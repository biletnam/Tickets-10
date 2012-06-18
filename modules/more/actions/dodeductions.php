<?php
$refs = lavnn('ref');
foreach $ref (@refs) {
  $comment = lavnn("comment_$ref");
  $name = lavnn("name_$ref");
  $amount = lavnn("amount_$ref");
  $sqlParams = (
    'editor' => $r['userID'],
    'ref_no' => $ref,
    'amount' => $amount,
    'comment' => $comment,
  );
  srun($module, 'InsertMobileBillDeduction', $sqlParams);
}
go("?p=$module/lastdeductions");
?>
