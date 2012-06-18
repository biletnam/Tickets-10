<?php

$book_id = lavnn('book_id', $_REQUEST, 0);
if ($book_id > 0) {
  $_REQUEST['user_id'] = $r['userID'];
  $_REQUEST['ids'] = join_request_array('invoice_ids');
#  formdebug($_REQUEST); die(spreview($module, 'IssueInvoices', $_REQUEST));
  srun($module, 'IssueInvoices', $_REQUEST);
  go("?p=$module/view&id=$book_id&tab=payments");
} else {
  go("?p=$module/search");
}

?>
