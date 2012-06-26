<?php

$pageParams = array();

$page->add('title', $runtime->txt->do_template('main', 'home.pagetitle', $pageParams));
$page->add_cssfile('ctlTab');
$page->add_jsfile('ctlTab');

require $runtime->config['folders']['include'].'ctlTab.php';
$tcHome = new ctlTab($runtime, 'villageinfo');
$tcHome->addTab('details', $runtime->txt->do_template('main', 'home.tab.notifications', $pageParams), $runtime->txt->do_template('main', 'home.wait.notifications', $pageParams));
$tcHome->addTab('tickets', $runtime->txt->do_template('main', 'home.tab.tickets', $pageParams), $runtime->txt->do_template('main', 'home.wait.tickets', $pageParams));
$pageParams['tabcontrol'] = $tcHome->render('details');

$page->add('main', 'hello world! I am main!');

?>