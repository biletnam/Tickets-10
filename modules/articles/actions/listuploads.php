<?php

$upload_dir = $r['config']['DIRUPLOAD']; 
$files = $fs->dirlist($upload_dir);
$morevalues = array('can_delete' => $acc['superadmin']);
$files = Arrays::list2array($files, 'filename', $morevalues);
$pageParams = array();
$pageParams['files'] = $files;
$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.listuploads');
$page->add('main', $runtime->txt->do_template($module, 'uploadedfiles.list', $pageParams);



?>
