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

}

catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

?>

</body>
</html>