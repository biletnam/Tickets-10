<?php

$upload_dir = $runtime['config']['DIRUPLOAD'];
$filename = lavnn('filename', $_REQUEST, '');
if ($filename <> '') {
  $filepath = "$upload_dir/$filename";
  if (-e $filepath) {
    ($id, $name) = split('\.', $filename, 2);
#    $runtime->db->sqlrun($module, 'DeleteAttachmentRelatedArticles', array('id' => $id));
#    $runtime->db->sqlrun($module, 'DeleteAttachmentRelatedTickets', array('id' => $id));
    unlink($filepath);
    $_SESSION['flash'] = 'File deleted');
  }
} 

go('?p=inarticlestranet/listuploads');

?>
