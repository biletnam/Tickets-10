<?php
$blocks = $runtime->s2a($module, 'GetHotelProfitabilityStats', array('id' => lavnn('id')));
print $runtime->txt->do_template($module, 'hotelprofit', array('blocks' => $blocks));
?>
