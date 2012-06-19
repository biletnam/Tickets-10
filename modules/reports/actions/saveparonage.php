<?php

if (lavnn('id') <> '') {
  $runtime->db->sqlrun($module, 'UpdateParonnageDetails', $_REQUEST);
} else {
  set_cookie('error', 'No record found to be updated');
}

go("?p=$module/paronnage&report=ParonageReport");
?>
