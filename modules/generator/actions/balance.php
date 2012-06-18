<?php

$gen_user_id = $runtime->get_cookie('gen_user_id', $_REQUEST, 0);

$pageParams = array( 
  'gen_id' => $gen_user_id,
  'baseurl' => 'http://' . $ENV['SERVER_NAME'] . $r['config']['BASEURL_SCRIPTS'],
);

#$r['db']['db']{odbc_old_unicode} = 1;
$lang = 'en';

if ($gen_user_id > 0) {
  $genUserInfo = $runtime->s2r($module, 'GetGeneratorUserInfo', array('id' => $gen_user_id));
  $generator_id = $genUserInfo['generator_id'];
  $sqlParams = array(
    'lang' => $lang, 
    'generator_id' => $generator_id,
    'date_from' => (lavnn('date_from') || ''),
    'date_to' => (lavnn('date_to') || ''),
    'contract_number' => (lavnn('contract_number') || ''),
    'category' => (lavnn('category') || ''),
  );
  $date_from = $pageParams['date_from'] = $sqlParams['date_from'];
  $date_to = $pageParams['date_to'] = $sqlParams['date_to'];
  $contract = $pageParams['contract_number'] = $sqlParams['contract_number'];
  $category = $pageParams['category'] = $sqlParams['category'];
  $generatorInfo = $runtime->s2r($module, 'GetGeneratorInfo', $sqlParams);
  $pageParams['generatorinfo'] = $generatorInfo;
  $categories = $runtime->s2a($module, 'ListCategories', $sqlParams);
  $categoryoptions = genOptions($categories, 'lngId', 'strName', $category);
#  $pageParams['categories'] = $categoryoptions; # This does not work correctly with encodings, so it was commented out completely

  if ($date_from <> '') {
    # Get balances of all currencies before date_from:
    #print spreview($module, 'ListExpenseCurrBalances', $sqlParams);
    $balances = $runtime->s2a($module, 'ListExpenseCurrBalances', $sqlParams);
    $b = Arrays::array2hash($balances, 'currency', 'amount');
    
    # Take a look what currencies were used
    $currencies = $runtime->s2a($module, 'ListExpenseCurrencies', $sqlParams);
    if (count($currencies) > 0) {
      #Prepare templates for showing expenses
      $pageParams['currencies'] = $currencies;
      $amountsTemplate = gett('expenses.listitem.amounts');  #TODO
      $rowTemplate = gett('expenses.listitem');
      foreach $curr (@currencies) {
        $currabbr = $curr['currency'];
        $currAmount = $amountsTemplate;
        $currAmount =~ s/==CURR==/$currabbr/g;
        push @cc, $currAmount;
        # also, update balances with new currencies
        if (! exists $b{$curr['currency']}) {
          $b{$currabbr} = 0;
        }
      } 
      $amountsTemplate = join('', @cc);

      # Print out the list of expenses
      $expenses = $runtime->s2a($module, 'ListExpenses', $sqlParams);
      if (count($expenses) > 0) {
        $ee = array();
        foreach $e (@expenses) {
          $currabbr = $e['currency'];
          $curramount = $e['amount'];
          $e['in_'.$currabbr} = $curramount if ($curramount > 0);
          $e['out_'.$currabbr} = -$curramount if ($curramount < 0);
          $b{$currabbr} += $curramount;
          $e['balance_'.$currabbr} = -$b{$currabbr};
          $e['amounts'] = $r['txt']doText($amountsTemplate, $e);
          push @ee, $r['txt']doText($rowTemplate, $e);
        }
        $pageParams['expenses'] = join('', @ee);
        $pageParams['expenselist'] = $runtime->txt->do_template($module, 'expenses.list', $pageParams);
      }
      
      # Check if there are more expenses for the same generator but on other dates
      $otherexpenses = $runtime->s2r($module, 'CountOtherExpenses', $sqlParams);
      if ($otherexpenses['cnt'] > 0) {
        $pageParams['otherexpenses'] = $runtime->txt->do_template($module, 'expenses.other', $otherexpenses);
      }
      
    } else {
      # INTERCOMPANY stuff 
      $interc = $runtime->s2a($module, 'Intercompany', $sqlParams);
      if (count($interc) > 0) {
        $pageParams['interc'] = $interc;
        $pageParams['intercompany'] = $runtime->txt->do_template($module, 'intercompany', $pageParams);
      }
    }

  } else {
    $page['flash'] .= dotmod('main', 'error', array('error' => dot('error.balance.datemissing')));
  }

  # register pageview
  srun('main', 'RegisterPageview', array('entity_type' => 'balance', 'entity_id' => 0, 'viewer_type' => 'G', 'viewer_id' => $gen_user_id));

}

$page->add('main', $runtime->txt->do_template($module, 'balance', $pageParams);
$page['baseurl'] = $pageParams['baseurl'];
print dotmod($module, 'index', $page);

?>
