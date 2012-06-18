<?php

$pageParams = array();

$gen_user_id = $runtime->get_cookie('gen_user_id', $_REQUEST, 0);
$id = lavnn('id', $_REQUEST, 0);

if ($gen_user_id > 0 && $id > 0) {
  $genUserInfo = $runtime->s2r($module, 'GetGeneratorUserInfo', array('id' => $gen_user_id)); 
  $generator_id = $genUserInfo['generator_id'];
  $genInfo = $runtime->s2r($module, 'GetGeneratorInfo', array('generator_id' => $generator_id)); 
  $sqlParams = (
    'contract_numbers' => $id,
    'generator_id' => $generator_id,
  );
  $contractInfo = $runtime->s2a($module, 'GetSalesReport', $sqlParams);
  $firstentry = %{$contractInfo[0]};
  $entries = array();
  foreach $c (@contractInfo) {
    $c['paymentrows'] = arr2ref(s2a($module, 'ListContractPayments', array('id' => $id)));
    $c['giftrows'] = arr2ref(s2a($module, 'ListContractGifts', array('id' => $id)));
    push @entries, dot('contract.row', ${$c});
  }
  $firstentry['rows'] = join('', @entries);

  if ($genInfo['show_fu_comments'] == 1) {
    $fucomments = $runtime->s2a($module, 'ListFollowUpComments', $sqlParams);
    $firstentry['fucomments'] = $runtime->doTemplate($module, 'contract.emptyrow').loopt('contract.fucomment', @fucomments);
  }

  # register pageview
  srun('main', 'RegisterPageview', array('entity_type' => 'contract', 'entity_id' => $id, 'viewer_type' => 'G', 'viewer_id' => $gen_user_id));

  $page->add('main', $runtime->doTemplate($module, 'contract', $firstentry);
}

print dotmod($module, 'index', $page);
?>