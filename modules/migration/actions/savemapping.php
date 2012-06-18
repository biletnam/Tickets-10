<?php

$tablename = lavnn('table_name');
$idfieldname = lavnn('id_field_name');
$valuefieldname = lavnn('value_field_name');

$ids = lavnn('ids[]');
foreach $id_field_value (@ids) {
  $dirty = lavnn("dirty_$id_field_value");
  if ($dirty <> '') {
    $id = lavnn("id_$id_field_value");
    $merlinvalue = ''.lavnn("merlin_value_$id_field_value");
    $sqlParams = array(
      'id' => $id, 
      'id_field_value' => $id_field_value, 
      'merlin_value' => $merlinvalue, 
      'table_name' => $tablename, 
      'id_field_name' => lavnn('id_field_name')
    );
    if ($id <> '' && $merlinvalue <> '') { #update mapping
      srun($module, 'UpdateMapping', $sqlParams);
    } else { # insert mapping
      srun($module, 'InsertMapping', $sqlParams);
    }
  }
}
go("?p=$module/mapping&table_name=$tablename&id_field_name=$idfieldname&value_field_name=$valuefieldname");

?>
