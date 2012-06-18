<?php

# Render staff list with inline search form
$page['js'] .= $runtime->doTemplate($module, 'search.js');
$page['js'] .= $runtime->doTemplate($module, 'search.sort.js');
$page->add('title',  $_REQUEST['pagetitle'] = $runtime->doTemplate($module, 'title.search');
$page->add('main', $runtime->doTemplate($module, 'search', $_REQUEST);



?>
