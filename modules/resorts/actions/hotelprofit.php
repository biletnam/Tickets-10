<?php
$blocks = $runtime->s2a($module, 'GetHotelProfitabilityStats', array('id' => lavnn('id')));
print $r->txt->do_template($module, 'hotelprofit', array('blocks' => $blocks));
?>
