<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
    $articleInfo = $runtime->s2r($module, 'GetArticleData', $_REQUEST);
    $html = $runtime->txt->do_template($module, 'articletooltip.articleinfo', $articleInfo);
} else {
    $html = $runtime->txt->do_template($module, 'articletooltip.notfound');
}

print $html;
?>
