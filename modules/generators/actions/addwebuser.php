<?php

$id = lavnn('generator_id', $_REQUEST, 0);
if ($id > 0) {
  $username = lavnn('username', $_REQUEST, '');
  if ($username == '' || lavnn('password') == '') {
    set_cookie('error', 'Both username and password should be defined');
  } else {
    # check if username is vacant
    $users = $runtime->s2a($module, 'CheckUserName', array('username' => $username));
    if (count($users) > 0) {
      set_cookie('error', "Username $username is already taken");
    } else {
      # This does not work on PROD until gen_users has identity on user_id field
      #  We cannot add identity while royalemarketing.com is working
      $id = sid($module, 'InsertGeneratorUser', $_REQUEST); 
      if ($id > 0) {
        $_SESSION['flash'] = 'Web access for new user is defined');
      } else {
        set_cookie('error', 'Failed to add another web user for generator');
      }
    }
  }
  go("?p=$module/view&id=$id&tab=webaccess");
} else {
  go("?p=$module/search");
}
?>
