<?php
$comment = lavnn('comment');
$id = lavnn('id');
if ($id <> '' && $comment <> '') {
  $runtime->($module, 'SetFolderComment', $_REQUEST);
}
print $comment;
?>
