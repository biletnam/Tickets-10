<?php

$pageParams = array();
$resorts = $runtime->s2a('resorts', 'ListResorts');
$pageParams['resorts'] = $resorts;

$page['navigation'] = doTemplate('resorts', 'navigation');
$page->add('main',  doTemplate('resorts', 'list', $pageParams);

print doTemplate('main', 'index', $page);
?>
