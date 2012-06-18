<?php

delete_cookie('sessionID_client', '');
delete_cookie('lastpage', '');
go2('client.pl', '?p='.$_CONFIG['DEFAULT_CLIENT_ACTION']);

?>
