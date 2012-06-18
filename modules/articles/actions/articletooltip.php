<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
    $articleInfo = $runtime->s2r($module, 'GetArticleData', $_REQUEST);
    $html = $runtime->doTemplate($module, 'articletooltip.articleinfo', $articleInfo);
} else {
    $html = $runtime->doTemplate($module, 'articletooltip.notfound');
}

print $html;
?>
