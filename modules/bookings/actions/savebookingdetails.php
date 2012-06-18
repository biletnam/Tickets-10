<?php
$book_id = lavnn('book_id', $_REQUEST, '');
if ($book_id <> '') {
  $_REQUEST['entered_by'] = $r['userID'];
  #formdebug(%_REQUEST); die spreview($module, 'UpdateBookingDetails', $_REQUEST);
  srun($module, 'UpdateBookingDetails', $_REQUEST);
  set_cookie('flash', 'Booking details updated');
  go("?p=$module/view&id=$book_id");
} else {
  go("?p=$module/search");
}

?>
