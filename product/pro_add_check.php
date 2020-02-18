<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
  </head>
<body>

<?php

$pro_name = $_POST['name'];
$pro_price = $_POST['price'];
$pro_image = $_FILES['image'];

$pro_name = htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8');
$pro_price = htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8');

if($pro_name=='')
{
  print '商品名が入力されていません。<br>';
}
else
{
  print '商品名 : ';
  print $pro_name;
  print '<br>';
}

if(preg_match('/\A[0-9]+\z/', $pro_price)==0) // 正規表現で半角数字
{
  print '価格を半角数字で入力してください。<br>';
}
else
{
  print '価格 : ';
  print $pro_price;
  print '円<br>';
}

if($pro_image['size'] > 0)
{
  if($pro_image['size'] > 10000)
  {
    print "画像が大きすぎます";
  }
  else
  {
    move_uploaded_file($pro_image['tmp_name'],'./image/'.$pro_image['name']);
    print '<img src="./image/'.$pro_image['name'].'">';
    print '<br>';
  }
}

if($pro_name=='' || preg_match('/\A[0-9]+\z/', $pro_price)==0 || $pro_image['size'] > 10000)
{
  print '<form>';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '</form>';
}
else
{
  print '上記の商品を追加します。<br>';
  print '<form method="post" action="pro_add_done.php">';
  print '<input type="hidden" name="name" value="'.$pro_name.'">';
  print '<input type="hidden" name="price" value="'.$pro_price.'">';
  print '<input type="hidden" name="image_name" value="'.$pro_image['name'].'">';
  print '<br>';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '<input type="submit" value="OK">';
  print '</form>';
}

?>

</body>
</html>