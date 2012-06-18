<?php
# Render page from all calculated parts
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.home');
$page->add('main',  $runtime->doTemplate($module, 'home', $pageParams);


?>
