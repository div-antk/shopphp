<?php

session_start(); // ユーザー認証は一番はじめに書かなければならない
require_once('../common/common.php');

try
{
  $post=sanitize($_POST);
  $member_email = $post['email'];
  $member_pass = $post['pass'];
  
  $member_pass = md5($member_pass);

  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // $sql = 'SELECT code,m_name FROM dat_member WHERE m_email=?';
  $sql = 'SELECT code,m_name FROM dat_member WHERE m_password=? AND m_email=?';
  $stmt = $dbh->prepare($sql);
  $data[] = $member_pass;
  $data[] = $member_email;
  $stmt->execute($data);

  $dbh = null;

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  if($rec == false)
  {
    print 'メールアドレス、またはパスワードが間違っています。<br>';
    print '<a href="member_login.html">戻る</a>';
  }
  else
  {
    $_SESSION['member_login'] = 1;
    $_SESSION['member_code'] = $rec['code'];
    $_SESSION['member_name'] = $rec['m_name'];
    header('Location: shop_list.php');
    exit();
  }
}

catch (PDOException $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br>' .$e->getMessage()."<br>";
  exit();
}

// catch (Exception $e)
// {
//   print 'ただいま障害により大変ご迷惑をおかけしております。';
//   // エラーメッセージを表示させる
//   echo '捕捉した例外: ',  $e->getMessage(), "\n";
//   exit();
// }

?>