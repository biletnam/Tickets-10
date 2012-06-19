<?php

$messages = $objW->do_workflow(%_REQUEST);

$_SESSION['flash'] = 'workflows/execute has been worked.');
go('?' . lavnn('returnUrl'));

?>
