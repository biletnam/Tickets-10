<?php
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $bookingFamilyInfo = $runtime->s2r($module, 'GetFamilyMemberInfo', array('id' => $id));
  if (count($bookingFamilyInfo) > 0) {
    $book_id = $bookingFamilyInfo['book_id'] || '';
    if ($book_id <> '') {
      $runtime->db->sqlrun($module, 'DeleteBookingGuest', array('id' => $id));
      go("?p=$module/view&id=$book_id&tab=people");
    }
  }
}
go("?p=$module/search");

?>
