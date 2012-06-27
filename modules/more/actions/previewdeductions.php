<?php
$pageParams = $_REQUEST;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.previewdeductions');
$amounts = lavnn('amounts');
$months = $runtime->getDict('main', 'month');
$month = lavnn('month'); $year = lavnn('year');
$month = trim($months{$month}) || lavnn('month');

$deductions = array(); $refs = array();
foreach $line (split('\n', $amounts)) {
  $line = trim($line);
  if ($line <> '') {
    $parts = split('\t', $line);
    ($phone, $ref_no, $amount, $trash) = @parts;
    $row = array(
      'phone' => $phone,
      'ref_no' => $ref_no,
      'amount' => $amount,
      'comment' => "Mobile bill $month $year ($phone)",
    );
    push @refs, $ref_no;
    push @deductions, $row;
  }
}
if (count($refs) > 0) {
  $references = $runtime->s2a($module, 'GetEmployeesByRefs', array('refs' => "'".join("','", @refs)."'"));
  $mixed = Arrays::mixin_arrays($deductions, $references, 'ref_no');
  $pageParams['deductions'] = $deductions;
  $pageParams['refs'] = join(',', @refs);
}
$page->add('main', $r->txt->do_template($module, 'previewdeductions', $pageParams);



?>
