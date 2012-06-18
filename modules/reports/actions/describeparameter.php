<?php

$id = lavnn('type', $_REQUEST, 0);
$type = lavnn('type');
$paramInfo = array(); 
if ($id > 0) {
  %paramInfo = $runtime->s2r($module, 'GetParameterData', $_REQUEST);
  $type = $paramInfo['type'];
} else {
  $paramInfo['id'] = 'new';
  $paramInfo['type'] = $type;
}

if ($type == 'number') {
#  $paramInfo['input'] = $runtime->txt->do_template($module, 'describeparameter.input.number', $paramInfo);
} elseif ($type == 'string') {
#  $paramInfo['input'] = $runtime->txt->do_template($module, 'describeparameter.input.string', $paramInfo);
} elseif ($type == 'dblookup') {
  $paramInfo['input'] = $runtime->txt->do_template($module, 'describeparameter.input.dblookup', $paramInfo);
  print dot('describeparameter', $paramInfo);
} elseif ($type == 'employee') {
#  $paramInfo['input'] = $runtime->txt->do_template($module, 'describeparameter.input.employee', $paramInfo);
}

1;

 
?>
