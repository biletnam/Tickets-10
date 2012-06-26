<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) {
  $result = $runtime->db->sqlrun($module, 'UpdateCampaign', $_REQUEST);
} else {
  $id = $runtime->sid($module, 'InsertCampaign', $_REQUEST);
  if ($id == 0) {
    $_SESSION['error'] = 'Could not insert campaign');  
  } else {
    $_SESSION['flash'] = 'Campaign saved');  
  }
}

if ($id > 0) {
  go("?p=$module/editcampaign&id=$id");
} else {
  go("?p=$module/campaigns");
}

?>
