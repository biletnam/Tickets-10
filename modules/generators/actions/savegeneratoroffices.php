<?php
$generator = lavnn('id', $_REQUEST, 0);
if ($generator > 0) {
  $existing = Arrays::cut_column(arr2ref(s2a($module, 'ListGeneratorBookingOffices', array('id' => $generator))), 'lngId');
  $ids = join(',', lavnn('office'));
  if (join(',', @existing) <> $ids) {
    foreach $id (lavnn('office')) {
      if (!in_array($id, $existing)) {
        $runtime->db->sqlrun($module, 'InsertGeneratorBookingOffice', array('office' => $id, 'generator' => $generator));
      }
    }
    $runtime->db->sqlrun($module, 'DeleteObsoleteGeneratorBookingOffices', array('offices' => $ids, 'generator' => $generator));
  }
  go("?p=$module/view&id=$generator&tab=offices");
} else {
  go("?p=$module/search");
}

?>
