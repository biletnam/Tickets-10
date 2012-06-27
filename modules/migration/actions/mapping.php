<?php

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.mapping');

$tablename = $pageParams['table_name'] = array(lavnn('table_name') || '');
$idfieldname = $pageParams['id_field_name'] = array(lavnn('id_field_name') || '');
$valuefieldname = $pageParams['value_field_name'] = array(lavnn('value_field_name') || '');

$pageParams['form'] = $r->txt->do_template($module, 'mapping.form', $pageParams);
if ($tablename <> '' && $idfieldname <> '') {
  if ($valuefieldname <> '') {
    $mappings = $runtime->s2a($module, 'SelectValues', $pageParams);
    $pageParams['mappings'] = $mappings;
    $pageParams['table'] = $r->txt->do_template($module, 'mapping.table', $pageParams);
  } else {
    $columns = $runtime->s2a($module, 'GetTableColumns', array('table_name' => $tablename));
    $pageParams['columns'] = arr2ref(genOptions($columns, 'COLUMN_NAME', 'COLUMN_NAME'));
    $pageParams['form'] = $r->txt->do_template($module, 'mapping.form.prefilled', $pageParams);
  } 
} else {
  $pageParams['table'] = $r->txt->do_template($module, 'mapping.none', $pageParams);
}

$shortcuts = $runtime->s2a($module, 'Shortcuts', $pageParams);
$pageParams['shortcuts'] = $shortcuts;
$pageParams['shortcutstable'] = $r->txt->do_template($module, 'mapping.shortcuts', $pageParams);


$page->add('main', $r->txt->do_template($module, 'mapping', $pageParams);




?>
