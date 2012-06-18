<?php

$messages = $objW->do_workflow(%_REQUEST);

set_cookie('flash', 'workflows/execute has been worked.');
go('?' . lavnn('returnUrl'));

?>
