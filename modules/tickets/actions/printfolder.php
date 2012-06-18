<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  use objTicketing;
  $objT = new objTicketing($r);
  $folderInfo = $runtime->s2r($module, 'GetFolderInfo', $_REQUEST); 
  $page->add('title',  $folderInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.printfolder', $folderInfo);
  $page->add('main',  $objT->print_folder(%folderInfo);
}






?>
