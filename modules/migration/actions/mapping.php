<?php

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.mapping');

$tablename = $pageParams['table_name'] = (lavnn('table_name') || '');
$idfieldname = $pageParams['id_field_name'] = (lavnn('id_field_name') || '');
$valuefieldname = $pageParams['value_field_name'] = (lavnn('value_field_name') || '');

$pageParams['form'] = $runtime->doTemplate($module, 'mapping.form', $pageParams);
if ($tablename <> '' && $idfieldname <> '') {
  if ($valuefieldname <> '') {
    $mappings = $runtime->s2a($module, 'SelectValues', $pageParams);
    $pageParams['mappings'] = $mappings;
    $pageParams['table'] = $runtime->doTemplate($module, 'mapping.table', $pageParams);
  } else {
    $columns = $runtime->s2a($module, 'GetTableColumns', array('table_name' => $tablename));
    $pageParams['columns'] = arr2ref(genOptions($columns, 'COLUMN_NAME', 'COLUMN_NAME'));
    $pageParams['form'] = $runtime->doTemplate($module, 'mapping.form.prefilled', $pageParams);
  } 
} else {
  $pageParams['table'] = $runtime->doTemplate($module, 'mapping.none', $pageParams);
}

$shortcuts = $runtime->s2a($module, 'Shortcuts', $pageParams);
$pageParams['shortcuts'] = $shortcuts;
$pageParams['shortcutstable'] = $runtime->doTemplate($module, 'mapping.shortcuts', $pageParams);


$page->add('main', $runtime->doTemplate($module, 'mapping', $pageParams);




?>
