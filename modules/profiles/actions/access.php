<?php

$ITEMS_PER_PAGE = lavnn('perpage', $_REQUEST, 10);

$pageParams = array('perpage' => $ITEMS_PER_PAGE);
$userInfo = $r['userInfo'];
$user_id = $r['userID'];
$resources = $acc->list_user_resources('', $user_id);
$bytype = slice_array($resources, 'source_type');
#print Dumper($bytype);

$type = lavnn('type', $_REQUEST, '');
if ($type == 'followhotel') {
    $entities = $bytype{$type];
    $cnt = count($entities);
    $pageParams['accesses'] = render_entities($type, $cnt, join_column(',', 'source_id', $entities));
    $page->add('title', $pageParams['pagetitle'] = $runtime->txt->do_template($module, "title.access.$type");
    $page->add('main', $runtime->txt->do_template($module, "access.$type", $pageParams);
} else {
    $accesses = array();
    foreach ($bytype as $type => $entities) {
        $cnt = count($entities);
        $ee = render_entities($type, $cnt, join_column(',', 'source_id', $entities), $ITEMS_PER_PAGE);
        if $ee <> '' {
            $accesses[] = $ee;
        }
    }
}
#print Dumper($accesses);
$pageParams['accesses'] = join('', $accesses);
$pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.access');
$page->add('title', $pageParams['pagetitle']);
$page->add('main', $runtime->txt->do_template($module, 'access', $pageParams));




function render_entities($type, $cnt, $ids, $perpage = -1) {
    $allowed = array('readarticle', 'viewarticle', 'followhotel', 'editoffice', 'editpoll');
    if (in_array($type, $allowed)) {
        if ($cnt <= $perpage || $perpage < 0) {
            $entities = $runtime->s2a($module, "GetEntities.$type", array('ids' => $ids));
            $access = array('type' => $type, 'cnt' => count($entities), 'entities' => $entities);
            return dot("access.$type.list", $access);
        } elseif($cnt > $perpage) {
            return dot("access.$type.stats", array('cnt' => $cnt));
        }
    }
    return '';  
}

*/

?>
