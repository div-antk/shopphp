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

if(isset($_SESSION['cart'])==true)
{
  $cart = $_SESSION['cart'];
  $quantity = $_SESSION['quantity'];
  $max = count($cart);
}
else
{
  $max = 0;
}

if($max == 0)
{
  print 'カートに商品がありません。<br>';
  print '<br>';
  print '<a href="shop_list.php">商品一覧に戻る</a><br>';
  exit();
}
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
<table border="1">
<tr>
<td> 商品 </td>
<td> 商品画像 </td>
<td> 価格 </td>
<td> 数量 </td>
<td> 小計 </td>
<td> 削除 </td>
</tr>
<?php for($i=0; $i<$max; $i++)
{
?>
<tr>
  <td><?php print $pro_name[$i]; ?></td>
  <td><?php print $pro_image[$i]; ?></td>
  <td><?php $total_price = $pro_price[$i] * $quantity[$i]; ?></td>
  <td><?php print "${total_price}円"; ?></td>
  <td><input type="text" name="quantity<?php print $i; ?>" value="<?php print $quantity[$i]; ?>" style="width:40px"></td>
  <td><input type="checkbox" name="delete<?php print $i; ?>"></td>
  <br>
</tr>
<?php
}
?>
</table>
<input type="hidden" name="max" value="<?php print $max; ?>">
<input type="submit" value="数量変更"><br>
<a href="shop_list.php">商品一覧に戻る</a>
<!-- <input type="button" onclick="history.back()" value="戻る"> -->
</form>

</body>
</html>