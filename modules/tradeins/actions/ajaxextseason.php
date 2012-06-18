<?php
$resort = lavnn('resort', $_REQUEST, 0);
$id = lavnn('season', $_REQUEST, 0);
$seasons = $runtime->s2a($module, 'ListExtSeasons', array('resort' => $resort));
$seasons = arr2ref(genOptions($seasons, 'id', 'name', $id));
print dot('tradeintooltip.extseason', array('extseasons' => $seasons));
?>
