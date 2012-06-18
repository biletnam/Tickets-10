<?php

$id = lavnn('id', $_REQUEST, '');
$resortInfo = array();

if ($id <> '') {
  %resortInfo = $runtime->s2r('resorts', 'GetResortDetails', $_REQUEST);
  $resortInfo['pagetitle'] = doTemplate('resorts', 'resort.title.edit', $resortInfo);
} else {
  $resortInfo['pagetitle'] = doTemplate('resorts', 'resort.title.new');
}

# Get list of resort types
$resorttypeoptions = getDictArr('main', 'yesno', $resortInfo['Resort_Type']);
$resortInfo['resorttypeoptions'] = $resorttypeoptions;

# Get list of resort types
$resortusageoptions = getDictArr('main', 'yesno', $resortInfo['In_Use']);
$resortInfo['resortusageoptions'] = $resortusageoptions;

#print Dumper($resortInfo);

$page['navigation'] = doTemplate('resorts', 'navigation');
$page->add('main',  doTemplate('resorts', 'edit', $resortInfo);

print doTemplate('main', 'index', $page);

?>
