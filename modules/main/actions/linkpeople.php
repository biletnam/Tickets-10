<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  print $runtime->txt->do_template($module, 'linkpeople', $pageParams);
}

1;

?>
