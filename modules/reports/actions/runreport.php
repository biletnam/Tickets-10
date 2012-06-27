<?php

use ctlDataGrid;

$reportInfo = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %reportInfo = $runtime->s2r($module, 'GetReportData', $_REQUEST); 
  # TODO Check if user has permissions to run the query
  
  if ($acc['superadmin'] == 1 || $acc->check_resource("runreport:$id", $r['userID'])) {
    # Render parameter section  
    $reportParams = $runtime->s2a($module, 'ListReportParameters', $_REQUEST);
    $parsedParams = array();
    $ready = 0; $missing = 0;
    if (count($reportParams) > 0) {
      foreach $p (@reportParams) {
        $name = $p['name'];
        $type = $p['type'];
        $mandatory = $p['mandatory'] || 0; 
        $value = $_REQUEST[$name] || '';
        if ($value == '' && $mandatory == 1) {
          $missing++;
        } else {
          $p['value'] = $value; 
        }
        if ($type == 'dblookup') {
          $values = $r['db']get_array($p['description']);
          $p['options'] = arr2ref(genOptions($values, 'key', 'value', $value));
        } elseif ($type == 'yesno') {
          $yesnooptions = $runtime->getDictArr('main', 'yesno', $value);
          $p['options'] = $yesnooptions;
        } elseif ($type == 'employee') {
          if ($value <> '') {
            $employeeData = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $value));
            $p['employeeInfo'] = $r->txt->do_template('staff', 'employee.name', $employeeData);
          }
          $p['input'] = $r->txt->do_template($module, 'runreport.params.employee.' . ($value == '' ? 'input' : 'hidden'), $p);
        }
        $p['required'] = 'required' if $mandatory == 1;
        $p['rendered'] = $r->txt->do_template($module, "runreport.params.$type", $p);
        push @parsedParams, $p;
      }
      $ready = 1 if $missing == 0;
    } else {
      $ready = 1;
    }
    $reportInfo['parameters'] = $r->txt->do_template($module, 'runreport.params', array('params' => $parsedParams)) if count($reportParams) > 0;
  
    if ($ready == 1) {
      $basequery = $txt->doText($reportInfo['query'], $_REQUEST); 
      print "<!--  $basequery -->" if $_REQUEST['p'] <> '';
      if ($basequery <> '') {
        $grid1 = new ctlDataGrid($r, 'runreport', $basequery, $module);
        $columns = $grid1->reflect_columns($basequery, $reportInfo['allow_sorting']);
        $grid1->set_columns(@columns);
        $grid1->set_width('100%');
        if ($_REQUEST['xml'] <> '') {
          print $grid1->render_xml(); exit();
        }
        $reportInfo['results'] = $r->txt->do_template($module, 'runreport.link2excel', array('link2excel' => str_replace('p=', 'xml=', $ENV['QUERY_STRING']))).$grid1->render();
        $runtime->saveMoment('Finished rendering data grid');
      }
    } else {
      $reportInfo['results'] = $r->txt->do_template($module, 'runreport.notready');
    }
    $page->add('title',  $reportInfo['pagetitle'] = $r->txt->do_template($module, 'title.runreport', $reportInfo);
  
    # register pageview
    $opresult = $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'runreport', 'entity_id' => $id, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
  } else {
    #$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.runreport.notallowed', $pageParams);
  }
} else {
  #$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.runreport.notfound', $pageParams);
}


$page['js'] .= $r->txt->do_template($module, 'pageviews.js');
$page->add('main', $r->txt->do_template($module, 'runreport', $reportInfo);


print $r->txt->do_template('main', 'index.wide', $page);

?>
