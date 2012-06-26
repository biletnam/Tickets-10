
function switchTab(controlid, tabid) {
  var oldSelectedTab = ($(controlid).html());
  var oldSelectedTabContent = oldSelectedTab + '_content';
  var newSelectedTab = tabid;
  var newSelectedTabContent = tabid + '_content';
  // Set old selected tab's style to regular
  if (oldSelectedTab != '') {
    $(oldSelectedTab).removeClass('selectedtab').addClass('tab');
    $(oldSelectedTabContent).removeClass('visibletabbody').addClass('hiddentabbody');
  }
  // Set new selected tab's style to selected
  $(newSelectedTab).removeClass('tab').addClass('selectedtab');
  $(newSelectedTabContent).removeClass('hiddentabbody').addClass('visibletabbody');
  // Finally, set name of newly selected tab to control
  $(controlid).html(tabid);
}
    