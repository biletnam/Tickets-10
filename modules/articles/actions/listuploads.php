<?php

$upload_dir = $r['config']['DIRUPLOAD']; 
$files = $fs->dirlist($upload_dir);
$morevalues = ('can_delete' => $acc['superadmin']);
$files = Arrays::list2array($files, 'filename', $morevalues);
$pageParams = array();
$pageParams['files'] = $files;
$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'title.listuploads');
$page->add('main', $runtime->doTemplate($module, 'uploadedfiles.list', $pageParams);



?>
