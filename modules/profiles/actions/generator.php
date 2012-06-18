<?php

use objEntity;
$objE = new objEntity($r);
$id = lavnn('id');
if ($id > 0) {
  $generatorInfo = $objE->getInfo(('entity_type' => 'generator', 'entity_id' => $id));
  #print Dumper($generatorInfo);
  $runtime->saveMoment('  generator info fetched from db');
  if (count($generatorInfo) == 0) {
    $page->add('title',  $generatorInfo['pagetitle'] = $runtime->doTemplate($module, 'title.generator.notfound');
    $page->add('main', $runtime->doTemplate($module, 'generator.notfound', $generatorInfo);
  } else {
    $page->add('title',  $generatorInfo['pagetitle'] = $runtime->doTemplate($module, 'title.generator', $generatorInfo);
    $wallPosts = $objWall->get_posts(('entity_type' => 'generator', 'entity_id' =>$id)); #print Dumper($wallPosts);
    $generatorInfo['wall'] = $objWall->render($wallPosts);
    $page->add('main', $runtime->doTemplate($module, 'generator', $generatorInfo);
  }
}



?>
