<?php
$blocks = $runtime->s2a($module, 'GetHotelProfitabilityStats', array('id' => lavnn('id')));
print dot('hotelprofit', array('blocks' => $blocks));
?>
