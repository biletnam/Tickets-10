<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  print $r->txt->do_template($module, 'linkhotels', $pageParams);
}

1;

?>
