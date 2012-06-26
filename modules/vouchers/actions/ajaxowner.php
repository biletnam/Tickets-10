<?php
$type = lavnn('type', $_REQUEST, '');
if ($type == 'owner') {
  $owneroptions = genOptions(arr2ref(s2a($module, 'ListOwners')), 'id', 'name'); 
  print $runtime->txt->do_template($module, 'ajaxowner', array('owneroptions' => $owneroptions)); 
} elseif ($type == 'client') {
  print $runtime->txt->do_template($module, 'ajaxowner.client'); 
} elseif ($type == 'employee') {
  print $runtime->txt->do_template($module, 'ajaxowner.employee'); 
} 

1;
?>
