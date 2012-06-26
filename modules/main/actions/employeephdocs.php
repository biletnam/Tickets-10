<?php

$pageParams = array('employee' => $r['userID']);

$phdocs = $runtime->s2a($module, 'ListIncomingUserDocs', $pageParams);  
$pageParams['phdocs'] = $phdocs; 

# Check if we stock should be shown to this user
$stock = $runtime->s2a($module, 'ListUserStock', array('employee' => $r['userID']));
if (count($stock) > 0) {
  $pageParams['stock'] = $runtime->txt->do_template($module, 'dashboard.phdocs.stock', array('stock' => $stock)); 
}

# Get all personal documents, regardless of their delivery status (do not show UsedVouchers!)
$personaldocs = $runtime->s2a($module, 'ListPersonalUserDocs', array('employee' => $r['userID']));  
$pageParams['personaldocs'] = $personaldocs; 

print $runtime->txt->do_template($module, 'dashboard.phdocs', $pageParams);

?>
