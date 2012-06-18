<?php

$id = lavnn('id');
$tableInfo = $runtime->s2r($module, 'GetEditableTableInfo', $_REQUEST);
$tablename = $tableInfo['table_name'];
if ($tablename <> '') {
  $id_field_name = lavnn($tablename . '_id_field');
  if ($id_field_name <> '') {
    $id_field_value = '';
    $columns = $runtime->s2a($module, 'GetTableColumns', array(
      'name' => $tablename, 
      'include_dates' => $tableInfo['include_dates'],
      'include_numbers' => $tableInfo['include_numbers'],
    ));
    $dirty = $_REQUEST{$tablename.'_dirty']; 
    foreach $i (@dirty) {
      if ($i <> '') {
        $fields = array();
        foreach $column (@columns) {
          $column_name = $column['name'];
          if ($column_name == $id_field_name) {
            $id_field_value = lavnn($tablename . '_' . $id_field_name . '_' . $i);
          } else {
            if (exists $_REQUEST{$column_name . '_' . $i}) {
              $column_value = $_REQUEST{$column_name . '_' . $i} || '';
              push @fields, "$column_name = " . typed($column_value, $column);
            }
          }
        }
        if (count($fields) > 0 && $id_field_value <> '') {
          $query = "UPDATE $tablename SET " . join(', ', @fields) . " WHERE $id_field_name = $id_field_value";
          $rows_affected = $r['db']run_query($query);
        }
      }
    }
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
  } elseif ($usertype == 231) { #varchar
    return "dbo.Utf8toUni('$value')" || "''";
  } else {
    return 'NULL'; # abnormal situation, but produces valid SQL 
  }
}

?>
