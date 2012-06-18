<?php
$name = lavnn('name');
$id = lavnn('id');
if ($id <> '' && $name <> '') {
  $runtime->srun($module, 'SetFolderName', $_REQUEST);
}
print $name;
?>
