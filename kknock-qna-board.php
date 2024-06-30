<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QnA Board</title>
    <link href="kknock-header.css" rel="stylesheet">
    <link href="kknock-board.css" rel="stylesheet">
    <script src="kknock-board.js"></script>
</head>
<body>
    <header>
        <h1>QnA Board</h1>
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
        <div class="top"><h2>게시판</h2></div>
        <button class="no" onclick="window.location.href='kknock-write.php?board_type=qna'">글쓰기</button>
        <div class="main-board">
            <table class="middle">
                <thead>
                    <tr align="center">
                        <th width="100">
                            <a href="javascript:void(0);" onclick="toggleSort('<?= $_GET['sort'] ?>', '<?= $_GET['order'] ?>', 'id')">
                                ID <span class="sort-indicator <?= ($_GET['sort'] == 'id') ? ($_GET['order'] == 'asc' ? '' : 'desc') : '' ?>"></span>                        
                            </a>
                        </th>
                        <th width="460">
                            <a href="javascript:void(0);" onclick="toggleSort('<?= $_GET['sort'] ?>', '<?= $_GET['order'] ?>', 'title')">
                                제목 <span class="sort-indicator <?= ($_GET['sort'] == 'title') ? ($_GET['order'] == 'asc' ? '' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                        <th width="130">
                            <a href="javascript:void(0);" onclick="toggleSort('<?= $_GET['sort'] ?>', '<?= $_GET['order'] ?>', 'name')">
                                작성자 <span class="sort-indicator <?= ($_GET['sort'] == 'name') ? ($_GET['order'] == 'asc' ? '' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                        <th width="180">
                            <a href="javascript:void(0);" onclick="toggleSort('<?= $_GET['sort'] ?>', '<?= $_GET['order'] ?>', 'written')">
                                작성일 <span class="sort-indicator <?= ($_GET['sort'] == 'written') ? ($_GET['order'] == 'asc' ? '' : 'desc') : '' ?>"></span>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = mysqli_connect('localhost', 'ybell', 'gungail127', 'kknock_db');
                    
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
                    $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
                    $allowed_sorts = ['id', 'title', 'name', 'written'];
                    $allowed_orders = ['asc', 'desc'];
                    
                    if (!in_array($sort, $allowed_sorts)) {
                        $sort = 'id';
                    }
                    if (!in_array($order, $allowed_orders)) {
                        $order = 'desc';
                    }

                    $sql = "SELECT * FROM qna_board ORDER BY $sort $order";
                    $res = mysqli_query($conn, $sql);

                    while($row = mysqli_fetch_array($res)) { ?>
                    <tr align="center">
                        <td><?php echo $row['id']; ?></td>
                        <td><a href="kknock-board-view.php?id=<?=$row['id']?>&board_type=qna"><?php echo $row['title']; ?></a></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['written']; ?></td>
                    </tr>
                    <?php 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
