<?php

$domain = lavnn('domain', $_REQUEST, 0);
$nextUrl = lavnn('nextUrl', $_REQUEST, 0);
delete $_REQUEST['domain'];
delete $_REQUEST['nextUrl'];
delete $_REQUEST['f'];

use objEmployee;
$objEmp = new objEmployee($r);

$warnings = array();
foreach $p (keys %_REQUEST) {
  ($action, $id) = split('_', $p);
  if ($action == 'employee') {
    $employee = $_REQUEST{$p};
    if ($id <> '') {
      # Check if this employee already have e-mail, and create a forward in case if he does.
      $employeeInfo = $objEmp->get_employee_info(('id' => $employee));
      if (count($employeeInfo) > 0) {
        $objEmp->link_emp_email(('email' => $id, 'employee' => $employee));
      }
    }
  } elseif ($action == 'fwd') {
    $forward_to = $_REQUEST{$p};
    if ($forward_to <> '') {
      $runtime->db->sqlrun($module, 'SetForwarder', array('id' => $id, 'forward_to' => $forward_to));
    }
  } 
}
$_SESSION['flash'] = join('', @warnings)) if count($warnings) > 0;
if ($domain > 0) {
  $domainInfo = $runtime->s2r($module, 'GetDomainDetails', array('id' => $domain));
  if (count($domainInfo) == 0) {
    set_cookie('error', 'Invalid domain name given, adding failed');
  } else {
    go("?p=$module/search&domain=$domain");
  }
} else {
  set_cookie('error', 'No domain name was provided, adding failed');
}
go("?p=$module/search");
?>
