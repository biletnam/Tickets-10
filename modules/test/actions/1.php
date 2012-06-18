<?php
$s = '1002^1^300.00|980^1^39.95|998^1^100.00';
$fieldnames = qw(item_id count price);
$arr = Arrays::parse_string_to_array($s, '\|', '\^', $fieldnames);
print Dumper($arr);


use objMerlin;
$objM = new objMerlin($r);
print $objM->save_tour_cost_items(2, '1002^1^300.00|980^1^39.95|998^1^100.00');
?>
