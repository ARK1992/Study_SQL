<?php
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

session_start();

// set host
$db['host'] = "XXX.XXX.X.XX";

// set user
$db['user'] = "XXXX";

// set pass
$db['pass'] = "XXXX";

// set dbname
$db['dbname'] = "usrdata";

// 
$errorMessage = "";
$signUpMessage = "";

// 
if (isset($_POST["signUp"])) {
    
    // 
    if (empty($_POST["usr_name"])) {
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    }
         
    if (!empty($_POST["usr_name"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        
        // 
        $usr_name = $_POST["usr_name"];
        $password = $_POST["password"];
        
        if(!empty($_POST["usr_name"]) && !preg_match("/\A(?=.*?[a-z])[a-z\d]{2,8}+\z/i", $_POST["usr_name"])) {
            $errorMessage = 'ERROR:ユーザーIDは半角英字2文字以上8文字以下で作成して下さい!';
            exit($errorMessage);
        }
        
        if (!empty($_POST["password"]) && !preg_match("/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{4,10}+\z/i", $_POST["password"])) {
            $errorMessage = 'ERROR:パスワードは半角英数4文字以上10文字以下で作成して下さい!';
            exit($errorMessage);
        }
        
        // 
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
       
        // 
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            $stmt = $pdo->prepare('INSERT INTO usrdata(usr_name, password) VALUES (?, ?)');
           
            $stmt->execute(array($usr_name, password_hash($password, PASSWORD_DEFAULT)));

            $signUpMessage = '登録が完了しました。あなたのユーザー名は '. $usr_name. ' です。パスワードは '. $password. ' です。';
            sleep(5);
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー!入力されたユーザー名は使用されております。';
            //print("INPUT: $usr_name, $password");
            //exit('データベース接続失敗。'.$e->getMessage()); 
        }
    } else if ($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
    } 
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <title>新規登録</title>
    </head>
    <body>
        <h1>新規登録画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>新規登録フォーム</legend>
                <div><font color="#ff0000"><?php echo h($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo h($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="usr_name">ユーザー名</label><input type="text" id="usr_name" name="usr_name" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["usr_name"])) {echo htmlspecialchars($_POST["usr_name"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <label for="password2">パスワード(確認用)</label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力">
                <br>
                <input type="submit" id="signUp" name="signUp" value="新規登録">
            </fieldset>
        </form>
        <br>
        <form action="login.php">
            <input type="submit" value="戻る">
        </form>
    </body>
</html>
