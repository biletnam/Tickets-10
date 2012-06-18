<?php

$source = lavnn('source', $_REQUEST, '');
$value = lavnn('value', $_REQUEST, '');

if ($source == 'ticket') {
  $ticketInfo = $runtime->s2r('tickets', 'GetTicketInfo', array('id' => $value));
  if (count($ticketInfo) > 0) {
    go("?p=tickets/viewticket&id=$value");
  } else {
    go("?p=tickets/search&id=$value");
  }
} elseif ($source == 'tstring') {
  go("?p=tickets/search&id=&titletext=".$runtime->urlencode($value));
} elseif ($source == 'article') {
  $articleInfo = $runtime->s2r('articles', 'GetArticleData', array('id' => $value));
  if (count($articleInfo) > 0) {
    go("?p=articles/route&id=$value");
  } else {
    go("?p=articles/search");
  }
}


?>
