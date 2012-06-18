<?php
%pageParams = array();

$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'title.vacationbalance');
$page->add('main', $runtime->doTemplate('main', 'vacationbalance', $pageParams);
$runtime->saveMoment(' rendered main part of the page');



# register pageview
srun('main', 'RegisterPageview', array('entity_type' => 'vacationbalance', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
