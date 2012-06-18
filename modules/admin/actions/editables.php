<?php
$pageParams  = array();
$pageParams['editables'] = arr2ref(s2a($module, 'ListEditableTables')); 
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'editables.title');
$page->add('main',  $acc->can_access_menu('admin-setup-tables') ? dot('editables', $pageParams) : dot('noaccess', $pageParams);


  
?>
