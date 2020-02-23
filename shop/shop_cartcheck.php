<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['member_login'])==false)
{
  print 'ようこそ、ゲスト様。';
  print '<a href="member_login.html">会員ログイン</a><br>';
  print '<br>';
}
else
{
  print 'ようこそ';
  print $_SESSION['member_name'];
  print '様<br>';
  print '<a href="member_logout.php">ログアウト</a><br>';
  print '<br>';
}
?>

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

$cart = $_SESSION['cart'];
$quantity = $_SESSION['quantity'];
$max = count($cart);

// var_dump($cart);
// exit();

$dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($cart as $key => $val)
{
  $sql = 'SELECT code,name,price,image FROM mst_product WHERE code=?';
  $stmt = $dbh->prepare($sql);
  $data[0] = $val;
  $stmt->execute($data);

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  $pro_name[] = $rec['name'];
  $pro_price[] = $rec['price'];
  if($rec['image']=='')
  {
    $pro_image[] = '';
  }
  else
  {
    $pro_image[] = '<img src="../product/image/'.$rec['image'].'">';
  }
}
$dbh = null;
}

catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

?>

ショッピングカート<br>
<br>
<form method="post" action="qty_change.php">
<?php for($i=0; $i<$max; $i++)
{
?>
  <?php print $pro_name[$i]; ?>
  <?php print $pro_image[$i]; ?>
  <?php $total_price = $pro_price[$i] * $quantity[$i]; ?>
  <?php print "${total_price}円"; ?>
  <input type="checkbox" name="delete<?php print $i; ?>">
  <input type="text" name="quantity<?php print $i; ?>" value="<?php print $quantity[$i]; ?>" style="width:40px">
  <br>
<?php
}
?>

<input type="hidden" name="max" value="<?php print $max; ?>">
<input type="submit" value="数量変更"><br>
<input type="button" onclick="history.back()" value="戻る">
</form>

</body>
</html>