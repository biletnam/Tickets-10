<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) {
  $result = srun($module, 'UpdateCampaign', $_REQUEST);
} else {
  $id = sid($module, 'InsertCampaign', $_REQUEST);
  if ($id == 0) {
    set_cookie('error', 'Could not insert campaign');  
  } else {
    set_cookie('flash', 'Campaign saved');  
  }
}

if ($id > 0) {
  go("?p=$module/editcampaign&id=$id");
} else {
  go("?p=$module/campaigns");
}

?>
