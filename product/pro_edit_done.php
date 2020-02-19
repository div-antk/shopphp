<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
  </head>
<body>

<?php

try
{

$pro_code = $_POST['code'];
$pro_name = $_POST['name'];
$pro_price = $_POST['price'];
$pro_image_name_old = $_POST['image_name_old'];
$pro_image_name = $_POST['image_name'];

$pro_code = htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8');
$pro_name = htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8');
$pro_price = htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8');

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
catch (Exception $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。';
  // エラーメッセージを表示させる
  echo '捕捉した例外: ',  $e->getMessage(), "\n";
  exit();
}

?>

<a href="pro_list.php">戻る</a>

</body>
</html>