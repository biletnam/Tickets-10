<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = ('src' => $source, 'controlname' => $controlname);
  print dot('linkpeople', $pageParams);
}

1;

?>
