<?php

use Calendar;
use objVouchers;
$objVouchers = new objVouchers($r);

$serie = lavnn('serie', $_REQUEST, 0);
$stock = lavnn('stock', $_REQUEST, 0);

# check access
$access = 'none';
if ($acc['superadmin'] == 1) {
  $access = 'edit';
} elseif ($acc->check_resource("editvoucherstock:$stock", $r['userID'])) {
  $access = 'edit';
} elseif ($acc->check_resource("readvoucherstock:$stock", $r['userID'])) {
  $access = 'read';
}

if ($access <> 'none') { # default for authorized users
  if ($serie > 0 || $stock > 0) {
    # gather ids of checked vouchers    
    $ids = array();
    while (($request_key, $request_value) = each $_REQUEST) {
      my($prefix, $suffix) = split('_', $request_key);
      if ($prefix == 'id' && $suffix <> '') {
        push @ids, $suffix;
      }
    }
    # process ids if there are any
    $op = lavnn('op', $_REQUEST, '');
    if (count($ids) > 0 && $op <> '') {
      if ($op == 'delete') {
        $objVouchers->delete_vouchers(('ids' => join(',', @ids)));
      } elseif($op == 'setlocation') {
        $doc_location_type = lavnn('doc_location_type');
        $doc_location_id = lavnn('doc_location_id');
        $delivery_method = lavnn('delivery_method', $_REQUEST, '');
        $count_failure = 0; $count_success = 0;
        if ($doc_location_type <> '' && $doc_location_id <> '') {
          foreach $id (@ids) {
            $result = $objVouchers->set_location((
              'id' => $id, 
              'doc_location_type' => $doc_location_type, 
              'doc_location_id' => $doc_location_id,
              'delivery_method' => $delivery_method,
            ));
            $count_success += $result; 
            $count_failure += (1 - $result);
          }
          $_SESSION['flash'] = "Location set for ".$count_success." vouchers") if $count_success > 0;
          $_SESSION['error'] = "Location was not set for ".$count_failure." vouchers") if $count_failure > 0;
          if ($count_success > 0 && $doc_location_type == 'employee' && $doc_location_id <> $r['userID']) {
            # Send notification about new vouchers to be arrived
            $delivery_method ||= '[unknown delivery method]';
            $msgsubj = $runtime->txt->do_template($module, 'mail.sentvouchers.subject', array(
              'cnt' => $count_success, 
              'delivery_method' => $delivery_method,
            ));
            $mailBodyParams = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $r['userID'])); 
            $mailBodyParams['cnt'] = $count_success;
            $mailBodyParams['delivery_method'] = $delivery_method;  
            $msgbody = $runtime->txt->do_template($module, 'mail.sentvouchers.body', $mailBodyParams);
            use objNotification;
            $objN = new objNotification($r);
            $notification_id = $objN->add_notification('sentdocs', $r['userID'], $msgsubj, $msgbody);
            if ($notification_id > 0) {
              $objN->add_notification_recipient($notification_id, $doc_location_id, '', '');
            }
          }
        } else {
          $_SESSION['error'] = "New location is not specified in full (code: $doc_location_type:$doc_location_id)");
        }
      } elseif($op == 'setowner') {
        $owner_type = lavnn('owner_type');
        $owner_id = lavnn('owner_id');
        $owner_change_date = lavnn('owner_change_date') || Calendar::getToday();
        #formdebug($_REQUEST); die($owner_change_date);
        if ($owner_type <> '' && $owner_id <> '') {
          foreach $id (@ids) {
            $result = $objVouchers->set_owner((
              'id' => $id, 
              'owner_type' => $owner_type, 
              'owner_id' => $owner_id,
              'owner_delivery_method' => lavnn('owner_delivery_method') || '',
              'owner_change_date' => $owner_change_date,
            ));
          }
          # TODO notification stuff should also be sent on this stage
        }
      }
    } else {
      $_SESSION['error'] = "Select some items in order to run multiple operation");
    }
    
    go("?p=$module/serie&tab=vouchers&id=$serie") if $serie > 0;
    go("?p=$module/stock&tab=vouchers&id=$stock") if $stock > 0;
  } else {
    go("?p=$module/home");
  }
} else { # default for unauthorized users
  $_SESSION['error'] = "You are not authorized to manage this stock");
  go("?p=main/dashboard&tab=docs");
}

    
?>
