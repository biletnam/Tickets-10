<?php
$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.new');
$page->add('main', $runtime->doTemplate($module, 'new', $pageParams);



?>
