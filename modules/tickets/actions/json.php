<?php

$test = $runtime->s2a($module, 'ListProjects', array('adminmode' => 'yes'));
$json = a2json($test);
print $json;

?>
