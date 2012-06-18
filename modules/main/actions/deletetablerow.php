<?php

$tableid = lavnn('tableid');
if ($tableid > 0) {
  $tableInfo = $runtime->s2r($module, 'GetEditableTableInfo', array('id' => $tableid));
  if (count($tableInfo) > 0) {
    $idfield = lavnn('idfield');
    $idvalue = lavnn('idvalue');
    $tablename = $tableInfo['table_name'] || '';
    if ($tablename <> '' && $idfield <> '' && $idvalue <> '') {
      $query = "delete from $tablename where $idfield = $idvalue";
      $rows_affected = $r['db']run_query($query);
      set_cookie('flash', 'Success!') if $rows_affected > 0;
      go("?p=$module/anytable&id=$tableid");
    }
  }
}
go("?p=$module/anytable");
?>
