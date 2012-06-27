<?php

$pageParams = array();
$savedqueries = $runtime->s2a($module, 'ListSavedQueries', array('user_id' => $r['userID']));
$pageParams['savequeries'] = @savedqueries;

use ctlTab;
$tabQueries = new ctlTab($r, 'tcQueries');
$tabQueries->addTab('saved', dot('queries.tab.saved'), dot('queries.saved', $pageParams)) if count($savedqueries) > 0;
$tabQueries->addTab('build', dot('queries.tab.build'), dot('queries.build'));
$pageParams['tabcontrol'] = $tabQueries->getHTML();
  
$page['js'] = $r->txt->do_template('main', 'tabcontrol.js');
$page['css'] = $r->txt->do_template('main', 'tabcontrol.css');
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.queries');
$page->add('main', $r->txt->do_template($module, 'queries', $pageParams);



?>
