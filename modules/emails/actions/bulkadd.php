<?php

$domain = lavnn('domain', $_REQUEST, 0);
if ($domain > 0) {
  $domainInfo = $runtime->s2r($module, 'GetDomainDetails', array('id' => $domain)); 
  if (count($domainInfo) == 0) {
    set_cookie('error', 'Invalid domain name given, adding failed');
  } else {
    $domainname = $domainInfo['domain_name']; 
    $usernames = $runtime->trim(lavnn('usernames'));
    $emails = lavnn('emails');
    $success = ''; $failures = array();
    # Try usernames first
    if ($usernames <> '') {
      foreach $username (split(/\n/, $usernames)) {
        $result = sid($module, 'InsertEmailAddress', array(
          'domain' => $domain, 
          'username' => $username, 
          'email' => $runtime->trim($username).'@'.$domainname,
          'reason' => lavnn('reason')
        ));
        if ($result > 0) {
          $success++;
        } else {
          push @failues, $username;
        }
      }
    }
    # Then do the same for full emails; that's a bit trickier
    if ($emails <> '') {
      foreach $email (split(/\n/, $emails)) {
        ($u, $d) = split('@', $email, 2);
        if ($d <> $domainname) {
          push @failures, $email;
        } else {
          $result = sid($module, 'InsertEmailAddress', array(
            'domain' => $domain, 
            'username' => $u, 
            'email' => $email,
            'reason' => lavnn('reason')
          ));
          if ($result > 0) {
            $success++;
          } else {
            push @failures, $username;
          }
        }
      }
    }
    if (count($failures) > 0) {
      $firstuser = pop(@failures); 
      $others = count($failures);
      $msg = (count($failures) > 0 ? " and $others more usernames/emails " : '') . ' failed to be added';
      $msg .= ($success > 0) ? " (and $success usernames/emails were added successfully)" : '';
      set_cookie('error', $firstuser.$msg);
    } else {
      set_cookie('flash', "All $success users are added successfully") if $success > 0;
    }
    go("?p=$module/search&domain=$domain");
  }
} else {
  set_cookie('error', 'No domain name was provided, adding failed');
}
go("?p=$module/search");

?>
