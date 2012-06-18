<?php

$upload_dir = $runtime['config']['DIRUPLOAD'];
$filename = lavnn('filename', $_REQUEST, '');
if ($filename <> '') {
  $filepath = "$upload_dir/$filename";
  if (-e $filepath) {
    ($id, $name) = split('\.', $filename, 2);
#    srun($module, 'DeleteAttachmentRelatedArticles', array('id' => $id));
#    srun($module, 'DeleteAttachmentRelatedTickets', array('id' => $id));
    unlink($filepath);
    set_cookie('flash', 'File deleted');
  }
} 

go('?p=inarticlestranet/listuploads');

?>
