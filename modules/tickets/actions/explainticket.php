<?php

$id = lavnn('id');
$ticketInfo = $runtime->s2r($module, 'GetTicketInfo', array('id' => $id));
print "<pre>Ticket before explaining";
print Dumper($ticketInfo);
$ticketInfo['explained'] = $r->txt->do_template($module, 'ticket.explain', $ticketInfo);
print 'New explanation: '.$ticketInfo['explained'];
$result = $runtime->db->sqlrun($module, 'UpdateTicketExplanation', $ticketInfo);
print "Update result: $result";  
print "Query executed: " . spreview($module, 'UpdateTicketExplanation', $ticketInfo) if $result <> 1;

1;

?>
