<?php

if (lavnn('id') <> '') {
  $runtime->db->sqlrun($module, 'UpdateParonnageDetails', $_REQUEST);
} else {
  $_SESSION['error'] = 'No record found to be updated');
}

go("?p=$module/paronnage&report=ParonageReport");
?>
