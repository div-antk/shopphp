<?php

session_start();
session_regenerate_id(true);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
  </head>
<body>

<?php

try{

require_once('../common/common.php');

$post = sanitize($_POST);

$name = $post['name'];
$email = $post['email'];
$postal_code1 = $post['postal_code1'];
$postal_code2 = $post['postal_code2'];
$address = $post['address'];
$tel = $post['tel'];

print "${name}様、ご注文ありがとうございました！<br><br>";
print "${email} にメールを送りましたので、ご確認ください。<br>";
print "商品は以下の住所に発送させていただきます。<br><br>";
print "${postal_code1}-${postal_code2}<br>";
print "${address}<br><br>";
print "${tel}<br>";

$text = '';
$text .= $name." 様 \n\n このたびは、当ショップをご利用いただきありがとうございました。\n";
$text .= "\n";
$text .= "ご注文商品\n";
$text .= "--------------------\n";

$cart = $_SESSION['cart'];
$quantity = $_SESSION['quantity'];
$max = count($cart);

$dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

for($i=0; $i<$max; $i++)
{
  $sql = 'SELECT name,price FROM mst_product WHERE code=?';
  $stmt = $dbh->prepare($sql);
  $data[0] = $cart[$i];
  $stmt->execute($data);

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  $name = $rec['name'];
  $price = $rec['price'];
  $total_quantity = $quantity[$i];
  $total_price = $price * $total_quantity;

  $text .= $name.' ';
  $text .= $price.'円 x ';
  $text .= $total_quantity.'個 = ';
  $text .= $total_price."円 \n";
}

$dbh = null;

$text .= "送料は無料です\n";
$text .= "--------------------\n";
$text .= "\n";
$text .= "代金は以下の口座にお振込みください。\n";
$text .= "モノポリー銀行 ボードウォーク支店 普通 1234567\n";
$text .= "入金が確認出来次第、発送いたします。\n";
$text .= "\n";
$text .= "////////////////////\n";
$text .= "\n";
$text .= "株式会社はたらくねこ\n";
$text .= "\n";
$text .= "////////////////////\n";

// print '<br>';
// print nl2br($text);

$title = 'ご注文ありがとうございます！';
$header = 'From: info@hatarakuneko.co.jp';
$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail($email, $title, $text, $header);

$title = 'お客様からのご注文がありました。';
$header = 'From: '.$email;
$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail('info@hatarakuneko.co.jp', $title, $text, $header);

}

catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

?>

</body>
</html>