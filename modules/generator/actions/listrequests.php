<?php

$gen_user_id = $runtime->get_cookie('gen_user_id', $_REQUEST, 0);
#$gen_user_id = 38;

$pageParams = array( 
  'gen_id' => $gen_user_id,
  'baseurl' => 'http://' . $ENV['SERVER_NAME'] . $r['config']['BASEURL_SCRIPTS'],
);

if ($gen_user_id > 0) {
  $genUserInfo = $runtime->s2r($module, 'GetGeneratorUserInfo', array('id' => $gen_user_id));
  $sqlParams['generator_id'] = $genUserInfo['generator_id'];
  $pageParams['hotels'] = arr2ref(s2a($module, 'ListBookingRequests', $sqlParams)); 
}
$page['baseurl'] = $pageParams['baseurl'];
$page->add('main', $runtime->txt->do_template($module, 'listrequests', $pageParams);
$page['css'] = $runtime->txt->do_template($module, 'listrequests.css', $pageParams);
print dotmod($module, 'index', $page);

?>
