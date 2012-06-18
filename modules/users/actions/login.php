<?php
$page['nextURL'] = lavnn('url');
$page->add('title',  $page['pagetitle'] = $runtime->doTemplate($module, 'title.login', $page);
$page->add('main', $runtime->doTemplate($module, 'login', $page);

#use CMenu;
#$menu = new CMenu($r);
#$menu->level1_from_descriptor('main/level1anonymousmenu', 'unauthorized');
#$menu->level2_from_descriptor('main/level2anonymousmenu', 'login');



?>
