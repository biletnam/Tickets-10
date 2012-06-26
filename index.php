<?php

error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL || E_PARSE);

require 'config.php';
require $config['folders']['sitecode'].'dbconfig.php';

require $config['folders']['include'].'CRuntime.php';
require $config['folders']['include'].'CDatabase.php';
require $config['folders']['include'].'CTextProcessor.php';

require $config['folders']['include'].'Arrays.php';

$r = new CRuntime($config);
$r->checkMaintenance();
$txt = $r->txt = new CTextProcessor($r);
$db = $r->db = new CDatabase($r);
session_start(); 
$r->route($_REQUEST);

?>