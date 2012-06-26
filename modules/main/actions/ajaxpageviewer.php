<?php

$_REQUEST['chars'] = 2 if (lavnn('chars') == '');
print $runtime->txt->do_template($module, 'ajaxpageviewer', $_REQUEST);


?>
