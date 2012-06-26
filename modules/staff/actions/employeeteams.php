<?php

$id = lavnn('id', $_REQUEST, '');
$personInfo = array();
$errors = array();

if ($id <> '') {
  %personInfo = $runtime->s2r($module, 'GetEmployeeDetails', $_REQUEST);
  $teams = $runtime->s2a($module, 'ListEmployeeTeams', $personInfo);
  if (count($teams) == 0) {
    push @errors, array('errortext' => 'Teams setup is missing!'));
  }
  $teamoptions = genOptions(arr2ref(s2a($module, 'ListTeams')), 'id', 'name');
  $personInfo['teams'] = $teams;
  $personInfo['teamoptions'] = $teamoptions;
  checkDates($errors, @teams);
  $personInfo['problems'] = $runtime->txt->do_template($module, 'employeeteams.problems', array('errors' => $errors)) if (count($errors) > 0); 
  
  print $runtime->txt->do_template($module, 'employeeteams', $personInfo);
}

function checkDates {
  ($errors, @teams)
  $first = shift @teams;
  checkFirstLast($errors, $first);
  $edfirst = $first['eD_first'];
  $started = DateChemistry::Format(DateChemistry::CDate($first['DateStarted'], 'fulldate'));
  $teamname = $first['TeamName'];
  foreach $team (@teams) {
    checkFirstLast($errors, $team);
    if ($team['eD_last'] == '') {
      push $errors, array('errortext' => "Only newest team record can have open finishing date"));
    } elseif ($edfirst < $team['eD_last']) {
      push $errors, array('errortext' => "Dates overlap for " . $team['TeamName'] . " and $teamname"));
    } elseif ($edfirst - 1 > $team['eD_last']) {
      push $errors, array('errortext' => "There is a gap between two periods, look dates from " . DateChemistry::Format(DateChemistry::CDate($team['DateFinished']), 'fulldate') . ' to ' . $started));
    }
    $edfirst = $team['eD_first'];
    $teamname = $team['TeamName'];
    $started = DateChemistry::Format(DateChemistry::CDate($team['DateStarted'], 'fulldate'));
  }
}

function checkFirstLast {
  ($errors, $team)
  if ($team['eD_first'] > $team['eD_last'] && $team['eD_last'] <> '') {
    push $errors, array('errortext' => "First day of belonging to the team '" . $team['TeamName'] . "'(starting " .$team['eD_first']. ") should be no greater than the last one!"));
  }
}
1;
?>
