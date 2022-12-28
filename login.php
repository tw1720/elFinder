<?php
session_start();
if (!empty($_SESSION['userId'])){
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>線上檔案管理系統</title>
        <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://v4.bootcss.com/docs/examples/sign-in/signin.css">
        <style>
            .form-signin input[type="text"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
        </style>
    </head>
    <body class="text-center">
        <form class="form-signin" onsubmit="return login()">
            <div style="background:#FFF">
                <img src="https://studio-42.github.io/elFinder/images/elfinder-logo.png" width="30%">
                <h4>線上檔案管理系統</h4>
            </div>
            <label for="inputUserId" class="sr-only">userID</label>
            <input type="text" id="inputUserId" class="form-control" placeholder="userID" maxlength="20" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" maxlength="20" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">登入</button>
            <p class="mt-5 mb-3 text-muted">&copy; elFinder.</p>
        </form>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://getbootstrap.net/example/assets/dist/js/bootstrap.bundle.js"></script>
    <script>
        //登入
        const login = () => {
            const jsonData = {
                        "do": "login",
                        "loginId": $("#inputUserId").val(),
                        "loginPw": $("#inputPassword").val(),
                };
            
            $.post("api.php", JSON.stringify(jsonData),
                function(result){
                    if(result.success){
                        // alert("登入成功");
                        window.location.href = "index.php";
                    }else{
                        alert("登入失敗");
                    }
            }, "json");
            return false;
        }
    </script>
</html>
