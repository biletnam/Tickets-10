<?php
$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r('resorts', 'GetHotelInfo', array('id' => $id));
  print $runtime->txt->do_template($module, 'hotel.tooltip', $pageParams);
}

?>
