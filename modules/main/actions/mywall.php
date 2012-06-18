<?php

use objWall;
$objWall = new objWall($r);

$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'title.mywall');
$posts = $objWall->get_posts(('entity_type' => 'employee', 'entity_id' => $r['userID'], 'can_edit' => '1')); #print Dumper($posts);
$pageParams['wall'] = $objWall->render($posts);
$page->add('main', $runtime->doTemplate('main', 'mywall', $pageParams);
$runtime->saveMoment(' rendered main part of the page');



# register pageview
srun('main', 'RegisterPageview', array('entity_type' => 'wall.employee', 'entity_id' => $r['userID'], 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
