<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $phDocInfo = $runtime->s2r($module, 'MarkPhDocDelivered', array('id' => $id));
  if (count($phDocInfo) > 0) {
    set_cookie('flash', 'You marked the document as received!'); 
  } 
}

go("?p=$module/dashboard&tab=docs");


?>
