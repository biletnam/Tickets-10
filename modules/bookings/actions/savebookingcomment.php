<?php
$book_id = lavnn('book_id', $_REQUEST, '');
if ($book_id <> '') {
  $_REQUEST['entered_by'] = $r['userID'];
  $id = sid($module, 'InsertBookingComment', $_REQUEST);
  if ($id > 0) {
    set_cookie('flash', 'New comment added for a booking');
  }
  go("?p=$module/view&id=$book_id");
} else {
  go("?p=$module/search");
}

?>
