<?php

$gen_user_id = $runtime->get_cookie('gen_user_id', $_REQUEST, 0);
$gen_frameset = $runtime->get_cookie('gen_frameset', $_REQUEST, '');

$pageParams = ( 
  'gen_id' => $gen_user_id,
  'baseurl' => 'http://' . $ENV['SERVER_NAME'] . $r['config']['BASEURL_SCRIPTS'],
  'status' => (lavnn('status') || ''),
  'frameset' => $gen_frameset,
);

use Calendar;
$todayArr = Calendar::getTodayArr();

if ($gen_user_id > 0) {
  $genUserInfo = $runtime->s2r($module, 'GetGeneratorUserInfo', array('id' => $gen_user_id));
  $generator_id = $genUserInfo['generator_id'];
  $first_gen_id = lavnn('first_gen_id', $_REQUEST, '');
  $office_id = (lavnn('office_id') || '');
  $date_to = (lavnn('date_to') || $todayArr['fulldate']);
  $date_from = (lavnn('date_from') || Calendar::addDate($date_to, -30));
  $sqlParams = (
    'generator_id' => $generator_id,
    'first_gen_id' => $first_gen_id, 
    'office_id' => $office_id,
    'date_from' => $date_from,
    'date_to' => $date_to,
    'status' => (lavnn('status') || '')
  );
  $pageParams['date_from'] = $sqlParams['date_from'];
  $pageParams['date_to'] = $sqlParams['date_to'];
  $pageParams['first_gen_id'] = $sqlParams['first_gen_id'];
  $offices = $runtime->s2a($module, 'ListContractOffices', $sqlParams);
  $officeoptions = genOptions($offices, 'office_id', 'Office_Name', $office_id);
  $pageParams['officeoptions'] = $officeoptions;
  $generators = $runtime->s2a($module, 'ListGenerators', $sqlParams);
  $has_other_generators = (count($generators) > 1);
  print "<!--".spreview($module, 'ListContracts', $sqlParams)."-->";
  $contracts = $runtime->s2a($module, 'ListContracts', $sqlParams);
  if ($has_other_generators) {
    $generatoroptions = genOptions($generators, 'generator_id', 'generator_name', $first_gen_id);
    $pageParams['generators'] = $generatoroptions; 
    $pageParams['generatorselection'] = $runtime->doTemplate($module, 'generator.selection', $pageParams);
    $contracts_by_generator = Arrays::slice_array ($contracts, 'generator_id');
    $genhtml = array();
    foreach $generator_id (keys %contracts_by_generator) {
      $cc = @{$contracts_by_generator{$generator_id}}; 
      push @genhtml, prepare_generator_contracts($cc, $pageParams);
    }
    $pageParams['contractlist'] = join('', @genhtml);
  } else {
    $pageParams['generatorselection'] = $runtime->doTemplate($module, 'generator.name', ${$generators[0]});
    $pageParams['contractlist'] = prepare_generator_contracts($contracts);
  }

  # register pageview
  srun('main', 'RegisterPageview', array('entity_type' => 'contracts', 'entity_id' => 0, 'viewer_type' => 'G', 'viewer_id' => $gen_user_id));
}

$page['baseurl'] = $pageParams['baseurl'];
$page->add('main', $runtime->doTemplate($module, 'contracts', $pageParams);
$page['js'] = $runtime->doTemplate($module, 'contracts.js'); 
print dotmod($module, 'index', $page);





function prepare_generator_contracts {
  ($_contracts, $pageParams)

  $contracts = @{$_contracts};
  $html = ''; $rows = array();
  $firstentry = %{$contracts[0]};
#  print Dumper($firstentry);
  foreach $c (@contracts) {
    $c['gen_user_id'] = $pageParams['gen_id'];
    if ($pageParams['frameset'] <> '') {
      $c['contracturl'] = "?p=$module/contract";
      $c['contracttarget'] = ' target="_blank" ';
      $c['balanceurl'] = $pageParams['frameset'].'?p=main/balance&gen_user_id='.$pageParams['gen_id'].'&gen_frameset='.$runtime->urlencode($pageParams['frameset']);
      $c['balancetarget'] = ' target="_top" ';
    } else {
      $c['contracturl'] = "?p=$module/contract";
      $c['contracttarget'] = ' target="_blank" ';
      $c['balanceurl'] = "?p=$module/balance";
      $c['balancetarget'] = '';
    }
    if ($c['contract_number'] <> '') {
      push @rows, dot('contracts.listitem.normal', $$c);
    } else {
      push @rows, dot('contracts.listitem.abnormal', $$c);
    }
  }
  $firstentry['contracts'] = join('', @rows);
  $firstentry['length'] = count($rows);
  $html .= $runtime->doTemplate($module, 'contracts.list', $firstentry);
}

?>