<?php

$pageParams = array();

$page->add('title', $r->txt->do_template('main', 'home.pagetitle', $pageParams));
$page->add_cssfile('ctlTab');
$page->add_jsfile('ctlTab');

require $r->config['folders']['include'] . 'ctlTab.php';
$tcHome = new ctlTab($r, 'dashboard');
$tcHome->addTab(
        'details', 
        $r->txt->do_template('main', 'home.tab.notifications', $pageParams), 
        $r->txt->do_template('main', 'home.wait.notifications', $pageParams)
);
$tcHome->addTab(
        'tickets', 
        $r->txt->do_template('main', 'home.tab.tickets', $pageParams), 
        $r->txt->do_template('main', 'home.wait.tickets', $pageParams)
);
$pageParams['tabcontrol'] = $tcHome->render('details');
$page->add('main', $r->txt->do_template('main', 'dashboard', $pageParams));
?>