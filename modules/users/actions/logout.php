<?php

delete_cookie('sessionID', '');
delete_cookie('lastpage', '');
go('?p='.$_CONFIG['DEFAULT_ACTION']);
?>
