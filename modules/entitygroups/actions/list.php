<?php

$entitygroups = $runtime->s2a($module, 'ListEntityGroups'); 
$pageParams['entitygroups'] = $entitygroups; 
#hash2ref(Arrays::slice_array($entitygroups, 'id', 'entity_type'));

$existingtypes = $runtime->s2a($module, 'ListExistingEntityGroups');
$pageParams['existingtypes'] = arr2ref(genOptions($existingtypes, 'id', 'name'));

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.list');
$page->add('main', $runtime->doTemplate($module, 'list', $pageParams);



  
?>
