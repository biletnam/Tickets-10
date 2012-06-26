<?php

$id = lavnn('id', $_REQUEST, 0);
$hotel = lavnn('hotel_id', $_REQUEST, 0);
$local_type = lavnn('local_apt_id', $_REQUEST, 0);
if ($local_type == 0) {
  $_SESSION['error'] = 'Please select a local type for the apartment');
} else {
  $localtypeInfo = $runtime->s2r($module, 'GetLocalTypeInfo', array('id' => $local_type));
  $_REQUEST['apt_type_id'] = $localtypeInfo['apt_type_id'];
  if ($id > 0 && $hotel > 0) {
    $runtime->db->sqlrun($module, 'UpdateHardBlock', $_REQUEST);
  } else {
    $id = $runtime->sid($module, 'InsertHardBlock', $_REQUEST);
  }
}
if ($hotel > 0) {
  go("?p=resorts/edithotel&id=$hotel&tab=hb");
} else {
  go("?p=resorts/hotels");
}

1;

?>
