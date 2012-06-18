<?php
$comment = lavnn('comment');
$id = lavnn('id');
if ($id <> '' && $comment <> '') {
  $runtime->srun($module, 'SetFolderComment', $_REQUEST);
}
print $comment;
?>
