<?php

$pageParams['folders'] = arr2ref(s2a($module, 'ListTicketFolders', array('user_id' => $r['userID'])));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.folders');
$page->add('main', $runtime->doTemplate($module, 'folders', $pageParams);


?>
