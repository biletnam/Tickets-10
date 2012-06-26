<?php

$pageParams  = array();
$lang = lavnn('lang', $_REQUEST, 'en');
$code = lavnn('code', $_REQUEST, 'index');
$title = $pageParams['pagetitle'] = lavnn('title', $_REQUEST, 'Help Index');
$page->add('title', $title);
$page->add('main', $r->txt->do_text($runtime->txt->get_module_file($module, "pages/$lang/header.html"), $pageParams);
$page->add('main', $r->txt->do_text($runtime->txt->get_module_file($module, "pages/$lang/$code.html"), $pageParams);
  
?>