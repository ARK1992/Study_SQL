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
$db['pass'] = "XXXXXX";

// set dbname
$db['dbname'] = "usrdata";

// 
$errorMessage = "";
$submitMessage = "";

// 
if (isset($_POST["submit"])) {
    
       $usr_name = $_SESSION["usr_name"];
       $st_date_year = $_POST["st_date_year"];
       $st_date_month = $_POST["st_date_month"];
       $st_date_day = $_POST["st_date_day"];
       $s_time_hour = $_POST["s_time_hour"];
       $s_time_min = $_POST["s_time_min"];
       $memo = h($_POST["memo"]);
       $usr_name = $usr_name.random_int(0, 99999);
       //$usr_name = $usr_name."-".date('Y-m-d H:i:s');
       
       // 
       $s_date = $st_date_year."/".$st_date_month."/".$st_date_day;
       $s_time = $s_time_hour.":".$s_time_min;
        
       // 2. 
       $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        
       // 3. 
       try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            //$stmt = $pdo->prepare("UPDATE usrdata SET st_bi = ? WHERE usr_name = '$usr_name'");
            $stmt = $pdo->prepare("INSERT INTO usrdata(usr_name, st_bi, st_time, memo) VALUES (?, ?, ?, ?)");
            
            // SQL実行
            $stmt->execute(array($usr_name, $s_date, $s_time, $memo));
            // 
            //$submitMessage = $usr_name."さんが予定を登録しました。";
            $submitMessage = preg_replace("/[^A-z]/", "", $usr_name)."さんが予定を登録しました。";
            sleep(5);
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー!';
        }
    }
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>予定登録画面</title>
    </head>
    <body>
        <h1>登録完了</h1>
        <div><font color="#ff0000"><?php echo h($errorMessage, ENT_QUOTES); ?></font></div>
        <div><font color="#0000ff"><?php echo h($submitMessage, ENT_QUOTES); ?></font></div>
        <ul>
            <li><a href="Main.php">Myスケジュールに戻る</a></li>
        </ul>
    </body>
</html>

