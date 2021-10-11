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
$db['pass'] = "YOUR PASSWORD";

// set dbname
$db['dbname'] = "usrdata";

// 
$errorMessage = "";
$submitMessage = "";

// 
if (isset($_POST["submit"])) {
    
       $usr_name = $_SESSION["usr_name"];
       
       // 2. 
       $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        
       // 3. 
       try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            //$stmt = $pdo->prepare("UPDATE usrdata SET st_bi = ? WHERE usr_name = '$usr_name'");
            //$stmt = $pdo->prepare("INSERT INTO usrdata(usr_name, st_bi, st_time, memo) VALUES (?, ?, ?, ?)");
            $stmt = $pdo->prepare("SELECT usr_name, st_bi, st_time, memo FROM usrdata WHERE st_bi >= CURDATE() AND password = '' ORDER BY st_bi DESC");
            
            // SQL実行
            //$stmt->execute(array());
            $stmt->execute();
            
            // 
            //$submitMessage = $usr_name."さんが予定を登録しました。";
            //$submitMessage = preg_replace("/[^A-z]/", "", $usr_name)."さんが予定を登録しました。";
            
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー!';
        }
    }
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>予定表示</title>
    </head>
    <body>
        <h1>メンバーの予定</h1>
        <div><font color="#ff0000"><?php echo h($errorMessage, ENT_QUOTES); ?></font></div>
        <div><font color="#0000ff"><?php echo h($submitMessage, ENT_QUOTES); ?></font></div>
        <table border="1">
    <tr>
        <th>名前</th><th>予定日</th><th>開始時間</th><th>メモ</th>
    </tr>
  
<?php
foreach ($stmt as $row):
{
?>
  <tr>
    <td><?php echo $row["usr_name"]; ?></td>
    <td><?php echo $row["st_bi"]; ?></td>
    <td><?php echo $row["st_time"]; ?></td>
    <td><?php echo $row["memo"]; ?></td>
  </tr>
<?php
}
endforeach;
?>
</table>
    <br>
    <br>
    <a href="Main.php">Myスケジュールに戻る</a></li>
    </body>
</html>

