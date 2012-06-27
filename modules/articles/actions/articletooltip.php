<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
    $articleInfo = $runtime->s2r($module, 'GetArticleData', $_REQUEST);
    $html = $r->txt->do_template($module, 'articletooltip.articleinfo', $articleInfo);
} else {
    $html = $r->txt->do_template($module, 'articletooltip.notfound');
}

print $html;
?>
