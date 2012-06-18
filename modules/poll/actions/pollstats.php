<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', $_REQUEST);
  
  $responders = $runtime->s2a($module, 'ListPollResponders', $_REQUEST);
  $pollInfo['responders'] = $responders;
  $allviewers = $acc->list_users_for_resource('viewpoll', $id);
  $pollInfo['cnt'] = count($allviewers);
  if (count($responders) == 0) {
    $pollInfo['tabcontrol'] = $runtime->doTemplate($module, 'pollstats.notstarted', $pollInfo);
  } else {
    $pollInfo['percentage'] = count($responders) * 100.0 / $pollInfo['cnt'];
    $pending = $objP->list_pending_respondents(('poll' => $id));
    $pollInfo['pending'] = $pending;
    use ctlTab;
    $tabPollStats = new ctlTab($r, "tcPollStats");
    $tabPollStats->addTab('persons', dot('pollstats.persons.tabheader'), dot('pollstats.persons', $pollInfo)); 
    $tabPollStats->addTab('questions', dot('pollstats.questions.tabheader'), $objP->render('id' => $id, 'mode' => 'stats')); 
    $tabPollStats->addTab('pending', dot('pollstats.pending.tabheader', $pollInfo), dot('pollstats.pending', $pollInfo)) if count($pending) > 0; 
    $tabPollStats->setDefaultTab(lavnn('tab') || 'persons');
    $pollInfo['tabcontrol'] = $runtime->doTemplate($module, 'pollstats.usage', $pollInfo) . $tabPollStats->getHTML();
    $runtime->saveMoment('  tab control rendered');
  }
  
  
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page->add('title',  $pollInfo['pagetitle'] = $runtime->doTemplate($module, 'title.pollstats', $pollInfo);
  $page->add('main', $runtime->doTemplate($module, 'pollstats', $pollInfo);
} else {
  $pollInfo = array();
  $page->add('title',  $pollInfo['pagetitle'] = $runtime->doTemplate($module, 'title.pollstats.notfound', $pollInfo);
  $pollInfo['tabcontrol'] = $runtime->doTemplate($module, 'pollstats.notfound', $pollInfo);
  $page->add('main', $runtime->doTemplate($module, 'pollstats', $pollInfo);
}




?>
