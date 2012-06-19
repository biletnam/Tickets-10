<?php

# obsolete after we have linkpeople component

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {

  # prepare offices
  $_offices = $_REQUEST['office'];
  $offices = $_offices};
  if (!exists $_REQUEST['office']) {
    $_offices = '';
  } elseif (count($offices) > 1) {
    $_offices = join(',', @offices);
  }
  $_REQUEST['offices'] = $_offices;

  # prepare departments
  $_departments = $_REQUEST['department'];
  $departments = $_departments};
  if (!exists $_REQUEST['department']) {
    $_departments = '';
  } elseif (count($departments) > 1) {
    $_departments = join(',', @departments);
  }
  $_REQUEST['departments'] = $_departments;

  $_REQUEST['editor'] = $r['userInfo']['staff_id'];
  $runtime->db->sqlrun($module, 'UpdateArticleAccess', $_REQUEST);  

  $_SESSION['flash'] = 'Article access saved');
  go("?p=$module/edit&id=$id");
} else {
  go("?p=$module/myarticles");
}


?>
