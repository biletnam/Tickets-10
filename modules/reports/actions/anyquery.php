<?php

use ctlDataGrid;

# TODO: prefetch query from the gallery by key

$basequery = $runtime->trim(lavnn('query')); 
$pageParams = ('query' => $basequery);

if ($basequery <> '') {
  $grid1 = new ctlDataGrid($r, 'anyquery', $basequery, $module);
  $columns = $grid1->reflect_columns($basequery);
  $grid1->set_columns(@columns);
  $grid1->set_width('100%');
  $pageParams['results'] = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');
}


$page['js'] .= $runtime->doTemplate($module, 'pageviews.js');
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.anyquery', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'anyquery', $pageParams);



?>
