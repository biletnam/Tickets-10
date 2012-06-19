<?php
$refs = lavnn('ref');
foreach $ref (@refs) {
  $comment = lavnn("comment_$ref");
  $name = lavnn("name_$ref");
  $amount = lavnn("amount_$ref");
  $sqlParams = array(
    'editor' => $r['userID'],
    'ref_no' => $ref,
    'amount' => $amount,
    'comment' => $comment,
  );
  $runtime->db->sqlrun($module, 'InsertMobileBillDeduction', $sqlParams);
}
go("?p=$module/lastdeductions");
?>
