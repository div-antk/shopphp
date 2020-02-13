<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
  </head>
<body>

<?php

// 入力データを受け取って変数にコピー
$staff_name = $_POST['name'];
$staff_pass = $_POST['pass'];
$staff_pass2 = $_POST['pass2'];

// 入力データに安全対策
$staff_name = htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8');
$staff_pass = htmlspecialchars($staff_pass, ENT_QUOTES, 'UTF-8');
$staff_pass2 = htmlspecialchars($staff_pass2, ENT_QUOTES, 'UTF-8');

if($staff_name=='')
{
  print 'スタッフ名が入力されていません。<br>';
}
else
{
    // スタッフ名が入力されていたらスタッフ名を表示
    print 'スタッフ名 :';
    print $staff_pass;
    print '<br>';
}

if($staff_pass=='')
{
  print 'パスワードが入力されていません。<br>';
}

if($staff_pass != $staff_pass2)
{
  print 'パスワードが一致しません。<br>';
}

// 入力が間違っていたら戻るボタンのみを表示
if($staff_name=='' || $staff_pass != $staff_pass2)
{
  print '<form>';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '</form>';
}
else
{
  $staff_pass = md5($staff_pass);
  print '<form method="post" action="staff_add_done.php">';
  print '<input type="hidden" name="name" value="'.$staff_name.'">';
  print '<input type="hidden" name="pass" value="'.$staff_pass.'">';
  print '<br>';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '<input type="submit" value="OK">';
  print '</form>';
}

?>

</body>