<?php
# Render page from all calculated parts
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.home');
$page->add('main',  $runtime->txt->do_template($module, 'home', $pageParams);


?>
