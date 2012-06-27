<?php

use ctlTab;
use objTicketing;
$objT = new objTicketing($r);

$_REQUEST['user_id'] = $r['userID'];
$ticketInfo = $runtime->s2r($module, 'GetTicketInfo', $_REQUEST);
if (count($ticketInfo) > 0) {

  $ticket_id = $ticketInfo['id'];
  $page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
  $page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');

  $comments = $runtime->s2a($module, 'ListTicketComments', $_REQUEST);
  $ticketInfo['comments'] = $comments;
  $runtime->saveMoment('  fetched list of comments');
  
  $attachments = $runtime->s2a($module, 'ListTicketAttachments', $_REQUEST);
  $ticketInfo['attachments'] = $attachments;
  $runtime->saveMoment('  fetched list of attachments');

  # Get related targets
  $targets = array();
  foreach $target (s2a($module, 'ListTicketTargets', $_REQUEST)) {
    $targetInfo = $objT->explain_target($target['target']);
    $target['targettitle'] = $targetInfo['title'];
    $target['sessionID'] = $targetInfo['sessionID'];
    if ($targetInfo['centralurl'] <> '') {
      $target['centralurl'] = $targetInfo['centralurl'];
      push @targets, dot('viewticket.target.central', ${$target});
    } else {
      push @targets, dot('viewticket.target', ${$target});
    }
  }
  push @targets, dot('viewticket.target.add', $ticketInfo);
  $ticketInfo['targets'] = join('', $targets);  
  $runtime->saveMoment('  fetched list of targets');
  
  # Get history of the ticket
  $activities = $objT->explain_full_history(lavnn('id'));
  $ticketInfo['activities'] = $activities;

  # Some shortcut boolean variables  
  $isCreator = $ticketInfo['creator'] == $r['userInfo']['staff_id'];
  $isReviewer = $ticketInfo['reviewer'] == $r['userInfo']['staff_id'];
  $isHandler = $ticketInfo['handler'] == $r['userInfo']['staff_id'];
  $privateTicket = array($isCreator && $isReviewer && $isHandler);
  $isRelated = array($isCreator || $isReviewer || $isHandler);
  $isSuperadmin = $acc->is_superadmin(); 

  # Set due date
  if ($isCreator || $isReviewer) {
    $ticketInfo['setduedate'] = $r->txt->do_template($module, 'viewticket.action.setduedate', $ticketInfo);
  }
  # Set reminder
  $reminders = $runtime->s2a($module, 'ListTicketReminders', array('user_id' => $r['userID'], 'ticket_id' => $ticket_id));
  $ticketInfo['remindershistory'] = $reminders; 
  $ticketInfo['reminders'] = $r->txt->do_template($module, 'viewticket.action.reminders', $ticketInfo);
  # Set priority     
  if ($isCreator || $isReviewer) {
    $priorityoptions = $runtime->getSortedDictArr($module, 'priority', $ticketInfo['priority']);
    $ticketInfo['priorities'] = $priorityoptions;
    $ticketInfo['setpriority'] = $r->txt->do_template($module, 'viewticket.action.setpriority', $ticketInfo);
  }
  # Change title
  if ($isCreator || $isReviewer) {
    $ticketInfo['edittitle'] = $r->txt->do_template($module, 'viewticket.action.edittitle', $ticketInfo);
  }
  # Change summary
  if ($isCreator || $isReviewer) {
    $ticketInfo['editsummary'] = $r->txt->do_template($module, 'viewticket.action.editsummary', $ticketInfo);
  }
  $runtime->saveMoment('  received all properties, ready to render action tab');

  ################## decide when to show some possible actions
  $canChangeProject = 0; $canForward2Handler = 0;
  if ($ticketInfo['status'] == 'NEW') {
    # For a new ticket, show 'Send2Handler' for Reviewer
    if ($isReviewer) {
      $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.send2handler', $ticketInfo);                          
      if (!$isCreator) {
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markfixed', $ticketInfo);    
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markcancelled', $ticketInfo);    
      }
    } elseif($isCreator) {
      $canChangeProject = 1;
      $canForward2Handler = 1;
    }
  } elseif ($ticketInfo['status'] == 'OPN') {
    if ($privateTicket) {
      $canChangeProject = 1;
      $canForward2Handler = 1;
    } elseif ($isHandler) { # only Handler is expected to react on the ticket
      if (!$isReviewer) { # return to Reviewer
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.send2test', $ticketInfo);    
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.reject', $ticketInfo);          
      } elseif(!$isCreator) { # Handler is also a Reviewer, so we can return ticket to Creator
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markfixed', $ticketInfo);    
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markcancelled', $ticketInfo);    
        $canForward2Handler = 1;
      }
    } elseif ($isReviewer) {
      if (!$isCreator) {
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markfixed', $ticketInfo);    
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markcancelled', $ticketInfo);    
      } else {
        # Direct ticket to Handler
        $canChangeProject = 1;
      }
      # force ticket to another handler
      $canForward2Handler = 1;
      $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2handler', $ticketInfo);    
    } elseif($isCreator) {
      $canChangeProject = 1;
      $canForward2Handler = 1;
    }
  } elseif ($ticketInfo['status'] == 'RDY') {
    if ($isReviewer) { # only Reviewer is expected to react on the ticket
      # accept-action (upwards) depends on whether Reviewer is also a Creator
      if (!$isCreator) { # No need to return to Creator - he just closes it
        # Send back to Creator showing that problem is supposedly fixed
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markfixed', $ticketInfo);    
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markcancelled', $ticketInfo);    
      }
      # reject-action (downwards) depends in whether Reviewer is also a Handler
      if (!$isHandler) { 
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2handler', $ticketInfo);    
      }
      $canForward2Handler = 1;
    } elseif ($isCreator) {
      $canChangeProject = 1;
    }
  } elseif ($ticketInfo['status'] == 'RJC') {
    if ($isReviewer) { # only Reviewer is expected to react on the ticket
      # accept-action (upwards) depends on whether Reviewer is also a Creator
      if (!$isCreator) { # No need to return for Creator, who can just close
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.markcancelled', $ticketInfo);    
      }    
      # reject-action (downwards) depends in whether Reviewer is also a Handler
      if (!$isHandler) { 
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2handler', $ticketInfo);    
      }
      $canForward2Handler = 1;
      # reject-action downwards can be done with reassigning ticket to a different project (if Reviewer is also Creator)
      if ($isCreator) {
        $canChangeProject = 1;
      }
    } elseif ($isCreator) {
      $canChangeProject = 1;
    }
  } elseif ($ticketInfo['status'] == 'FIX') {
    if ($isCreator) { # only Creator is expected to react on the ticket
      # accept-action (upwards) - just close (the option that is always there)
      # reject-action (downwards)
      if (!$isReviewer) {
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2reviewer', $ticketInfo);
      } elseif(!$isHandler) {
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2handler', $ticketInfo);
      }
    }
  } elseif ($ticketInfo['status'] == 'CLD') {
    if ($isCreator) { # only Creator is expected to react on the ticket
      # accept-action (upwards) - just close (the option that is always there)
      # reject-action (downwards)
      if (!$isReviewer) {
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2reviewer', $ticketInfo);
      } elseif(!$isHandler) {
        $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.back2handler', $ticketInfo);
      }
      $canChangeProject = 1;
    }
  } elseif ($ticketInfo['status'] == 'CLO') {
    # Creator is able to reopen tickets
    if ($isCreator) {
    $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.reopen', $ticketInfo);
    }
  }
  if ($canForward2Handler) {
    $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.send2anotherhandler', $ticketInfo);                           
  }
  if ($canChangeProject) {
    $projects = $objT->list_projects('read');
    $projectoptions = genOptions($projects, 'id', 'title');
    $ticketInfo['projects'] = $projectoptions;
    $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.send2project', $ticketInfo);     
  }
  # Clone ticket
  if ($isCreator || $isReviewer) {
    $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.clone', $ticketInfo); 
  }
  # Close ticket
  if ($isCreator && $ticketInfo['status'] <> 'CLO') {
    $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.close', $ticketInfo);    
  }
  # Delete ticket
  if ($isCreator) {
    $ticketInfo['actions'] .= $r->txt->do_template($module, 'viewticket.action.delete', $ticketInfo);    
  }
  $runtime->saveMoment('  list of actions constructed');
  
  # We might provide possibility to take over ticket for superadmins
  if (!$isRelated && $isSuperadmin) {
    $ticketInfo['takeover'] .= $r->txt->do_template($module, 'viewticket.action.takeover', $ticketInfo);
    $ticketInfo['takeover'] .= $r->txt->do_template($module, 'viewticket.action.send2anotherhandler', $ticketInfo);     
  }

  # Show time reporting form
  if ($isHandler) {
    $timeleft = $ticketInfo['minutesleft'];
    $hoursleft = $ticketInfo['hoursleft'] = int($timeleft / 60);
    $ticketInfo['minutesleft'] = $timeleft - $hoursleft * 60;
    $ticketInfo['timereporting'] = $r->txt->do_template($module, 'viewticket.action.timereporting.handler', $ticketInfo);
  } else {
    $ticketInfo['timereporting'] = $r->txt->do_template($module, 'viewticket.action.timereporting', $ticketInfo);
  }
  
  # Get Notification list
  $notified = $runtime->s2a($module, 'GetNotificationList', array('id' => $_REQUEST['id'], 'user_id' => $r['userInfo']['staff_id']));
  $ticketInfo['notified'] = $notified ; 
  $runtime->saveMoment('  fetched notification list');

  # Create a ticket view tab
  $htmlComments = $r->txt->do_template($module, (count($comments) > 0 ? 'viewticket.comments' : 'viewticket.nocomments'), $ticketInfo);
  $htmlAttachments = $r->txt->do_template($module, (count($attachments) > 0 ? 'viewticket.attachments' : 'viewticket.noattachments'), $ticketInfo);
  $htmlAction = $r->txt->do_template($module, ($isCreator || $isReviewer || $isHandler ? 'viewticket.action' : 'viewticket.action.guest'), $ticketInfo);

  $ticketInfo['allfolders'] = arr2ref(s2a($module, 'ListTicketFolders', array('ticket_id' => lavnn('id'), 'user_id' => $r['userID']))); 
  $usedfolders = $runtime->s2a($module, 'ListUsedTicketFolders', array('ticket_id' => lavnn('id'), 'user_id' => $r['userID']));
  $ticketInfo['usedfolders'] = count($usedfolders);
  
  $tabTicketView = new ctlTab($r, "tcTicketView");
  $tabTicketView->addTab('history', dot('viewticket.activities.tabheader'), dot('viewticket.activities', $ticketInfo)); 
  $tabTicketView->addTab('comments', dot('viewticket.comments.tabheader', $ticketInfo), $htmlComments); 
  $tabTicketView->addTab('attachments', dot('viewticket.attachments.tabheader', $ticketInfo), $htmlAttachments); 
  $tabTicketView->addTab('folders', dot('viewticket.folders.tabheader', $ticketInfo), dot('viewticket.folders', $ticketInfo)); 
  $tabTicketView->addTab('reminders', dot('viewticket.reminders.tabheader', $ticketInfo), dot('viewticket.wait.reminders', $ticketInfo)) if ($isCreator); 
  $tabTicketView->addTab('notified', dot('viewticket.notified.tabheader', $ticketInfo), dot('viewticket.notified.list.ajax', $ticketInfo)); 
  $tabTicketView->addTab('action', dot('viewticket.action.tabheader', $ticketInfo), $htmlAction, 'right'); 
  $tabTicketView->setDefaultTab(lavnn('tab') || 'action'); #TODO should we also use user's default?
  $ticketInfo['tabcontrol'] = $tabTicketView->getHTML();
  $runtime->saveMoment('  tab control rendered');

  # Finally, check the easter egg :)
  if ($page['easteregg'] <> '') {
    ($eggname, $eggvalue) = split(':', $page['easteregg']);
    if ($eggname == 'coolticket' && $eggvalue == $_REQUEST['id']) {
      $ticketInfo['easteregg'] = $r->txt->do_template($module, 'easteregg.coolticket', array('id' => $eggvalue));
    }
  }

  $page->add('title',  $r->txt->do_template($module, 'title.viewticket', $ticketInfo);
  $ticketInfo['pagetitle'] = $r->txt->do_template($module, 'title.viewticket.short', $ticketInfo);
  $page->add('main', $r->txt->do_template($module, 'viewticket', $ticketInfo);

  # register pageview
  $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'ticket', 'entity_id' => $_REQUEST['id'], 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
} else {
  $page->add('main', $r->txt->do_template($module, 'viewticket.notfound', $ticketInfo);
}

#$page['js'] .= $r->txt->do_template($module, 'viewticket.notified.js');



?>
