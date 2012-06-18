<?php

# only saves profile changes for currently logged user

$_REQUEST['id'] = $userInfo['staff_id'];
srun('profile', 'SaveMyProfile', $_REQUEST);

go('?p=profile/myprofile');
?>
