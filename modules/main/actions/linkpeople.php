<?php

$source = lavnn('src', $_REQUEST, '');
$controlname = lavnn('controlname', $_REQUEST, '');
if ($source != '' && $controlname != '') {
    $pageParams = array('src' => $source, 'controlname' => $controlname);
    print $r->txt->do_template($module, 'linkpeople', $pageParams);
}

?>
