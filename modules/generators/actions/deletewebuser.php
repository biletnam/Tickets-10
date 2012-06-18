<?php
$id = lavnn('id', $_REQUEST, 0);
$gen_id = 0;
if ($id > 0) {
  $userInfo = $runtime->s2r($module, 'GetGenUserData', array('id' => $id));
  if (count($userInfo) > 0) {
    srun($module, 'DeleteGeneratorUser', $_REQUEST);
  }
  $gen_id = $userInfo['generator_id'];
}
if ($gen_id > 0) {
  go("?p=$module/view&id=$gen_id&tab=webaccess");
} else {
  go("?p=$module/search");
}

?>
