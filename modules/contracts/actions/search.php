<?php

$searchInfo = $_REQUEST;

$resortcodes = $runtime->s2a($module, 'ListResorts'); 
$searchInfo['resortcodes'] = arr2ref(genOptions($resortcodes, 'Resort_Code', 'Resort_Name', $_REQUEST['resort_code']));
$contracttypes = $runtime->s2a($module, 'ListContractTypes');
$searchInfo['contracttypes'] = arr2ref(genOptions($contracttypes, 'Contract_Code', 'Contract_type', $_REQUEST['type_of_contract']));
$contractstatuses = $runtime->s2a($module, 'ListContractStatuses');  
$searchInfo['contractstatuses'] = arr2ref(genOptions($contractstatuses, 'status_id', 'status_name', $_REQUEST['status']));
$searchInfo['excludesources'] = arr2ref(genOptions($contractstatuses, 'status_id', 'status_name', $_REQUEST['exclude_contract_source']));
$offices = $runtime->s2a($module, 'ListOffices'); 
$searchInfo['officeoptions'] = arr2ref(genOptions($offices, 'Office_ID', 'Office_Name', $_REQUEST['office']));
$yesno = $runtime->getDictArr('main', 'yesno', 0);
$searchInfo['hasbookingoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $_REQUEST['has_booking_id']));
$searchInfo['hasmerlinoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $_REQUEST['has_merlin_id']));
$searchInfo['coldlineoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $_REQUEST['is_coldline']));
$datetypes = $runtime->getDictArr($module, 'datetype', 0);
$searchInfo['datetypeoptions'] = arr2ref(genOptions($datetypes, 'key', 'value', $_REQUEST['datetype']));
$currencies = $runtime->s2a($module, 'ListCurrencies'); 
$searchInfo['currencyoptions'] = arr2ref(genOptions($currencies, 'Currency_code', 'Currency_code', $_REQUEST['ccy']));
$giftcodes = $runtime->s2a($module, 'ListGiftCodes'); 
$searchInfo['giftcodeoptions'] = arr2ref(genOptions($giftcodes, 'Gift_Code', 'Description', $_REQUEST['gift_code']));

$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$resultsHtml = '';
#if ($_REQUEST['ref_no'] . $_REQUEST['book_id'] . $_REQUEST['client_name'] . $_REQUEST['generator_id'] <> '') {

  use ctlDataGrid;
  
  $basequery = $runtime->spreview($module, 'SearchContracts', $_REQUEST); print "<!-- $basequery -->";
  $grid1 = new ctlDataGrid($r, 'contracts', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/SearchContracts.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1->set_custom_template('actions' => $runtime->gettmod($module, 'search.listitem.actions'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
  $resultsHtml = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');

#}
$searchInfo['results'] = $resultsHtml;
$page->add('main', $runtime->txt->do_template($module, 'search', $searchInfo);



?>
