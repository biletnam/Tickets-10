<?php
$id = lavnn('tableid');
$tableInfo = $runtime->s2r($module, 'GetEditableTableInfo', array('id' => $id));
$tablename = $tableInfo['table_name']; 
if ($tablename <> '') {
  $columns = $runtime->s2a($module, 'GetTableColumns', array(
    'name' => $tablename, 
    'include_dates' => $tableInfo['include_dates'],
    'include_numbers' => $tableInfo['include_numbers'],
  )); 
  $fieldnames = array();
  $fieldvalues = array();
  foreach $column (@columns) {
    $column_name = $column['name'];
    if (exists $_REQUEST{$column_name}) {
      $column_value = $_REQUEST{$column_name} || '';
      push @fieldnames, $column_name;
      push @fieldvalues, typed($column_value, $column);
    }
  }
  if (count($fieldnames) > 0) {
    $query = "INSERT INTO $tablename (" . join(', ', @fieldnames) . ') VALUES (' . join(', ', @fieldvalues) . ')';
    $rows_affected = $r['db']run_query($query);
    $_SESSION['flash'] = 'Success!') if $rows_affected > 0;
  }
}

go("?p=main/anytable&id=$id");

function typed {
  ($value, $column)
  
  $nullable = $column['is_nullable'];
  $usertype = $column['user_type_id'];
  
  return 'NULL' if ($value == '' && $nullable == 1);  
  if ($usertype == 56) { # int
    return $value || 0;
  } elseif ($usertype == 61) { #datetime
    $value = DateChemistry::Format(DateChemistry::CDate($value), 'mysql');
    return "'$value'" || "''";
  } elseif ($usertype == 167) { #varchar
    return "'$value'" || "''";
  } else {
    return 'NULL'; # abnormal situation, but produces valid SQL 
  }
}

1;
?>
