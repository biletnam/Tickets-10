<?php
$pageParams = %_REQUEST;
$invoices = $runtime->s2a($module, 'ListBookingInvoices', $pageParams);
$pageParams['payments'] = $invoices;
$invoiceissues = $runtime->s2a($module, 'ListBookingInvoiceIssues', $pageParams);

$payeroptions = genOptions(arr2ref(s2a($module, 'ListBookingPayers')), 'payer_id', 'payer_name');
$pageParams['payeroptions'] = $payeroptions;
$paymenttypeoptions = genOptions(arr2ref(s2a($module, 'ListPaymentTypes')), 'type_id', 'type_name');
$pageParams['paymenttypeoptions'] = $paymenttypeoptions;
$paymentplaceoptions = genOptions(arr2ref(s2a($module, 'ListPaymentPlaces')), 'place_id', 'place_name');
$pageParams['paymentplaceoptions'] = $paymentplaceoptions;
$currencyoptions = genOptions(arr2ref(s2a($module, 'ListCurrencies')), 'currency_name', 'currency_name');
$pageParams['currencyoptions'] = $currencyoptions;

if (count($invoices) == count($invoiceissues)) {
  $pageParams['newissue'] = $runtime->txt->do_template($module, 'payments.allissued');
} else {

  $invoices2issue = $runtime->s2a($module, 'newissue.ListInvoices', $pageParams);
 # print Dumper($invoices2issue);
  
  $newissue_currencyoptions = genOptions(arr2ref(s2a($module, 'newissue.ListCurrencies', $pageParams)), 'invoice_currency', 'invoice_currency');
  if (count($newissue_currencyoptions) == 1) {
    $pageParams['currency'] = $runtime->txt->do_template($module, 'payments.newissue.currency.fixed', ${$newissue_currencyoptions[0]});
  } else {
    $pageParams['currency'] = $runtime->txt->do_template($module, 'payments.newissue.currency.select', array('currencyoptions' => $newissue_currencyoptions));
  }
  
  $newissue_payeroptions = genOptions(arr2ref(s2a($module, 'newissue.ListPayers', $pageParams)), 'payer_id', 'payer_name'); 
  if (count($newissue_payeroptions) == 1) {
#    print Dumper($newissue_payeroptions[0]);
    $pageParams['payer'] = $runtime->txt->do_template($module, 'payments.newissue.payer.fixed', ${$newissue_payeroptions[0]});
    $pageParams['invoices2issue'] = arr2ref(genCheckboxes($invoices2issue, 'invoice_ids', 'id', 'comment'));
  } else {
    $pageParams['payer'] = $runtime->txt->do_template($module, 'payments.newissue.payer.select', array('book_id' => $pageParams['book_id'], 'payeroptions' => $newissue_payeroptions));
  }
  
  
  # TODO - on practice, Yury restricts all the lists - see boking_invoice.pm, function NewIssue
  $pageParams['newissue'] = $runtime->txt->do_template($module, 'payments.newissue', $pageParams);
}

if (count($invoiceissues) > 0) {
 # print 'invoice issues ' . Dumper($invoiceissues);
  $pageParams['invoiceissues'] = $runtime->txt->do_template($module, 'payments.invoiceissues', array('invoiceissues' => $invoiceissues)); 
}


$orphanexpenses = $runtime->s2a($module, 'ListOrphanExpenses', $pageParams);
if (count($orphanexpenses) > 0) {
  $pageParams['orphanexpenses'] = $runtime->txt->do_template($module, 'payments.orphanexpenses', array('orphanexpenses' => $orphanexpenses));
}

print dot('payments', $pageParams);

?>
