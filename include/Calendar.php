<?php
<?php
<?php
<?php
function getToday {
  ($second, $minute, $hour, $day, $month, $year, $weekday, $dayofyear, $isDST) = localtime(time());
  $year += 1900;
  $month++;
  $date = "$day.$month.$year";
}

function parseDate {
  ($date)
  
  $output = array();

  ($day, $month, $year) = split('\.', $date, 3);
  $year += ($year < 100 ? 1900 : 0);
  $output['day'] = sprintf("%02d", $day);
  $output['month'] = sprintf("%02d", $month);
  $output['year'] = sprintf("%4d", $year); 
  
  return %output;
}

function getYears {
  ($yearfrom, $yearto, $yearselect)
  
  $output = array();
  for ($year = $yearfrom; $year <= $yearto; $year++) {
    $hash = ('key' => $year, 'value' => $year);
    if ($year == $yearselect) {
      $hash['selected'] = 'selected';
    }
    push @output, $hash;
  }

  return $output;
}

function getMonthInfo {
  ($month, $year)
  
  $starts = dayOfWeek($year, $month, 1);
  $monthInfo['starts'] = ($starts > 0) ? $starts : 7;
  $monthInfo['length'] = daysInMonth($year, $month); 

  return %monthInfo;
}

function dayOfWeek {
  # The source: Journal on Recreational Mathematics, Vol. 22(4), pages 280-282, 1990.
  # The authors: Michael Keith and Tom Craver.
  ($y, $m, $d)
  $y-- if $m < 3;
  $d += 11 if $y < 1752 || $y == 1752 && $m < 9;
  if ($y >= 1752) {
    return (int(23*$m/9)+$d+4+($m<3?$y+1:$y-2) + int($y/4) - int($y/100) + int($y/400)) % 7;
  } else {
    return (int(23*$m/9)+$d+5+($m<3?$y+1:$y-2)+int($y/4))%7;
  }
}

function daysInMonth {
  ($y, $m)
  
  $output = 31;
  if ($m == 2) {
    $output = ($y % 4 == 0 && $y % 100 > 0 || $y % 400 == 0) ? 29 : 28;
  } elseif ($m == 4 || $m == 6 || $m == 9 || $m == 11) {
    $output = 30;    
  }
  
  return $output;
}

1;
?>

?>

?>

?>
