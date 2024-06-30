<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Main</title>
    <link href="/kknock-header.css" rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const userCardTemplate = document.querySelector("[data-user-template]");
            const userCardContainer = document.querySelector("[data-user-cards-container]");
            const searchInput = document.querySelector("[data-search]");

            // 사용자 데이터 배열
            let users = [];

            // 검색어에 따라 사용자 필터링하는 함수
            function filterUsers(value) {
                users.forEach((user) => {
                    const isVisible =
                    user.name.toLowerCase().includes(value) ||
                    user.email.toLowerCase().includes(value);
                    user.element.classList.toggle("hide", !isVisible);
                });
            }

            // 검색 입력 이벤트 리스너 등록
            searchInput.addEventListener("input", (e) => {
                const value = e.target.value.toLowerCase();
                filterUsers(value);
            });

            // 사용자 데이터 가져오기
            fetch("kknock-fetch-users.php")
                .then((res) => res.json())
                .then((data) => {
                    // 데이터를 가지고 사용자 카드 생성
                    users = data.map((user) => {
                        // 템플릿의 내용을 복제하여 새로운 노드를 생성, true 매개변수는 하위 요소들도 함께 복제하도록 지정
                        const card = userCardTemplate.content.cloneNode(true).children[0];

                        const header = card.querySelector("[data-header]");
                        const body = card.querySelector("[data-body]");

                        //선택된 헤더와 바디 요소의 textContent를 사용자의 이름과 이메일로 설정. 이를 통해 해당 요소들에 데이터가 표시
                        header.textContent = user.username;
                        body.textContent = user.user_id;

                        //생성된 카드 요소(card)를 사용자 카드 컨테이너(userCardContainer)에 추가
                        userCardContainer.append(card);

                        //생성한 사용자 객체를 반환
                        return { name: user.username, email: user.user_id, element: card };
                    });
                })
                .catch((err) => console.error(err));
        });
    </script>
    <style>
        .search-wrapper {
            margin-bottom: 20px;
        }
        .search-wrapper h2 {
            margin-bottom: 10px;
            color: #343a40;
        }
        .search-wrapper input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .user-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card .header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .card .body {
            padding: 15px;
            font-size: 16px;
            color: #495057;
        }
        .hide {
            display: none;
        }
        .profileBox{
            width: 250px;
            height: 70px;
            background-color:black;
            color:white;
        }
        #boxName {
            margin-bottom: 27px;
        }
    </style>
</head>
<body>
    <header>
        <h1>MAIN</h1>
        <nav>
            <a href="kknock-main.php">Main</a>
            <a href="kknock-board.php">Board</a>
            <a href="kknock-free-board.php">Free Board</a>
            <a href="kknock-qna-board.php">QnA Board</a>
        </nav>
    </header>
    <?php
        if (!isset($_SESSION['userId']) || !isset($_SESSION['userName'])) {
            echo "<p>로그인을 해 주세요.</p>";
            echo "<p><button onclick=\"window.location.href='kknock-login.php'\">로그인</button> 
            <button onclick=\"window.location.href='kknock-join.php'\">회원가입</button></p>";
        } else {
            $userId = $_SESSION['userId'];
            $userName = $_SESSION['userName'];
            echo "<div class=\"profileBox\"><div id=\"boxName\"><p>$userName($userId)님 환영합니다.</div>";
            echo "<p><button onclick=\"window.location.href='kknock-logout.php'\">로그아웃</button></p></div>";
        }
    ?>
    <div>
        <form class="search-wrapper">
            <h2><label for="search" class="a11yHidden">사용자 검색</label></h2>
            <input type="search" id="search" data-search />
        </form>
        <div class="user-cards" data-user-cards-container></div>
        <template data-user-template>
            <div class="card">
                <div class="header" data-header></div>
                <div class="body" data-body></div>
            </div>
        </template>
    </div>
</body>
</html>
