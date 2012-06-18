<?php

$pageParams['folders'] = arr2ref(s2a($module, 'ListTicketFolders', array('user_id' => $r['userID'])));
$pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.folders');
$page->add('title',  $pageParams['pagetitle']);
$page->add('main', $runtime->txt->do_template($module, 'folders', $pageParams));


?>
