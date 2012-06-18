<?php

use objMerlin;
$objM = new objMerlin($r);

foreach $ci (s2a($module, 'GetUnimportedCostItems')) {
  $ids = $objM->save_tour_cost_items($ci['id'], $ci['CostItems']);
  if ($ids <> '') {
    print "inserted cost items for " .$ci['id']. ": $ids<br>";
  } else {
    print $objM['lastwarning']. '<br>';
  }
}

1;

?>
