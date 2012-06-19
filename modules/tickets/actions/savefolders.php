<?php
$ticket_id = lavnn('ticket_id', $_REQUEST, 0);
if ($ticket_id > 0) {
  $existing = Arrays::cut_column(arr2ref(s2a($module, 'ListUsedTicketFolders', array('ticket_id' => $ticket_id, 'user_id' => $r['userID']))), 'id');
  $ids = join(',', lavnn('folder'));
  
  if (join(',', @existing) <> $ids) {
    foreach $id (lavnn('folder')) {
      if (!in_array($id, $existing)) {
        $runtime->db->sqlrun($module, 'InsertTicketFolderMapping', array('ticket_id' => $ticket_id, 'folder_id' => $id));
      }
    }
    $runtime->db->sqlrun($module, 'DeleteObsoleteTicketFolderMapping', array('folders' => $ids, 'ticket_id' => $ticket_id));
  }

  go("?p=$module/viewticket&id=$ticket_id&tab=folders");
} else {
  go("?p=$module/mytickets");
}

1;
?>
