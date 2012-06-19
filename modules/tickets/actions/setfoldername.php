<?php
$name = lavnn('name');
$id = lavnn('id');
if ($id <> '' && $name <> '') {
  $runtime->($module, 'SetFolderName', $_REQUEST);
}
print $name;
?>
