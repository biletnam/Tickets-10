<?php

$id = lavnn('id', $_REQUEST, 0);
$ids = join(',', lavnn('office'));
if ($id > 0) {
  # Also, check offices for this day type
  $existing = Arrays::cut_column(arr2ref(s2a($module, 'ListCalendarDayTypeOffices', array('id' => $id))), 'lngId');
  if (join(',', @existing) <> $ids) {
    foreach $off (lavnn('office')) {
      if (!in_array($id, $existing)) {
        $runtime->db->sqlrun($module, 'InsertCalendarDayTypeOffice', array('office' => $off, 'daytype' => $id));
      }
    }
    $runtime->db->sqlrun($module, 'DeleteObsoleteCalendarDayTypeOffices', array('offices' => $ids, 'daytype' => $id));
  }
  $_REQUEST['offices'] = $ids;
  $runtime->db->sqlrun($module, 'UpdateCalendarDayType', $_REQUEST);
  $_SESSION['flash'] = 'Calendar day type updated');
} else {
  $_REQUEST['offices'] = $ids;
  $newid = $runtime->sid($module, 'AddCalendarDayType', $_REQUEST);
  if ($newid > 0) {
    foreach $off (lavnn('office')) {
      $runtime->db->sqlrun($module, 'InsertCalendarDayTypeOffice', array('office' => $off, 'daytype' => $newid));
    }
    $_SESSION['flash'] = 'Calendar day type added');
  } else {
    $_SESSION['error'] = 'Failed to add calendar day type');
  }
}

go("?p=$module/settings&tab=daytypes");

?>
