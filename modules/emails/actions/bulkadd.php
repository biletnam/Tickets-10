<?php

$domain = lavnn('domain', $_REQUEST, 0);
if ($domain > 0) {
  $domainInfo = $runtime->s2r($module, 'GetDomainDetails', array('id' => $domain)); 
  if (count($domainInfo) == 0) {
    $_SESSION['error'] = 'Invalid domain name given, adding failed';
  } else {
    $domainname = $domainInfo['domain_name']; 
    $usernames = trim(lavnn('usernames'));
    $emails = lavnn('emails');
    $success = ''; $failures = array();
    # Try usernames first
    if ($usernames <> '') {
      foreach (explode("\n", $usernames) as $username) {
        $result = $runtime->sid($module, 'InsertEmailAddress', array(
          'domain' => $domain, 
          'username' => $username, 
          'email' => trim($username).'@'.$domainname,
          'reason' => lavnn('reason', $_REQUEST, '');
        ));
        if ($result > 0) {
          $success++;
        } else {
          $failues[] = $username;
        }
      }
    }
    # Then do the same for full emails; that's a bit trickier
    if ($emails <> '') {
      foreach (explode("\n", $emails) as $email) {
        list($u, $d) = split('@', $email, 2);
        if ($d != $domainname) {
          $failures[] = $email;
        } else {
          $result = $runtime->sid($module, 'InsertEmailAddress', array(
            'domain' => $domain, 
            'username' => $u, 
            'email' => $email,
            'reason' => lavnn('reason')
          ));
          if ($result > 0) {
            $success++;
          } else {
            $failures [] = $username;
          }
        }
      }
    }
    if (count($failures) > 0) {
      $firstuser = array_shift($failures); 
      $others = count($failures);
      $msg = (count($failures) > 0 ? " and $others more usernames/emails " : '') . ' failed to be added';
      $msg .= ($success > 0) ? " (and $success usernames/emails were added successfully)" : '';
      $_SESSION['error'] = $firstuser.$msg;
    } else {
      $_SESSION['flash'] = "All $success users are added successfully") if $success > 0;
    }
    go("?p=$module/search&domain=$domain");
  }
} else {
  $_SESSION['error'] = 'No domain name was provided, adding failed';
}
go("?p=$module/search");

?>
