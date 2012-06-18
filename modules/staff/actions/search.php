<?php

# Render staff list with inline search form
$page['js'] .= $runtime->txt->do_template($module, 'search.js');
$page['js'] .= $runtime->txt->do_template($module, 'search.sort.js');
$page->add('title',  $_REQUEST['pagetitle'] = $runtime->txt->do_template($module, 'title.search');
$page->add('main', $runtime->txt->do_template($module, 'search', $_REQUEST);



?>
