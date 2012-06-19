<?php

$field = lavnn('field');
$userData = array();
if ($field == 'username') {
  %userData = $runtime->s2r($module, 'LookupByUsername', array('username' => lavnn('value')));
} elseif($field == 'email') {
  %userData = $runtime->s2r($module, 'LookupByEmail', array('email' => lavnn('value')));
} elseif($field == 'fullname') {
  %userData = $runtime->s2r($module, 'LookupByFullName', array('fullname' => lavnn('value')));
}

# Show error or send the password
if (count($userData) == 0) {
  set_cookie('error', 'User not found');
} elseif($userData['strLocalOfficeEmail'] == '') {
  set_cookie('error', 'Your user does not have e-mail defined. Please contact your Line Manager.');
} else {
  mail($userData['strLocalOfficeEmail'], '', 'Your Password', 'This is a reminder of your password: <b>'.$userData['psswrd'].'</b>');
  $_SESSION['flash'] = 'Password for '.$userData['username'].' has been sent to their e-mail address');
}

$nextUrl = lavnn('url') || '?p=main/dashboard';
go($nextUrl);

?>
