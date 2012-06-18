<?php

$runtime->delete_cookie('gen_user_id');
$url = $_REQUEST['goto'];
if ($url == '') {
  go("?p=$module/promptlogin");
} else {
  go($url);
}

?>
