<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
  </head>
<body>

<?php

$month = $_POST['month'];

$vegi = ['', 'ブロッコリー', 'カリフラワー', 'レタス', 'みつば', 'アスパラガス',
          'セロリ', 'ナス', 'ピーマン', 'オクラ', 'さつまいも', '大根', 'ほうれんそう'];

print "${month}月は、${vegi[$month]}が旬です。"

?>

</body>
</html>