<?php

$id = lavnn('id', $_REQUEST, 0);
$tableInfo = array();

$page->add('title',  $tableInfo['pagetitle'] = $runtime->doTemplate($module, 'title.anytable.tablelist');
if ($id == 0) {
  $resources = $acc->list_access('editable', $r['userID']);
  $tables = array();
  if (count($resources) > 0) {
    @tables = $runtime->s2a($module, 'ListEditableTables', array('ids' => Arrays::join_column(',', 'source_id', $resources)));
    $tableInfo['tables'] = $tables;
    $page->add('main', $runtime->doTemplate($module, 'anytable.tablelist', $tableInfo);
  } else {
    $page->add('main', $runtime->doTemplate($module, 'anytable.none', $tableInfo);
  }
  
} elseif ($id  > 0) {
  # Check access - user might have no rights to edit this table!
  if ($acc->check_access("editable:$id", $r['userID']) == 0) {
    $page->add('title',  $tableInfo['pagetitle'] = $runtime->doTemplate($module, 'title.anytable.notallowed', $tableInfo);
    $page->add('main', $runtime->doTemplate($module, 'anytable.notallowed', $tableInfo);
  } else {
    # Gather metadata about table
    %tableInfo = $runtime->s2r($module, 'GetEditableTableInfo', $_REQUEST);
    $tableInfo['tablename'] = $tableInfo['name'] = $tableInfo['table_name'];  
    $columns = $runtime->s2a($module, 'GetTableColumns', $tableInfo); # print Dumper($columns);
    $data = $runtime->s2a($module, 'GetTableData', array('tablename' => $tableInfo['tablename'], 'columns' => Arrays::join_column(', ', 'name', $columns)));
    # Render editing UI
    $headers = array(); $templates = array(); $newrowform = array();
    foreach $column (@columns) {
      push @headers, dot('anytable.header.column', $$column);
      $column['table_name'] = $tableInfo['tablename'];
      $t = $column['user_type_id'];
      if ($column['is_identity'] == 1) {
        $tableInfo['id_field'] = $column['name'];
        push @templates, dot('anytable.rowtemplate.column.identity', $$column);
        push @newrowform, dot('anytable.newrowform.field.identity', $$column);
      } elseif ($t == 56 || $t == 59 || $t == 62 || $t == 106) { # int || float || real || decimal
        push @templates, dot('anytable.rowtemplate.column.int', $$column);
        push @newrowform, dot('anytable.newrowform.field.int', $$column);
      } elseif ($column['user_type_id'] == 61) {
        push @templates, dot('anytable.rowtemplate.column.datetime', $$column);
        push @newrowform, dot('anytable.newrowform.field.datetime', $$column);
      } elseif ($column['user_type_id'] == 231) { # nvarchar
        push @templates, dot('anytable.rowtemplate.column.textarea', $$column);
        push @newrowform, dot('anytable.newrowform.field.textarea', $$column);
      } else {
        push @templates, dot('anytable.rowtemplate.column', $$column);
        push @newrowform, dot('anytable.newrowform.field', $$column);
      }
    }
    $rowtemplate  = $runtime->doTemplate($module, 'anytable.rowtemplate', array('table_id' => $id, 'table_name' => $tableInfo['table_name'], 'columns' => join('', @templates))); 
    $tableInfo['header'] = $runtime->doTemplate($module, 'anytable.header', array('table_name' => $tableInfo['table_name'], 'columns' => join('', @headers)));
    $tableInfo['data'] = $txt->loopText($rowtemplate, @data);
    $tableInfo['newrowform'] = $runtime->doTemplate($module, 'anytable.newrowform', array('id' => $id, 'name' => $tableInfo['name'], 'fields' => join('', @newrowform)));
    #print Dumper($data);
    $page->add('title',  $tableInfo['pagetitle'] = $runtime->doTemplate($module, 'title.anytable.edit', $tableInfo);
    $page->add('main', $runtime->doTemplate($module, 'anytable', $tableInfo);
  }
  print dotmod('main', 'index.wide', $page);
}

1;

?>
