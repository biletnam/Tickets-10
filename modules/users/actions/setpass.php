<?php

$old = lavnn('old', $_REQUEST, '');
$new = lavnn('new', $_REQUEST, '');
$new2 = lavnn('new2', $_REQUEST, '');

if ($old == '') {
  set_cookie('error', 'Please provide your old password!');
} elseif ($new == '') {
  set_cookie('error', 'You cannot set an empty password!');
} elseif ($new <> $new2) {
  set_cookie('error', 'Password and confirmation should match!');
} elseif ($r['userInfo']['psswrd'] <> $old) {
  set_cookie('error', 'Provided old password is not correct!');
} else {
  # Everything's allright, yes, everything fine. And we want you to sleep well tonight. Let the world turn without you tonight.
  $r['userInfo']['psswrd'] = $new;
  srun('users', 'SetNewPassword', $r['userInfo']);
  set_cookie('flash', 'New password is set');
  go('?p='.$_CONFIG['DEFAULT_ACTION']);
}

# If control flow reaches this point, it means some errors were encountered. Form should be shown again. 
go('?p=users/passwd');

?>
