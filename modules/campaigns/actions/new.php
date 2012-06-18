<?php
$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.new');
$page->add('main', $runtime->txt->do_template($module, 'new', $pageParams);



?>
