<?php
    session_start();
    
    // 
    if (!isset($_SESSION["usr_name"])) {
        header("Location: logout.php");
        exit;
    }
?>

<?php
// DEF PATH
define('FILENAME', './message.txt');

// DEF TIME ZONE
date_default_timezone_set('Asia/Tokyo');


// INIT OF VALUE
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();

if(!empty($_POST['btn_submit'])) {

    if(empty($_POST['view_name'])) {
        $error_message[] = '表示名を入力して下さい。';
    }

    if(empty($_POST['message'])) {
        $error_message[] = 'メッセージを入力して下さい。';
    }

    if(empty($error_message)) {

    // var_dump($_POST);
        if($file_handle = fopen(FILENAME, "a")) {
            $now_date = date("Y-m-d H:i:s");
            $data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";
            fwrite($file_handle, $data);
            fclose($file_handle);
            $success_message = $_SESSION["usr_name"].'がメッセージを書き込みました。';
        }
    }
}

if($file_handle = fopen(FILENAME, 'r')) {
    while($data = fgets($file_handle)) {

        $split_data = preg_split('/\'/', $data);

        $message = array(
            'view_name' => $split_data[1],
            'message' => $split_data[3],
            'post_date' => $split_data[5]
        );
        array_unshift($message_array, $message);
    }

    // CLOSE FILE
    fclose($file_handle);
}

?>

<!doctype html>
<link rel="stylesheet" href="/style.css" type="text/css" >
<html lang="ja">
   <head>
        <meta charset="UTF-8" >
        <title>スケジュール記載</title>
   </head>
    
   <body oncontextmenu="return false;">    
    <h1 style="background:#dfeffb">【Myスケジュール】</h1>
    
    <p> ようこそ。 <u><?php echo htmlspecialchars($_SESSION["usr_name"], ENT_QUOTES); ?></u> さん 予定入れといてー。 </p>
    <script type="text/javascript">
    
        dd = new Date();
        document.write(dd.toLocaleString());

    </script>
    <br>
    <br>

<?php
    function S_DATE($start, $end){
        for($i = $start; $i <= $end; $i++){
            print("<option value='".$i."'>".$i."</option>");
    }
}

?>

  <form method="POST" action="schedule_rec.php">
    <table border="0" align="center">
    <tr>
              <th align="right">日付</th>
          <td>
              <select name="st_date_year"><?php S_DATE(2019, 2022); ?></select>年
              <select name="st_date_month"><?php S_DATE(1, 12); ?></select>月
              <select name="st_date_day"><?php S_DATE(1, 31); ?></select>日
          </td>
    </tr>
    
    <tr>
    
              <th align="right">開始</th>
          <td>
              <select name="s_time_hour"><?php S_DATE(0, 23); ?></select>時
              <select name="s_time_min"><?php S_DATE(0, 59); ?></select>分
          </td>
    </tr>
    <br>
    <tr>
              <label for="memo">MEMO</label>
              <textarea id="memo" name="memo"></textarea><br>
    </tr>
    
    <tr>
              <th align="right"></th>
              <input type="submit" name="submit" value="登録"><br>
              <input type="reset"  name="clear"  value="クリア" >
    </tr>
    </table>
    <br>
  </form>
          <a href="logout.php">ログアウト</a>
          <br>
          <br>
  <form method="POST" action="yotei.php">
      <input type="submit" name="submit" value="予定表示" >
  </form>
          <br>
          <br>
  <h1 style="background:#dfeffb">【掲示板】</h1>

  <?php if(!empty($success_message)): ?>
      <p class="success_message"><?php echo $success_message; ?></p>
  <?php endif; ?>

  <?php if(!empty($error_message)): ?>
      <ul class="error_message">
          <?php foreach($error_message as $value): ?>
              <li><?php echo $value; ?></li>
  <?php endforeach; ?>
      </ul>
  <?php endif; ?>
  <form method="post">
      <div>
          <label for="view_name">表示名</label>
          <input id="view_name" type="text" name="view_name" value="">
      </div>
      <div>
          <label for="message">メッセージ</label>
          <textarea id="message" name="message"></textarea>
      </div>
          <input type="submit" name="btn_submit" value="書き込む">
  </form>
  <hr>
  <section>
  <?php if(!empty($message_array)): ?>
  <?php foreach($message_array as $value): ?>
  <article>
      <div class="info">
          <h2><?php echo $value['view_name']; ?></h2>
          <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
      </div>
      <p><?php echo $value['message']; ?></p>
  </article>
  <?php endforeach; ?>
  <?php endif; ?>
  </section>
  </body>
</html>
