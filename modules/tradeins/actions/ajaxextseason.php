<?php
$resort = lavnn('resort', $_REQUEST, 0);
$id = lavnn('season', $_REQUEST, 0);
$seasons = $runtime->s2a($module, 'ListExtSeasons', array('resort' => $resort));
$seasons = arr2ref(genOptions($seasons, 'id', 'name', $id));
print $r->txt->do_template($module, 'tradeintooltip.extseason', array('extseasons' => $seasons));
?>
