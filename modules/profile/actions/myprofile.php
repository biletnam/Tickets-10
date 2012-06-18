<?php

$profile = $runtime->s2r('profile', 'GetUserProfile', array('id' => $userInfo['staff_id']));

$page->add('main',  doTemplate('profile', 'myprofile', $profile);

print doTemplate('main', 'index', $page);
?>
