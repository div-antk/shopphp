<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
  print 'ログインされていません。<br>';
  print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}
else
{
  print $_SESSION['staff_name'];
  print 'がログイン中<br>';
  print '<br>';
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>あんどう時計店</title>
  </head>
<body>

<?php

try
{

$pro_code = $_GET['procode'];

$dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = 'SELECT name,price,image FROM mst_product WHERE code=?';
$stmt = $dbh->prepare($sql);
$data[] = $pro_code;
$stmt->execute($data);

$rec = $stmt->fetch(PDO::FETCH_ASSOC);
$pro_name=$rec['name'];
$pro_price=$rec['price'];
$pro_image_name_old=$rec['image'];

$dbh = null;

if($pro_image_name_old=='')
{
  $disp_image='';
}
else
{
  $disp_image='<img src="./image/'.$pro_image_name_old.'">';
}

}
catch (Exception $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。';
  exit(); // 強制終了
}

?>

商品編集<br>
<br>
商品コード<br>
<?php print $pro_code; ?>
<br>
<br>
<form method="post" action="pro_edit_check.php" enctype="multipart/form-data">
  <input type="hidden" name="code" value="<?php print $pro_code;?>">
  <input type="hidden" name="image_name_old" value="<?php print $pro_image_name_old;?>">
  商品名<br>
  <input type="text" name="name" style="width:200px" value="<?php print $pro_name;?>"><br>
  価格<br>
  <input type="text" name="price" style="width:50px" value="<?php print $pro_price;?>"><br>
  <br>
  <?php print $disp_image; ?>
  <br>
  画像を選択してください。<br>
  <input type="file" name="image" style="width:400px"><br>
  <br>
  <input type="button" onclick="history.back()" value="戻る">
  <input type="submit" value="OK">
</form>

</body>
</html>