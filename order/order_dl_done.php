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
    <title>ろくまる農園</title>
  </head>
<body>

<?php

try
{
  $year = $_POST['year'];
  $month = $_POST['month'];
  $day = $_POST['day'];
  
  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = '
  SELECT
    dat_order_customer.code,
    dat_order_customer.date,
    dat_order_customer.customer_code,
    dat_order_customer.c_name,
    dat_order_customer.c_email,
    dat_order_customer.c_postal_code1,
    dat_order_customer.c_postal_code2,
    dat_order_customer.c_address,
    dat_order_customer.c_tel,
    dat_order_product.order_code,
    mst_product.name,
    dat_order_product.price,
    dat_order_product.quantity
  FROM
    dat_order_customer,
    dat_order_product,
    mst_product
  WHERE
    dat_order_customer.code=dat_order_product.order_code
  AND
    dat_order_product.product_code=mst_product.code
  AND
    substr(dat_order_customer.date,1,4)=?
  AND
    substr(dat_order_customer.date,6,2)=?
  AND
    substr(dat_order_customer.date,9,2)=?
  ';

  $stmt = $dbh->prepare($sql);
  $data[] = $year;
  $data[] = $month;
  $data[] = $day;
  $stmt->execute($data);

  $dbh = null;

  $csv = '注文コード, 注文日時, 会員番号, お名前, メール, 郵便番号, 住所, TEL, 商品コード, 商品名, 価格, 数量';
  $csv .= "\n";
  while(true)
  {
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rec == false)
    {
      break;
    }
    $csv .= $rec['code'];
    $csv .= ',';
    $csv .= $rec['date'];
    $csv .= ',';
    $csv .= $rec['customer_code'];
    $csv .= ',';
    $csv .= $rec['c_name'];
    $csv .= ',';
    $csv .= $rec['c_email'];
    $csv .= ',';
    $csv .= $rec['c_postal_code1'].'-'.$rec['c_postal_code2'];
    $csv .= ',';
    $csv .= $rec['c_address'];
    $csv .= ',';
    $csv .= $rec['c_tel'];
    $csv .= ',';
    $csv .= $rec['order_code'];
    $csv .= ',';
    $csv .= $rec['name'];
    $csv .= ',';
    $csv .= $rec['price'];
    $csv .= ',';
    $csv .= $rec['quantity'];
    $csv .= "\n";
  }

  print nl2br($csv);
}

catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

?>

<br>
<a href="../staff_login/staff_top.php">メインメニューへ</a><br>

</body>
</html>