<?php
$page['nextURL'] = lavnn('url');
$page->add('title',  $page['pagetitle'] = $r->txt->do_template($module, 'title.login', $page);
$page->add('main', $r->txt->do_template($module, 'login', $page);

#use CMenu;
#$menu = new CMenu($r);
#$menu->level1_from_descriptor('main/level1anonymousmenu', 'unauthorized');
#$menu->level2_from_descriptor('main/level2anonymousmenu', 'login');



?>
