<?php

$employees = $runtime->s2a($module, 'ListEmployeeEmails');
$allowed_domains = Arrays::array2hash(arr2ref(s2a($module, 'ListAllDomainNames')), 'domain_name', 'id');
$actual_domains = array();
foreach $e (@employees) {
  $email = $e['strLocalOfficeEmail'];
  ($username, $domain) = split('@', $email, 2);
  $username = lc(trim($username));
  $domain = lc(trim($domain));
  $actual_domains{$domain} = arr2ref(()) if !exists $actual_domains{$domain};
  $user = array('id' => $e['lngId'], 'username' => $username, 'email' => $email);
  push $actual_domains{$domain}, $user;
}
print "<pre>";
#print Dumper($allowed_domains);
#print Dumper($domains);
while (($domainname, $usernames) = each %actual_domains) {
  if (!exists $allowed_domains{$domainname}) {
    # Report invalid domain
    $cnt = scalar($usernames);
    print "<strong>Invalid domain found: '$domainname' ($cnt users)</strong><br />";
  } else {
    print "<strong>'$domainname'</strong><br />";
    # Add addresses one by one
    $failed = array(); $success = 0;
    foreach $user ($usernames) {
      #print Dumper($user);
      $newid = sid($module, 'InsertEmailAddress', array(
#      print spreview($module, 'InsertEmailAddress', array(
        'username' => $user['username'], 'domain' => $allowed_domains{$domainname}, 'email' => $user['email'], 'employee' => $user['id'] 
      ));
      if ($newid == 0) {
        push @failed, $user;
      } else {
        $success++;
      }
    }
    print "Succeeded: $success<br>";
    print "Faled to add:<br>" . Dumper($failed) if count($failed) > 0;
  }
}

1;

?>
