<?php

$pageParams = array();
$forms = $runtime->s2a($module, 'ListEmployeeForms');
$pageParams['forms'] = $forms;
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.empforms', $pageParams);
$page->add('main', $runtime->txt->do_template($module, 'empforms', $pageParams);

      
?>
