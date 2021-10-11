<?php 
session_start();

if (isset($_SESSION["usr_name"])) {
    $errorMessage = "ログアウトしました。";
} else {
    $errorMessage = "セッションがタイムアウトしました。";
}

// delte session val
$_SESSION = array();

// session data all clear
session_destroy();
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ログアウト</title>
    </head>
    <body>
        <h1>ログアウト画面</h1>
        <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES);?></div>
             <a href="login.php">ログイン画面に戻る</a>
    </body>
</html>
