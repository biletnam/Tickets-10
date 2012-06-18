<?php

$id = lavnn('id');

$groupInfo = $runtime->s2r($module, 'GetEntityGroupInfo', $_REQUEST);
$entities = $runtime->s2a($module, 'ListGroupEntities', $groupInfo);
$pageParams['entities'] = $entities;

$page->add('title',  $groupInfo['pagetitle'] = $runtime->doTemplate($module, 'title.view', $groupInfo);
$page->add('main', $runtime->doTemplate($module, 'view', $groupInfo);




?>
