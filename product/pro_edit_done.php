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


require_once('../common/common.php');

$post = sanitize($_POST);

$pro_code = $post['code'];
$pro_name = $post['name'];
$pro_price = $post['price'];
$pro_image_name_old = $post['image_name_old'];
$pro_image = $post['image'];

$dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = 'UPDATE mst_product SET name=?,price=?,image=? WHERE code=?';
$stmt = $dbh->prepare($sql);
$data[] = $pro_name;
$data[] = $pro_price;
$data[] = $pro_image_name;
$data[] = $pro_code;
$stmt->execute($data);

$dbh = null;

if($pro_image_name_old != $pro_image_name) // 同じ画像がアップされたら何もしない
{
  if($pro_image_name_old !='')
  {
    unlink('./image/'.$pro_image_name_old);
  }
}

print '編集しました。<br>';

}
catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

?>

<a href="pro_list.php">戻る</a>

</body>
</html>