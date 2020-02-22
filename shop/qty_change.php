<?php

  session_start();
  session_regenerate_id(true);

  require_once('../common/common.php');

  $post = sanitize($_POST);

  $max = $post['max'];
  for($i = 0; $i < $max; $i++)
  {
    $quantity[] = $post['quantity'.$i];
  }

  $_SESSION['quantity'] = $quantity;

  header('Location: shop_cartcheck.php');
  exit();

?>