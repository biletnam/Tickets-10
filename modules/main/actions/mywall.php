<?php

use objWall;
$objWall = new objWall($r);

$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.mywall');
$posts = $objWall->get_posts(('entity_type' => 'employee', 'entity_id' => $r['userID'], 'can_edit' => '1')); #print Dumper($posts);
$pageParams['wall'] = $objWall->render($posts);
$page->add('main', $runtime->txt->do_template('main', 'mywall', $pageParams);
$runtime->saveMoment(' rendered main part of the page');



# register pageview
$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'wall.employee', 'entity_id' => $r['userID'], 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
