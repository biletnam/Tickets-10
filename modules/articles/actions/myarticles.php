<?php

$pageParams = array();

# Get pagelet that renders articles
$sqlParams = array(
  'id' => $r['userID'], 
  'office' => $r['userInfo']['lngWorkPlace'].'',
  'department' => $r['userInfo']['team_id'].'',
  'sourcetype' => "editarticle", 
  #'uniqueblock' => 'yes',
);

# Render page from all calculated parts
$departments = $runtime->getDictArr('main', 'departments');
$pageParams['departments'] = $departments;


# Find only published articles
$sqlParams['draft'] = 0; $sqlParams['deleted'] = 0; 
$pageParams['published'] = arr2ref($objA->search_articles(%sqlParams));
# Find only draft articles
$sqlParams['draft'] = 1; $sqlParams['deleted'] = 0;
$pageParams['drafts'] = arr2ref($objA->search_articles(%sqlParams));
# Find only deleted articles (both ex-published abd ex-draft)
$sqlParams['deleted'] = 1; 
$pageParams['deleted'] = arr2ref($objA->search_articles(%sqlParams));

# Render the tab control
use ctlTab;
$tcArticles = new ctlTab($r, 'tcArticles');
$tcArticles->addTab('published', dot('myarticles.tab.published', $articleInfo), dot('myarticles.published', $pageParams));
$tcArticles->addTab('drafts', dot('myarticles.tab.drafts', $articleInfo), dot('myarticles.drafts', $pageParams));
$tcArticles->addTab('deleted', dot('myarticles.tab.deleted', $articleInfo), dot('myarticles.deleted', $pageParams));
$pageParams['tabcontrol'] = $tcArticles->getHTML();     

$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.myarticles');
$page->add('main', $runtime->txt->do_template($module, 'myarticles', $pageParams);

$page['js'] .= $runtime->txt->do_template('main', 'tabcontrol.js');
$page->add('css',  $runtime->txt->do_template('main', 'tabcontrol.css');
      


?>
