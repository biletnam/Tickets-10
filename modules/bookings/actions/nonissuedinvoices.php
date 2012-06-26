<?php
$invoices2issue = $runtime->s2a($module, 'newissue.ListInvoices.bypayer', $_REQUEST);
#print Dumper($invoices2issue);
$_REQUEST['invoices2issue'] = arr2ref(genCheckboxes($invoices2issue, 'invoice_ids', 'id', 'comment'));
print $runtime->txt->do_template($module, 'nonissuedinvoices', $_REQUEST);
?>
