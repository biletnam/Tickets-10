<?php
%pageParams = array();

$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.vacationbalance');
$page->add('main', $runtime->txt->do_template('main', 'vacationbalance', $pageParams);
$runtime->saveMoment(' rendered main part of the page');



# register pageview
$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'vacationbalance', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
