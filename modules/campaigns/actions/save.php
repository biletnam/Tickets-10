<?php
use objTicketing;
$objT = new objTicketing($r);

# Check if any client was given
$clients = $runtime->trim(lavnn('clients'), $_REQUEST, '');
$contracts = $runtime->trim(lavnn('contracts'), $_REQUEST, '');
$title = lavnn('title') || 'New Marketing Campaign';
$summary = lavnn('summary');
$duedate = lavnn('duedate');
if ($clients.$contracts == '' ) {
  set_cookie('error', 'Please specify some clients or contracts to start marketing campaign!');
} else {

  # Create a project
  $sqlParams = (
    'user_id' => $r['userInfo']['staff_id'],
    'moderator' => (lavnn('moderator') || $r['userInfo']['staff_id']),
    'title' => $title,
  );
  $project_id = $objT->create_project(%sqlParams);
  if ($project_id > 0) {
  
    $success = 0; $failure = 0;
    # Create all the tickets - by clients
    $ids = split(/\n/, $clients);
    foreach $id (@ids) {
      $client_id = $runtime->trim($id);
      if ($client_id <> '') {
        $clientInfo = $runtime->s2r($module, 'GetClientInfo', array('id' => $client_id));
        if (count($clientInfo) > 0) {
          $tickettitle = $runtime->doTemplate($module, 'ticket.title.client', $clientInfo); 
          %sqlParams = (
            'title' => $tickettitle,
            'contents' => $summary,
            'destination' => 'project',
            'project' => $project_id, 
            'creator' => $r['userInfo']['staff_id'],
            'duedate' => $duedate,
            'target' => "client:$client_id",
          );  
          $objT->create_ticket(%sqlParams);
          $success++;
        } else {
          $failure++;
        } 
      }
    }
    # Create all the tickets - by contracts
    $ids = split(/\n/, $contracts);
    foreach $id (@ids) {
      $contract_id = $runtime->trim($id);
      if ($contract_id <> '') {
        $contractInfo = $runtime->s2r($module, 'GetContractInfo', array('id' => $contract_id));
        if (count($contractInfo) > 0) {
          $tickettitle = $runtime->doTemplate($module, 'ticket.title.contract', $contractInfo); 
          %sqlParams = (
            'title' => $tickettitle,
            'contents' => $summary,
            'destination' => 'project',
            'project' => $project_id, 
            'creator' => $r['userInfo']['staff_id'],
            'duedate' => $duedate,
            'target' => "script=comments&show=&contract_number=$contract_id",
          );  
          $objT->create_ticket(%sqlParams);
          $success++;
        } else {
          $failure++;
        } 
      }
    }

    #set_cookie('flash', dot('flash.save.success', array('title' => $title, 'success' => $success, 'failure' => $failure) ));
    go("?p=tickets/project&id=$project_id");
  } else {
    set_cookie('error', 'Could not create a project!');
  }

}
go("?p=$module/new");
?>
