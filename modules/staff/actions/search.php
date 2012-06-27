<?php

# Render staff list with inline search form
$page['js'] .= $r->txt->do_template($module, 'search.js');
$page['js'] .= $r->txt->do_template($module, 'search.sort.js');
$page->add('title',  $_REQUEST['pagetitle'] = $r->txt->do_template($module, 'title.search');
$page->add('main', $r->txt->do_template($module, 'search', $_REQUEST);



?>
