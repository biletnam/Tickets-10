<?php
# Render page from all calculated parts
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.home');
$page->add('main',  $r->txt->do_template($module, 'home', $pageParams);


?>
