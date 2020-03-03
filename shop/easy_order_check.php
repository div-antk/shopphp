<?php

session_start();
session_regenerate_id(true);
if(isset($_SESSION['member_login'])==false)
{
  print 'ログインされていません。<br>';
  print '<a href="shop_list.php">商品一覧へ</a>';
  exit();
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
  $code = $_SESSION['member_code'];

  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = 'SELECT m_name, m_email, m_postal_code1, m_postal_code2, m_address, m_tel FROM dat_member WHERE code=?';
  $stmt = $dbh->prepare($sql);
  $data[] = $code;
  $stmt->execute($data);
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  $dbh = null;

  $name = $rec['m_name'];
  $email = $rec['m_email'];
  $postal_code1 = $rec['m_postal_code1'];
  $postal_code2 = $rec['m_postal_code2'];
  $address = $rec['m_address'];
  $tel = $rec['m_tel'];

  print 'お名前<br>';
  print $name;
  print '<br><br>';

  print 'メールアドレス<br>';
  print $email;
  print '<br><br>';
  
  print '郵便番号<br>';
  print "${postal_code1}-${postal_code2}";
  print '<br><br>';
  
  print '住所<br>';
  print $address;
  print '<br><br>';

  print '電話番号<br>';
  print $tel;
  print '<br><br>';

  print '<form method="post" action="easy_order_done.php">';
    print '<input type="hidden" name="name" value="'.$name.'">';
    print '<input type="hidden" name="email" value="'.$email.'">';
    print '<input type="hidden" name="postal_code1" value="'.$postal_code1.'">';
    print '<input type="hidden" name="postal_code2" value="'.$postal_code2.'">';
    print '<input type="hidden" name="address" value="'.$address.'">';
    print '<input type="hidden" name="tel" value="'.$tel.'">';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="OK"><br>';
  print '</form>';

?>

</body>
</html>