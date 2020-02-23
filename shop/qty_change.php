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
  $cart = $_SESSION['cart'];

  for($i=$max; 0 <= $i;$i--)
    {
      if(isset($_POST['delete'.$i])==true)
      {
        array_splice($cart,$i,1);
        array_splice($quantity,$i,1);
      }
    }
  $_SESSION['cart'] = $cart;
  $_SESSION['quantity'] = $quantity;

  header('Location: shop_cartcheck.php');
  exit();

?>