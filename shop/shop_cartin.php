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
  print 'ようこそ、';
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
    <title>あんどう時計店</title>
  </head>
<body>

<?php

try
{

$pro_code = $_GET['procode'];

if(isset($_SESSION['cart'])==true)
{
  $cart = $_SESSION['cart']; // 現在のカートの内容を$cartにコピーする
  $quantity = $_SESSION['quantity'];
  if(in_array($pro_code, $cart)==true)
  {
    print 'その商品はすでにカートに入っています。<br>';
    print '<a href="shop_list.php">商品一覧に戻ります。</a><br>';
    exit();
  }
}

$cart[] = $pro_code; // カートに商品を追加
$quantity[] = 1;
$_SESSION['cart'] = $cart; // $_SESSIONにカートを保管する
$_SESSION['quantity'] = $quantity;

}
catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

?>

カートに追加しました。<br>
<br>
<a href="shop_list.php">商品一覧に戻る</a>

</body>
</html>