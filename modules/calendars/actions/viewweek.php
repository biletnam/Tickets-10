<?php

print Dumper($_REQUEST);

# Get list of events in this calendar
$events = $runtime->s2a($module, 'GetCalendarEvents', $_REQUEST);
$runtime->saveMoment('  fetched list of calendar events from db');
print Dumper($events);


?>
