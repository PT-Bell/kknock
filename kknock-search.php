<?php
if (isset($_GET['search']) && isset($_GET['criteria'])) {
    $conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $criteria = $_GET['criteria'];

    // 정렬 기준과 순서를 받아옵니다.
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $order = isset($_GET['order']) ? $_GET['order'] : 'asc';

    // 정렬 기준이 유효한 값인지 확인합니다.
    $allowed_sorts = ['board', 'id', 'title', 'name', 'written'];
    if (!in_array($sort, $allowed_sorts)) {
        $sort = 'id';
    }

    // 정렬 순서가 유효한 값인지 확인합니다.
    $allowed_orders = ['asc', 'desc'];
    if (!in_array($order, $allowed_orders)) {
        $order = 'asc';
    }

    $results = array();

    // 각 게시판에서 검색을 수행하고 결과를 $results 배열에 추가합니다.
    $queries = [
        "SELECT '일반 게시판' AS board, id, title, writer, name, written, content, file FROM general_board WHERE $criteria LIKE '%$search%'",
        "SELECT '자유 게시판' AS board, id, title, writer, name, written, content, file FROM free_board WHERE $criteria LIKE '%$search%'",
        "SELECT '질의응답 게시판' AS board, id, title, writer, name, written, content, file FROM qna_board WHERE $criteria LIKE '%$search%'"
    ];

    foreach ($queries as $query) {
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $results[] = $row;
            }
        }
    }

    // 결과를 정렬합니다.
    usort($results, function($a, $b) use ($sort, $order) {
        if ($a[$sort] == $b[$sort]) {
            return 0;
        }
        return ($order == 'asc' ? ($a[$sort] < $b[$sort]) : ($a[$sort] > $b[$sort])) ? -1 : 1;
    });

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검색 결과</title>
    <link href="kknock-header.css" rel="stylesheet">
    <link href="kknock-board.css" rel="stylesheet">
    <script src="kknock-board.js"></script>
</head>
<body>
    <header>
        <h1>게시판 검색 결과</h1>
        <nav>
            <a href="kknock-main.php">Main</a>
            <a href="kknock-general-board.php">Board</a>
            <a href="kknock-free-board.php">Free Board</a>
            <a href="kknock-qna-board.php">QnA Board</a>
        </nav>
    </header>
    <div class="container">
        <form method="GET" action="kknock-search.php">
            <label for="search">게시글 검색:</label>
            <input type="text" id="search" name="search" required>
            <select name="criteria">
                <option value="title">제목</option>
                <option value="name">작성자</option>
                <option value="content">내용</option>
            </select>
            <button type="submit">검색</button>
        </form>
        <?php if (isset($_GET['search']) && !empty($results)): ?>
            <h2>검색 결과</h2>
            <table class="middle">
                <thead>
                    <tr align="center">
                        <th width="150">
                            <a href="?search=<?= htmlspecialchars($search) ?>&criteria=<?= htmlspecialchars($criteria) ?>&sort=board&order=<?= ($sort == 'board' && $order == 'asc') ? 'desc' : 'asc' ?>">
                                게시판 <span class="sort-indicator <?= ($sort == 'board') ? ($order == 'asc' ? 'asc' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                        <th width="410">
                            <a href="?search=<?= htmlspecialchars($search) ?>&criteria=<?= htmlspecialchars($criteria) ?>&sort=title&order=<?= ($sort == 'title' && $order == 'asc') ? 'desc' : 'asc' ?>">
                                제목 <span class="sort-indicator <?= ($sort == 'title') ? ($order == 'asc' ? 'asc' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                        <th width="130">
                            <a href="?search=<?= htmlspecialchars($search) ?>&criteria=<?= htmlspecialchars($criteria) ?>&sort=name&order=<?= ($sort == 'name' && $order == 'asc') ? 'desc' : 'asc' ?>">
                                작성자 <span class="sort-indicator <?= ($sort == 'name') ? ($order == 'asc' ? 'asc' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                        <th width="180">
                            <a href="?search=<?= htmlspecialchars($search) ?>&criteria=<?= htmlspecialchars($criteria) ?>&sort=written&order=<?= ($sort == 'written' && $order == 'asc') ? 'desc' : 'asc' ?>">
                                작성일 <span class="sort-indicator <?= ($sort == 'written') ? ($order == 'asc' ? 'asc' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr align="center">
                            <td><?php echo htmlspecialchars($row['board']); ?></td>
                            <td><a href="kknock-board-view.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['written']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_GET['search']) && empty($results)): ?>
            <div class="no-results">
                <p>검색 결과가 없습니다.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
