<?php

$pageParams = array();
$forms = $runtime->s2a($module, 'ListEmployeeForms');
$pageParams['forms'] = $forms;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.empforms', $pageParams);
$page->add('main', $r->txt->do_template($module, 'empforms', $pageParams);

      
?>
