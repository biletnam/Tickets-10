<?php

$employees = $runtime->s2a($module, 'ListEmployeeEmails');
$allowed_domains = Arrays::array2hash(s2a($module, 'ListAllDomainNames'), 'domain_name', 'id');
$actual_domains = array();
foreach ($employees as $e) {
  $email = $e['strLocalOfficeEmail'];
  list($username, $domain) = split('@', $email, 2);
  $username = lc(trim($username));
  $domain = lc(trim($domain));
  $actual_domains[$domain][] = array('id' => $e['lngId'], 'username' => $username, 'email' => $email);
}
print "<pre>";
#print Dumper($allowed_domains);
#print Dumper($domains);
foreach ($actual_domains as $domainname => $usernames) {
  if (!exists $allowed_domains[$domainname]) {
    # Report invalid domain
    $cnt = scalar($usernames);
    print "<strong>Invalid domain found: '$domainname' ($cnt users)</strong><br />";
  } else {
    print "<strong>'$domainname'</strong><br />";
    # Add addresses one by one
    $failed = array(); $success = 0;
    foreach ($usernames as $user) {
      #print Dumper($user);
      $newid = sid($module, 'InsertEmailAddress', array(
#      print spreview($module, 'InsertEmailAddress', array(
        'username' => $user['username'], 'domain' => $allowed_domains{$domainname}, 'email' => $user['email'], 'employee' => $user['id'] 
      ));
      if ($newid == 0) {
        $failed[] = $user;
      } else {
        $success++;
      }
    }
    print "Succeeded: $success<br>";
    print (count($failed) > 0) ? "Faled to add:<br>" . print_r($failed, true) : '';
  }
}

1;

?>
