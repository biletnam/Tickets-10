<?php

$n = lavnn('n');
$k = lavnn('k'); 

function distribute {
  ($amplitude, @prev)
  
  $this = (1); 
  for ($i = 1; $i <= $amplitude - 1; $i++) {
    push @this, $prev[$i-1] + $prev[$i];
  }
  push @this, 1; 
  
  return $this;
}

function findindex {
  ($k, @distibution)
  
  $cnt = 0; 
  $ones = 0;
  while ($cnt < $k) {
    $cnt += $distibution[$ones]; 
    $ones++ if $cnt < $k;
  }
  return $ones;  
}

function findoffset {
  ($ones, @distibution)
  
  $offset = 0;
  $place = 0; 
  while ($place < $ones) {
    $offset += $distibution[$place]; 
    $place++;
  }
  return $offset;  
}

$first = distribute(1);
$distributions = ( '1' => $first );
$last = @first;  
for ($i = 2; $i <= $n; $i++) {
  $next = @last = distribute($i, @{$distributions{$i-1}});
  $distributions{$i} = $next;
}

print '<pre>';
print Dumper($last);

$word = ''; 
$minword = ''; 
$maxword = '';
for ($i = 0; $i < $n; $i++) {
  $minword .= '0';
  $maxword .= '1';
}

# Now, let's find how many "ones" are in the resulting words
$ones = findindex($k, @last);
$offset = findoffset($ones, @last);
print "word #$k has $ones 'ones' and offset=$offset \n";
if ($ones == 0) {
  print "this is the first possible word!";
  $word = $minword;
} elseif ($ones == $n) {
  print "this is the last possible word!";
  $word = $maxword;
} else {
  #calculate the word
  $k -= $offset;
  print "remainder to go through the tree: $k;  \n";
  for($i = $n - 1; $i > 0; $i--) {
    $distribution = @{$distributions{$i}}; 
    print "distribution $i: " . Dumper($distribution);
    $goleft = $distribution[$ones-1];
    $gowrite = $distribution[$ones];
    print "at level $i, go left if $k > $goleft words consisting of $ones 'ones' start with 0 and $startwith1 start with 1 \n";

    if ($k > $startwith0) {
      $word .= '1'; $k -= $startwith0; $ones--;
      print "$k > $startwith0 on level $i, so we go right in the tree, use 1, decrease remainder to $k and decrease ones to $ones";
    } else {
      print "$k <= $startwith0 on level $i, we go left in the tree, use 0 and keep ones to $ones";
      $word .= '0'; 
    }


#    $previous = @{$distributions{$i-1}}; 
#    print Dumper($previous);
  #  print "which is $ones element of distribution $i? " . $distribution[$ones];



  }
}

print $word;
?>
