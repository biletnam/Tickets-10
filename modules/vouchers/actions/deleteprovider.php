<?php
srun($module, 'DeleteVoucherProvider', $_REQUEST);
go("?p=$module/home&tab=providers");

?>
