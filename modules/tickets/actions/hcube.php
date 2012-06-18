<?php

use ctlHypercube;

$basequery = "
  SELECT 
    t.id,
    t.status, 
    t.title,
    t.creator,
    cp.strFirstName + ' ' + cp.strLastName + ' (' + cp.strNick + ')' AS CreatorName,
    t.handler,
    hp.strFirstName + ' ' + hp.strLastName + ' (' + hp.strNick + ')' AS HandlerName,
    cp.team_id AS CreatorDepartment,
    cpd.team_name AS CreatorDepartmentName,
    cp.lngWorkplace AS CreatorOffice,
    cpo.strName AS CreatorOfficeName,
    hp.team_id AS HandlerDepartment,
    hpd.team_name AS HandlerDepartmentName,
    hp.lngWorkplace AS HandlerOffice,
    hpo.strName AS HandlerOfficeName,
    t.project,
    prj.title AS ProjectTitle
  FROM zTickets t
  JOIN tblPerson cp ON cp.lngId = t.creator
  JOIN staff_team cpd ON cpd.team_id = cp.team_id
  JOIN tblOffices cpo ON cpo.lngId = cp.lngWorkplace
  JOIN tblPerson hp ON hp.lngId = t.handler
  JOIN staff_team hpd ON hpd.team_id = hp.team_id
  JOIN tblOffices hpo ON hpo.lngId = hp.lngWorkplace
  LEFT JOIN zProjects prj ON prj.id = t.project
";
$fields = array();
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 1, 'type' => 'str', 'valuefield' => 'status', 
  'showfield' => 'status', 'caption' => 'Status'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 1, 'type' => 'int', 'valuefield' => 'CreatorDepartment', 
  'showfield' => 'CreatorDepartmentName', 'caption' => 'Creator Department'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 1, 'type' => 'int', 'valuefield' => 'CreatorOffice', 
  'showfield' => 'CreatorOfficeName', 'caption' => 'Creator Office'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 1, 'type' => 'int', 'valuefield' => 'HandlerDepartment', 
  'showfield' => 'HandlerDepartmentName', 'caption' => 'Handler Department'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 1, 'type' => 'int', 'valuefield' => 'HandlerOffice', 
  'showfield' => 'HandlerOfficeName', 'caption' => 'Handler Office'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 2, 'type' => 'int', 'valuefield' => 'creator', 
  'prerequisites' => 'CreatorDepartment,CreatorOffice',
  'showfield' => 'CreatorName', 'caption' => 'Creator Name'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 2, 'type' => 'int', 'valuefield' => 'handler', 
  'prerequisites' => 'HandlerDepartment,HandlerOffice',
  'showfield' => 'HandlerName', 'caption' => 'Handler Name'
)); 
push @fields, hash2ref((
  'scope' => 'dimension',
  'level' => 2, 'type' => 'int', 'valuefield' => 'project', 
  'prerequisites' => 'Handler',
  'showfield' => 'ProjectTitle', 'caption' => 'Project'
)); 
push @fields, hash2ref((
  'scope' => 'listcolumn',
  'level' => 0, 'type' => 'str', 'valuefield' => 'title', 
  'showfield' => 'title', 'caption' => 'Title',
  'template' => '<a href="?p='.$module.'/viewticket&id=<:v:id>"><:v:title></a>'
)); 


$hcube = new ctlHypercube($r, $basequery, $fields);
$cubeParams = (
  'module' => $module,
  'template_list' => $runtime->gettmod($module, 'hcube.list'),        # obsolete at the moment
  'template_item' => $runtime->gettmod($module, 'hcube.list.item'),   # obsolete at the moment
);
$pageParams = ('cube' => $hcube->render($cubeParams));
$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'hcube.title');
$page->add('main', $runtime->doTemplate($module, 'hcube', $pageParams);

$page['css'] = $runtime->doTemplate($module, 'hcube.css');

?>
