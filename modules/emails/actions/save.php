<?php

$domain = lavnn('domain', $_REQUEST, 0);
$nextUrl = lavnn('nextUrl', $_REQUEST, 0);
unset($_REQUEST['domain']);
unset($_REQUEST['nextUrl']);
unset($_REQUEST['f']);

use objEmployee;
$objEmp = new objEmployee($r);

$warnings = array();
foreach ($_REQUEST as $p => $pvalue) {
  list($action, $id) = split('_', $p, 2);
  if ($action == 'employee') {
    if ($id != '') {
      # Check if this employee already have e-mail, and create a forward in case if he does.
      $employeeInfo = $objEmp->get_employee_info(('id' => $pvalue));
      if (count($employeeInfo) > 0) {
        $objEmp->link_emp_email(('email' => $id, 'employee' => $pvalue));
      }
    }
  } elseif ($action == 'fwd') {
    if ($pvalue <> '') {
      $runtime->db->sqlrun($module, 'SetForwarder', array('id' => $id, 'forward_to' => $pvalue));
    }
  } 
}
if (count($warnings) > 0) {
  $_SESSION['flash'] = join('', $warnings));
}
if ($domain > 0) {
  $domainInfo = $runtime->s2r($module, 'GetDomainDetails', array('id' => $domain));
  if (count($domainInfo) == 0) {
    $_SESSION['error'] = 'Invalid domain name given, adding failed');
  } else {
    go("?p=$module/search&domain=$domain");
  }
} else {
  $_SESSION['error'] = 'No domain name was provided, adding failed');
}
go("?p=$module/search");
?>
