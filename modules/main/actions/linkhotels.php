<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  print dot('linkhotels', $pageParams);
}

1;

?>
