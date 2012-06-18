<?php
$type = lavnn('type', $_REQUEST, '');
if ($type == 'owner') {
  $owneroptions = genOptions(arr2ref(s2a($module, 'ListOwners')), 'id', 'name'); 
  print dot('ajaxowner', array('owneroptions' => $owneroptions)); 
} elseif ($type == 'client') {
  print dot('ajaxowner.client'); 
} elseif ($type == 'employee') {
  print dot('ajaxowner.employee'); 
} 

1;
?>
