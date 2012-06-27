<?php

$searchInfo = $_REQUEST; 

$nationalities = $runtime->s2a($module, 'ListNationalities'); 
$searchInfo['nationalityoptions'] = arr2ref(genOptions($nationalities, 'nationality_id', 'nationality_name', lavnn('nationality')));
$countries = $runtime->s2a($module, 'ListCountries'); 
$searchInfo['countryoptions'] = arr2ref(genOptions($countries, 'country_id', 'country_name', lavnn('country')));
$searchInfo['contractoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', lavnn('has_contracts'))); 
$searchInfo['commentoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', lavnn('has_comments'))); 
$searchInfo['attachmentoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', lavnn('has_attachments'))); 

$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$paramstr = $r->txt->do_template($module, 'search.checkparams', $searchInfo);
$resultsHtml = '';
if ($paramstr <> '') {

  use ctlDataGrid;
  
  $basequery = $runtime->spreview($module, 'SearchClients', $_REQUEST); 
  $grid1 = new ctlDataGrid($r, 'clients', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/SearchClients.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1->set_custom_template('actions' => $runtime->gettmod($module, 'search.listitem.actions'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
  $resultsHtml = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');

}
$searchInfo['results'] = $resultsHtml;
$page->add('main', $r->txt->do_template($module, 'search', $searchInfo);



?>
