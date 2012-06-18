<?php

$id = lavnn('id');

$groupInfo = $runtime->s2r($module, 'GetEntityGroupInfo', $_REQUEST);
$entities = $runtime->s2a($module, 'ListGroupEntities', $groupInfo);
$pageParams['entities'] = $entities;

$page->add('title',  $groupInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.view', $groupInfo);
$page->add('main', $runtime->txt->do_template($module, 'view', $groupInfo);




?>
