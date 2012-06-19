<?php
$book_id = lavnn('book_id', $_REQUEST, '');
if ($book_id <> '') {
  $id = lavnn('id', $_REQUEST, '');
  if ($id <> '') {
    $runtime->db->sqlrun($module, 'UpdateBookingGuest', $_REQUEST);
  } else {
    $id = sid($module, 'InsertBookingGuest', $_REQUEST);
  }
  go("?p=$module/view&id=$book_id&tab=people");
} else {
  set_cookie('error', 'Unknown or corrupted booking');
  go("?p=$module/search");
}

?>
