<?php

use ctlDataGrid;

# TODO: prefetch query from the gallery by key

$basequery = trim(lavnn('query')); 
$pageParams = array('query' => $basequery);

if ($basequery <> '') {
  $grid1 = new ctlDataGrid($r, 'anyquery', $basequery, $module);
  $columns = $grid1->reflect_columns($basequery);
  $grid1->set_columns(@columns);
  $grid1->set_width('100%');
  $pageParams['results'] = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');
}


$page['js'] .= $runtime->txt->do_template($module, 'pageviews.js');
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.anyquery', $pageParams);
$page->add('main', $runtime->txt->do_template($module, 'anyquery', $pageParams);



?>
