<?php


$year = $_POST['year'];
$j_cal = era($year);
print $j_cal;

function era($year)
{
  if(1868 <= $year && $year <= 1911)
  {
    $era = "明治";
  }
  elseif(1912 <= $year && $year <= 1925)
  {
    $era = "大正";
  }
  elseif(1926 <= $year && $year <= 1988)
  {
    $era = "昭和";
  }
  elseif(1989 <= $year && $year <= 2019)
  {
    $era = "平成";
  }
  elseif(2020 == $year)
  {
    $era = "令和";
  }
  else
  {
    print "無効な数値です";
  }
  return($era);
}

function sanitize($before)
{
  foreach($before as $key=>$value)
  {
    $after[$key] = htmlspecialchars($value,ENT_QUOTES,'UTF-8');
  }
  return $after;
}

?>