<?php

# $_REQUEST expected to have elements 'type' and 'q'

%_MODULE = array(
  'name' => 'main'
);
$runtime->set_module($moduleODULE);
$module = $moduleODULE['name'];

$people = array();
$domain = lavnn('type', $_REQUEST, '');
if ($domain == 'C') {
  ($lastname, $firstname, $contractno) = split(',', lavnn('q'));
  $searchParams = array('last_name' => $lastname, 'first_name' => $firstname, 'contract_no' => $contractno);
  @people = $runtime->s2a($module, 'ListClientsJSON', $searchParams);
} elseif ($domain == 'G') {
  @people = $runtime->s2a($module, 'ListGenUsersJSON', $_REQUEST);
} elseif ($domain == 'U') {
  @people = $runtime->s2a($module, 'ListStaffJSON', $_REQUEST);
} elseif ($domain == 'AU') {
  @people = $runtime->s2a($module, 'ListAllStaffJSON', $_REQUEST);
}
 
print Arrays::a2jsonstr($people);

?>
