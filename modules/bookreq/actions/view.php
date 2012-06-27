<?php
use objBooking;
$objB = new objBooking($r);

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $bookreqInfo = $runtime->s2r($module, 'GetRequestInfo', $_REQUEST);
  #print Dumper($bookreqInfo);
  if (count($bookreqInfo) > 0) {
    $page->add('title',  $bookreqInfo['pagetitle'] = $r->txt->do_template($module, 'title.view', $bookreqInfo);
    # If client_id is not done, look up from contract
    if ($bookreqInfo['client_id'] == '' && $bookreqInfo['contract_id'] <> '') {
      $contractInfo = $objB->get_contract_info(('id' => $bookreqInfo['contract_id']));
      $bookreqInfo['client_id'] = $contractInfo['Client_Number'];
    }
    # Check if there are more people in the booking - in this case, we need to parse XML    
    if ($bookreqInfo['morepeople'] <> '') {
      $morepeople = Arrays::xml2a($bookreqInfo['morepeople'], 'person');
      if (count($morepeople) > 0) {
        $bookreqInfo['otherpeople'] = loopt('view.additionalperson', @morepeople);
      }
    }

    $status = $bookreqInfo['status'];
    if ($status == 'NEW') {
      $offices = genOptions(arr2ref(s2a($module, 'ListBookreqOffices', $bookreqInfo)), 'office_id' , 'office_name');
      $bookreqInfo['offices'] = $offices;
      $page->add('main',  $r->txt->do_template($module, 'view.new', $bookreqInfo);
    } else {
      $page->add('main',  $r->txt->do_template($module, 'view', $bookreqInfo);
    }
  } else {
    $page->add('main', $r->txt->do_template($module, 'view.notfound');    
  }
}


?>
