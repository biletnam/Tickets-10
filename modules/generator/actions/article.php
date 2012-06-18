<?php

use objArticles;
$objA = new objArticles($r);

$gen_user_id = $runtime->get_cookie('gen_user_id', $_REQUEST, 0);

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $articleData = $objA->get_article( ('id' => $id) );
  if (count($articleData) > 0) {
    $articleData['gen_id'] = $gen_user_id;
    $articleData['baseurl'] = 'http://' . $ENV['SERVER_NAME'] . $r['config']['BASEURL_SCRIPTS'];
    $page->add('main', $runtime->doTemplate($module, 'article', $articleData);

    # register pageview
    srun('main', 'RegisterPageview', array('entity_type' => 'viewarticle', 'entity_id' => $id, 'viewer_type' => 'G', 'viewer_id' => $gen_user_id));
  }
}

$page['baseurl'] = $pageParams['baseurl'];
$page['menu'] = '';
print dotmod($module, 'index', $page);


?>