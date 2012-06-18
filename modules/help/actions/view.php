<?php

$pageParams  = array();
$lang = lavnn('lang') || 'en';
$code = lavnn('code') || 'index';
$title = lavnn('title') || 'Help Index';
$page->add('title',  $pageParams['pagetitle'] = $title;
$page['main']  = $r['txt']doText($runtime->rf($module, "pages/$lang/header.html"), $pageParams);
$page->add('main',  $r['txt']doText($runtime->rf($module, "pages/$lang/$code.html"), $pageParams);


  
?>
