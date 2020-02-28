<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
  </head>
<body>

<?php

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
$password2 = $post['password2'];
$gender = $post['gender'];
$birth = $post['birth'];

$okflg = true;

if($name=='')
{
  print 'お名前が入力されていません。<br><br>';
  $okflg = false;
}
else
{
  print "お名前<br>${name}<br><br>";
}

if(preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/',$email)==0)
{
  print 'メールアドレスを正確に入力してください。<br><br>';
  $okflg = false;
}
else
{
  print "メールアドレス<br>${email}<br><br>";
}

if(preg_match('/\A[0-9]+\z/', $postal_code1)==0 || preg_match('/\A[0-9]+\z/', $postal_code2)==0)
{
  print '郵便番号は半角数字で入力してください。<br><br>';
  $okflg = false;
}
else
{
  print "郵便番号<br>${postal_code1}-${postal_code2}<br><br>";
}

// if(preg_match('/\A[0-9]+\z/', $postal_code2)==0)
// {
//   print '郵便番号は半角数字で入力してください。<br><br>';
// }

if($address=='')
{
  print '住所が入力されていません。<br><br>';
  $okflg = false;
}
else
{
  print "住所<br>${address}<br><br>";
}

if(preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel)==0)
{
  print '電話番号を正確に入力してください。<br><br>';
  $okflg = false;
}
else
{
  print "電話番号<br>${tel}<br><br>";
}

if($order == 'order_member')
  {
    print '会員登録希望<br><br>';
    if($password == '')
    {
      print 'パスワードを入力してください。<br><br>';
      $okflg = false;
    }
    elseif($password != $password2)
    {
      print 'パスワードが一致しません。<br><br>';
      $okflg = false;
    }
    else
    {
      print '性別<br>';

      switch($gender)
      {
        case 'male':
          print '男性';
          break;
        case 'female':
          print '女性';
          break;
      }
      print '<br><br>';

      print '生まれ年<br>';
      print "${birth}年代";
      print '<br><br>';
    }
  }

if($okflg == true)
{
  print '<form method="post" action="order_form_done.php">';
    print '<input type="hidden" name="name" value="'.$name.'">';
    print '<input type="hidden" name="email" value="'.$email.'">';
    print '<input type="hidden" name="postal_code1" value="'.$postal_code1.'">';
    print '<input type="hidden" name="postal_code2" value="'.$postal_code2.'">';
    print '<input type="hidden" name="address" value="'.$address.'">';
    print '<input type="hidden" name="tel" value="'.$tel.'">';
    // print '<input type="hidden" name="tel" value="'.$tel.'">';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="OK"><br>';
  print '</form>';
}
else
{
  print '<form>';
    print '<input type="button" onclick="history.back()" value="戻る">';
  print '</form>';
}



?>

</body>
</html>