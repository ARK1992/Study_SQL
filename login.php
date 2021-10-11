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
$db['pass'] = "XXXXXXX";

// set dbname
$db['dbname'] = "usrdata";
// 
$errorMessage = "";

// input login button
if (isset($_POST["login"])) {
    // empty
    if (empty($_POST["usr_name"])) {
        $errorMessage = 'ユーザー名が未入力です！';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です！';
    }
    if (!empty($_POST["usr_name"]) && !empty($_POST["password"])) {
        
        // 
        $usr_name = $_POST["usr_name"];
        
        // 
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        // 
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $stmt = $pdo->prepare('SELECT usr_name, password FROM usrdata WHERE usr_name = ?');
            $stmt->execute(array($usr_name));
            $password = $_POST["password"];
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    
                    $_SESSION["usr_name"] = $usr_name;
                    header("Location: Main.php");
                    exit();
                }
            } else {
                // eor msg
                $errorMessage = 'ユーザー名又はパスワードに誤りがあります！';
            }
          // 
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー！';
        }
    }
}
?>

 
<!DOCTYPE html>
<link rel="stylesheet" href="./style.css" type="text/css" />
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex,nofollow">
    <meta name="robots" content="noarchive">
    <title>ログイン画面</title>
</head>
<body oncontextmenu="return false;">

<h1>Karatsu Burgers</h1>
<img src="KaratsuBurgers.jpg" width="554" height="739">
<hr width="90%">

  <form id="loginForm" name="loginForm" action="" method="POST">
  <div>
     <font color="#ff0000"><?php echo h($errorMessage, ENT_QUOTES);?></font>
  </div>
  <label for="usr_name">NAME</label><input type="text" id="usr_name" name="usr_name" placeholder="YOUR NAME" value="<?php if(!empty($_POST["usr_name"])) {echo h($_POST["usr_name"], ENT_QUOTES);}?>">
      <br>
  <label for="password">PASS</label><input type="password" id="password" name="password" value="" placeholder="YOUR PASSWORD">
     <br>
     <br>
     <input type="submit" id="login" name="login" value="ログイン">
  </div>
  </form>
     <br>
  <form action="signup.php">
      <fieldset>
          <legend>新規登録フォーム</legend>
          <input type="submit" value="登録">
      </fieldset>
bakanta; 2019-03-16 updated;
</body>
</html>
