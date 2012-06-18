<?php
$pageParams = array();
$hotels = array();
if ($_REQUEST['location_id'] <> '') {
  @hotels = $runtime->s2a($module, 'ListGeneratorHotelsByLocation', array('gen_id' => $gen_id, 'location_id' => $_REQUEST['location_id']));
}
$pageParams['hotels'] = arr2ref(genOptions($hotels, 'hotel_id', 'hotel_name'));
print dot('hotelselection', $pageParams);
?>
