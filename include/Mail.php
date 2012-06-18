<?php
<?php
<?php
<?php

use MIME::Lite;

our $from = $_CONFIG['SENDMAIL_FROM'];
our $smtpserver = $_CONFIG['SENDMAIL_SMTPSERVER'];

function mail {
  ($to, $cc, $subj, $data)

  $msg = MIME::Lite->new(
      Type     => 'text/html',
      From     => $from,
      To       => $to,
      Cc       => $cc, 
      Subject  => $subj,
      Data     => $data
  );
  $msg->attr('content-type.charset' => 'UTF-8');
  
  if ($msg->send_by_smtp($smtpserver)) {
    return 1;
  } else {
    return 0;
  }    

}

function mail_withfiles {
  ($to, $cc, $subj, $body, $files)
  
  ### Create the multipart "container":
  $msg = MIME::Lite->new(
      From    => $from,
      To      => $to,
      Cc      => $cc, 
#      Cc      =>'some@other.com, some@more.com',
      Subject => $subj,
      Type    => 'multipart/mixed'
  );
  $msg->attr('content-type.charset' => 'UTF-8');
  
  ### Add the text message part:
  ### (Note that "attach" has same arguments as "new"):
  $msg->attach(
      Type     => 'text/html',
      Data     => $body
  );

  for $f ($files) {
    ### Add the image part:
    $msg->attach(
        Type        => $f['type'],
        Path        => $f['path'],
        Filename    => $f['filename'],
        Disposition => 'attachment'
    );
  }  
  $msg->attr('content-type.charset' => 'UTF-8');
  
  if ($msg->send_by_smtp($smtpserver)) {
    return 1;
  } else {
    return 0;
  }    

}

1;

?>

?>

?>

?>
