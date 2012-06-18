<?php

$pageParams = array();
$savedqueries = $runtime->s2a($module, 'ListSavedQueries', array('user_id' => $r['userID']));
$pageParams['savequeries'] = @savedqueries;

use ctlTab;
$tabQueries = new ctlTab($r, 'tcQueries');
$tabQueries->addTab('saved', dot('queries.tab.saved'), dot('queries.saved', $pageParams)) if count($savedqueries) > 0;
$tabQueries->addTab('build', dot('queries.tab.build'), dot('queries.build'));
$pageParams['tabcontrol'] = $tabQueries->getHTML();
  
$page['js'] = dotmod('main', 'tabcontrol.js');
$page['css'] = dotmod('main', 'tabcontrol.css');
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.queries');
$page->add('main', $runtime->doTemplate($module, 'queries', $pageParams);



?>
