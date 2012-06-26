<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $serieInfo = $runtime->s2r($module, 'GetVoucherSerieData', $_REQUEST);
  if (count($serieInfo) > 0) {
    print $runtime->txt->do_template($module, 'ajaxserie', $serieInfo);  
  }
} else {
  print "where ID, dude?";
}

1;
 
?>
