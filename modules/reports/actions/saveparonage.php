<?php

if (lavnn('id') <> '') {
  srun($module, 'UpdateParonnageDetails', $_REQUEST);
} else {
  set_cookie('error', 'No record found to be updated');
}

go("?p=$module/paronnage&report=ParonageReport");
?>
