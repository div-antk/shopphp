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
$order = $post['order'];
$password = $post['password'];
// $password2 = $post['password2'];
$gender = $post['gender'];
$birth = $post['birth'];

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

// 注文した商品の情報

$cart = $_SESSION['cart'];
$quantity = $_SESSION['quantity'];
$max = count($cart);

$dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

for($i=0 ; $i<$max ; $i++)
{
  $sql = 'SELECT name,price FROM mst_product WHERE code=?';
  $stmt = $dbh->prepare($sql);
  $data[0] = $cart[$i];
  $stmt->execute($data);

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  $pro_name = $rec['name'];
  $price = $rec['price'];
  $price_array[] = $price;
  $total_quantity = $quantity[$i];
  $total_price = $price * $total_quantity;

  $text .= $pro_name.' ';
  $text .= $price.'円 x ';
  $text .= $total_quantity.'個 = ';
  $text .= $total_price."円 \n";
}

// 安全な処理のためテーブルロックをかける

$sql = 'LOCK TABLES dat_order_customer WRITE,dat_order_product WRITE, dat_member WRITE';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$lastmembercode = 0;
if($order == 'order_member')
{
  $sql = 'INSERT INTO dat_member
  (m_name, m_password, m_email, m_postal_code1, m_postal_code2, m_address, m_tel, m_gender, m_born) VALUES(?,?,?,?,?,?,?,?,?)';
  $stmt = $dbh->prepare($sql);
  $data = array();
  $data[] = $name;
  $data[] = md5($password);
  $data[] = $email;
  $data[] = $postal_code1;
  $data[] = $postal_code2;
  $data[] = $address;
  $data[] = $tel;
  switch($gender)
      {
        case 'male':
          $data[] = 1;
          break;
        case 'female':
          $data[] = 2;
          break;
      }
  $data[] = $birth;
  $stmt->execute($data);

  $sql = 'SELECT LAST_INSERT_ID()';
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  $lastmembercode = $rec['LAST_INSERT_ID()'];
}

// 注文データを追加する

$sql = 'INSERT INTO dat_order_customer (customer_code, c_name, c_email, c_postal_code1, c_postal_code2, c_address, c_tel) VALUES (?,?,?,?,?,?,?)';
$stmt = $dbh->prepare($sql);
$data = array();
$data[] = $lastmembercode;
$data[] = $name;
$data[] = $email;
$data[] = $postal_code1;
$data[] = $postal_code2;
$data[] = $address;
$data[] = $tel;
$stmt->execute($data);

// 直前で追加した注文コードを取得する

$sql = 'SELECT LAST_INSERT_ID()'; // 直近で発番された番号を取得
$stmt = $dbh->prepare($sql);
$stmt->execute();
$rec = $stmt->fetch(PDO::FETCH_ASSOC);
$lastcode = $rec['LAST_INSERT_ID()'];

// 商品明細を追加する

for($i=0 ; $i<$max ; $i++)
{
  $sql = 'INSERT INTO dat_order_product (order_code, product_code, price, quantity) VALUES (?,?,?,?)';
  $stmt = $dbh->prepare($sql);
  $data = array();
  $data[] = $lastcode;
  $data[] = $cart[$i];
  $data[] = $price_array[$i];
  $data[] = $quantity[$i];
  $stmt->execute($data);
}

// テーブルロックの解除

$sql = 'UNLOCK TABLES';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$dbh = null;

// 自動返信メールの文面

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

// \nを<br>に変換

// print '<br>';
// print nl2br($text);

// お客様にメールを送信

$title = 'ご注文ありがとうございます！';
$header = 'From: info@hatarakuneko.co.jp';
$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail($email, $title, $text, $header);

// こちらにもメールを送信

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

<br>
<a href="shop_list.php">商品一覧へ戻る</a>

</body>
</html>