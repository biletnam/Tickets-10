<?php
use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.paronnage');
$report = lavnn('report') || 'ParonageReport';
if ($report <> '' && $runtime->eqmod($module, $report)) {
  if ($report == 'ParonageClients') {

    $basequery = spreview($module, 'ParonageClients', $_REQUEST); 
    $grid1 = new ctlDataGrid($r, 'paronageclients', $basequery, $module);
    $descriptor = $runtime->rf($module, 'sql/ParonageClients.columns.txt');
    $columns = $grid1->parse_columns_descriptor($descriptor);
    $grid1->set_columns(@columns);
    $grid1->set_pager(50);
    $grid1->set_width('100%');
    $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'ParonageClients.listitem'));
    $grid1->set_custom_template('superheaders' => $runtime->gettmod($module, 'ParonageClients.superheaders'));
    $pageParams['results'] = $grid1->render();
    $runtime->saveMoment('Finished rendering data grid');

    if (lavnn('sender') <> '') {
      $clientInfo = $runtime->s2r($module, 'GetClientInfo', array('id' => lavnn('sender')));
      $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.paronnage.client', $clientInfo);
    }

  } elseif ($report == 'ParonageReport') {

    $basequery = spreview($module, 'ParonageReport', $_REQUEST); 
    $grid1 = new ctlDataGrid($r, 'paronageclients', $basequery, $module);
    $descriptor = $runtime->rf($module, 'sql/ParonageClients.columns.txt');
    $columns = $grid1->parse_columns_descriptor($descriptor);
    $grid1->set_columns(@columns);
#    $grid1->set_pager(50);
    $grid1->set_width('100%');
    $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'ParonageReport.listitem'));
    $grid1->set_custom_template('superheaders' => $runtime->gettmod($module, 'ParonageReport.superheaders'));
    $pageParams['results'] = $runtime->doTemplate($module, 'ParonageReport', array('datagrid' => $grid1->render()));
    $runtime->saveMoment('Finished rendering data grid');
  
  }
}
$pageParams['reportname'] = $report;
$page->add('main', $runtime->doTemplate($module, 'paronnage', $pageParams);




?>