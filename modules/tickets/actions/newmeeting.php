<?php


$copyfrommeeting = lavnn('copyfrommeeting', $_REQUEST, '');
$copyfromfolder = lavnn('copyfromfolder', $_REQUEST, '');
$originalmeeting = lavnn('clone', $_REQUEST, '');

if ($originalmeeting <> '') {
  $originalMeetingData = $runtime->s2r($module, 'GetMeetingData', array('id' => $originalmeeting)); 
  $originalMeetingData['clonecopy'] = $runtime->txt->do_template($module, 'newmeeting.clonemeeting', $pageParams, $originalMeetingData);
  $page->add('title',  $originalMeetingData['pagetitle'] = $runtime->txt->do_template($module, 'title.clonemeeting', $originalMeetingData);
  $page->add('main', $runtime->txt->do_template($module, 'newmeeting', $originalMeetingData);
} else {
  $meetings = $runtime->s2a($module, 'ListMeetings', array('user_id' => $r['userID']));
  $folders = $runtime->s2a($module, 'ListTicketFolders', array('user_id' => $r['userID']));
  $pageParams['copyfromoptions'] = arr2ref(genOptions($meetings, 'id', 'name', $copyfrom));
  $pageParams['copyfromfolderoptions'] = arr2ref(genOptions($folders, 'id', 'name_with_cnt', $copyfromfolder));
  $pageParams['clonecopy'] = $runtime->txt->do_template($module, 'newmeeting.copytickets', $pageParams, $originalMeetingData);
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.newmeeting');
  $page->add('main', $runtime->txt->do_template($module, 'newmeeting', $pageParams);
}




?>
