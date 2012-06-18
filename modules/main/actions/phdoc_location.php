<?php

$phdoc = lavnn('id', $_REQUEST, 0);
if ($phdoc == 0) {
  $history = $runtime->s2a($module, 'ListPhDocHistory', $_REQUEST);
  die 'TODO main/phpdoc_location ' . Dumper(@history);
}

?>
