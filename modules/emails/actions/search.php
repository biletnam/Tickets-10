<?php
$domain = lavnn('domain', $_REQUEST, 0);
$searchInfo = $_REQUEST;
$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$resultsHtml = '';
$domains = $runtime->s2a($module, 'ListAllDomainNames'); 
$searchInfo['domainoptions'] = arr2ref(genOptions($domains, 'id', 'name_with_stats', $domain));
$offices = $runtime->s2a('staff', 'ListOffices');
$searchInfo['officeoptions'] = arr2ref(genOptions($offices, 'lngId', 'strName', $_REQUEST['office']));
$departments = $runtime->s2a('staff', 'ListDepartments');
$searchInfo['departmentoptions'] = arr2ref(genOptions($departments, 'team_id', 'team_name', $_REQUEST['department']));
$scope = $_REQUEST['scope'] = lavnn('scope', $_REQUEST, 3);
$scopeoptions = $runtime->getDictArr($module, 'scope', $_REQUEST['scope']); 
$searchInfo['scopeoptions'] = $scopeoptions;

use ctlDataGrid;

if ($domain > 0) {
  $basequery = $runtime->spreview($module, 'SearchEmailAddresses', $_REQUEST); #print $basequery;
  $grid1 = new ctlDataGrid($r, 'emails', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/SearchEmailAddresses.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor); 
  $grid1->set_columns(@columns);
  #$grid1->set_pager(20);
  $grid1->set_width('100%');
  $grid1->set_custom_template('actions' => $runtime->gettmod($module, 'search.listitem.actions'));
  $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'search.listitem'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
  $resultsHtml = $grid1->render(('rowcount' => 'yes'));
    
  $searchInfo['results'] = $r->txt->do_template($module, 'search.results', array('results' => $resultsHtml));
  $searchInfo['bulkadd'] = $r->txt->do_template($module, 'search.bulkadd', $_REQUEST);
}
$page->add('main', $r->txt->do_template($module, 'search', $searchInfo);




?>
