<?php

$pageParams = $_REQUEST;

# Make sure that we understand what tag was requested
$fulltag = lavnn('tag') || ':';
($prefix, $tag) = split(':', $fulltag, 2);
if ($tag == '' && $prefix <> '') {
  $tag = $prefix; $prefix = '';
}
$search = lavnn('search', $_REQUEST, '');

# Get pagelet that renders articles
$sqlParams = array(
  'id' => $r['userID'], 
  'office' => $r['userInfo']['lngWorkPlace'].'',
  'department' => $r['userInfo']['team_id'].'',
  'sourcetype' => "readarticle,editarticle", 
  'search' => $search,
);

$articleblocks = $runtime->s2a($module, 'ListArticleBlocks');
$blockdict = Arrays::array2map($articleblocks, 'code'); 

$articles = $objA->search_articles(%sqlParams); 
$articlesByBlock = slice_array($articles, 'block');
$noblock = array(); $blocks = array();
foreach $key (keys %articlesByBlock) {
  $block = lc($key, $_REQUEST, ''); 
  $articles = $articlesByBlock{$key];
  if ($block <> '') {
    $blockInfo = $blockdict{$block]; 
    $seqno = $blockInfo['seqno']; 
    if (exists($blockdict{$block})) {
      # Limit every block to 5 last articles
      if (count($articles) > 5) {
        @articles = @articles[0..4]; 
      }
      # store generated block with its number 
      $blockInfo['block_content'] = loopt('blockarticle', @articles);
      $blocks{$seqno} = $runtime->txt->do_template($module, 'home.block', $blockInfo); 
    } else {
      @noblock = array(@noblock, @articles); 
    }
  } else {
    @noblock = array(@noblock, @articles); 
  }
}
# Limit Misc block to 5 items and add it to $blocks 
#if (count($noblock) > 5) { @noblock = @noblock[0..4];  }

# Print out blocks in order of their sequence numbers
$sortedblocks = array(); 
foreach $key (sort keys %blocks) {
  push @sortedblocks, $blocks{$key};
}
$pageParams['blocks'] = join('', $sortedblocks);

# Render page from all calculated parts
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.home');
$page->add('main',  $runtime->txt->do_template($module, 'home', $pageParams);
$page->add('css',  $runtime->txt->do_template($module, 'css');



?>
