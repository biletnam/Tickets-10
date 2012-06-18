<?php

$pageParams  = array();

$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.home');

use ctlTab;
$tabVouchers = new ctlTab($r, 'ctVouchers');
$pageParams['series'] = arr2ref(s2a($module, 'ListSeries'));
$tabVouchers->addTab('series', dot('home.tab.series'), dot('home.series', $pageParams)); 
$pageParams['providers'] = arr2ref(s2a($module, 'ListProviders'));
$tabVouchers->addTab('providers', dot('home.tab.providers'), dot('home.providers', $pageParams));
$pageParams['owners'] = arr2ref(s2a($module, 'ListOwners'));
$tabVouchers->addTab('owners', dot('home.tab.owners'), dot('home.owners', $pageParams));
$pageParams['stock'] = arr2ref(s2a($module, 'ListStock'));
$tabVouchers->addTab('stock', dot('home.tab.stock'), dot('home.stock', $pageParams));

$currencyoptions = genOptions(arr2ref(s2a($module, 'ListCurrencies')), 'currency_name', 'currency_name');
$pageParams['currencyoptions'] = $currencyoptions;
$locationoptions = genOptions(arr2ref(s2a($module, 'ListLocations')), 'id', 'location_name');
$pageParams['locationoptions'] = $locationoptions;
$tabVouchers->addTab('newprovider', dot('home.tab.newprovider'), dot('home.newprovider', $pageParams), 'right');
$tabVouchers->addTab('newserie', dot('home.tab.newserie'), dot('home.newserie', $pageParams), 'right');
$tabVouchers->setDefaultTab(lavnn('tab') || 'series');
$pageParams['tabcontrol'] = $tabVouchers->getHTML();

$page['js'] = dotmod('main', 'tabcontrol.js');
$page['css'] = dotmod('main', 'tabcontrol.css');    

$page->add('main', $runtime->txt->do_template($module, 'home', $pageParams);



  
      
?>
