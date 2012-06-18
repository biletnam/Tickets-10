<?php

$pageParams = array();
$forms = $runtime->s2a($module, 'ListEmployeeForms');
$pageParams['forms'] = $forms;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.empforms', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'empforms', $pageParams);

      
?>
