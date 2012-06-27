<?php
$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.new');
$page->add('main', $r->txt->do_template($module, 'new', $pageParams);



?>
