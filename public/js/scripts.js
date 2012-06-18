function g(strGo) {
	location.href = strGo;
}

function gc(strAsk, strGo) {
	if (confirm(strAsk)) location.href = strGo;
}

function s(objForm, strAction) {
	objForm.a.value = strAction;
	objForm.submit();
}

function sc(strAsk, objForm, strAction) {
	if (confirm(strAsk)) {
		objForm.a.value = strAction;
		objForm.submit();
	}
}

// Common handler for submitting forms
$(function(){
  //ondomload
  $("form").submit(function(){
    var t = $(this);
    t.find("input[type=submit]")
      .attr("disabled", "disabled")
      .after('<div id="working">working...</div>')
      .hide('slow');
  });
});

function a(div, strUrl) {
  // load contents of strUrl to div using AJAX
  $(div).load(strUrl);
}

function ac(strAsk, div, strUrl) {
  // load contents of strUrl to div using AJAX, with confirmation message strAsk 
	if (confirm(strAsk)) $(div).load(strUrl);
}

function toggle(div2Hide, div2Show) {
	document.getElementById(div2Hide).style.display = 'none';	
	document.getElementById(div2Show).style.display = 'block';	
}
		
function toggleDivs(prefix) {
	var divs = document.getElementsByTagName("div");
	for(var i = 0; i < divs.length; i++) {
		d = divs[i];
		if (d.id.substring(0, prefix.length) == prefix) {
  		d.style.display = (d.style.display == 'none' || d.style.display == '' ? 'block' : 'none');
    }
	}
}

function urlencode(str) {
  str = escape(str);
  str = str.replace(/\+/g, '%2B');
  str = str.replace(/%20/g, '+');
  str = str.replace(/\*/g, '%2A');
  str = str.replace(/\//g, '%2F');
  str = str.replace(/@/g, '%40');
  return str;
}

function checkAllBoxes(objFrm, prefix, value) {
  for (var i = 0; i < objFrm.elements.length; i++) {
    var e = objFrm.elements[i];
    if ( (e.name.indexOf(prefix) == 0) && (e.type=='checkbox') && (!e.disabled) ) {
      e.checked = value;
    }
  }
}

function preselectCheckboxes(prefix, ids) {
  if (ids != '') {
    $.each(ids.split(','), function(index, value) { 
      $('#' + prefix + value).attr('checked', 'checked'); 
    });
  }
}



function ow(strUrl, strTitle, intWidth, intHeight) {
	// fix for other browsers than Safari
	if (navigator.appVersion.indexOf('Safari') < 0) {
		intWidth += 20;
		intHeight += 28;
	}
	strTitle = '';
	newWindow = window.open(strUrl, strTitle, 'width=' + intWidth + ',height=' + intHeight + ',left=0,top=0, toolbar=no,location=no,scrollbars=no,status=no,resizable=no,fullscreen=no');
	return;
}

function getCheckedValue(radioObj) {
  var output = '';
	var radioLength = radioObj.length;

	for (var i = 0; i < radioLength; i++) {
		if (radioObj[i].checked == true) {
  		output = radioObj[i].value;
    }
	}

	return output;
}

function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}

function getElementByIdCompatible(the_id) {
  if (typeof the_id != 'string') {
    return the_id;
  }
  
  if (typeof document.getElementById != 'undefined') {
    return document.getElementById(the_id);
  } else if (typeof document.all != 'undefined') {
    return document.all[the_id];
  } else if (typeof document.layers != 'undefined') {
    return document.layers[the_id];
  } else {
    return null;
  }
}

function update_attachment_description(id) {
  var inputName = 'attachment_description_' + id;
  var newDescription = prompt('Please enter new description for the attachment:', $('#' + inputName).html());
  if (newDescription != null) {
    $('#' + inputName).load('?i=main/set_attachment_description&id=' + id + '&description=' + escape(newDescription));
  }
}

function suggestPerson(type, inputID, inputName) {
  $('#' + inputID).jsonSuggest(
    function(text, wildCard, caseSensitive, notCharacter) { 
      rez = $.ajax({ 
        type: 'GET', 
        url: 'index.pl', 
        data: 'json=main/suggest&type=' + type + '&q=' + text, 
        dataType: 'json', 
        async: false 
      }); 
      return eval(rez.responseText); 
    }, 
    { 
      ajaxResults: true,
      minCharacters: 2,
      onSelect: function(item) {
      	var a = '<a onclick="' + "$('#" + inputID + "_ajax').hide(); $('#" + inputID + "').val('').show(); " + '" style="text-decoration: none;" title="click to remove"> X </a>';
      	$('#' + inputID)
          .after('<div id="'+ inputID + '_ajax" class="suggesteditem clearfix"><div class="suggestedname"><input type="hidden" name="' + inputName + '" value="' + item.id + '" />' + item.text + ' &nbsp; </div><div class="suggestedclose">' + a + '</div></div>')
          .hide();
      } 
    }
  );
}

function showAjaxPopup(params) {
  var url = params['url'];
  var width = params['width']; 
  var id = params['id'];
  var wwidth = $(window).width();
  var left = (wwidth - width) / 2;
  var top = 200;
  if (id != '') {
    position = $('#' + id).offset(); 
    left = position.left; 
    top = position.top;
    if (wwidth < left + width + 50) {
      left = wwidth - width - 50; 
    }
  }
  $.get(url, function(data) {
    $('#popup').width(width).css('left', left + 'px').css('top', top + 'px').html(data).slideDown();
  });
}

function hidePopup() {
  $('#popup').hide();
}

function setManualTicketWeight(ticket, weight) {
  var newWeight = prompt('Please enter a new weight for ticket #' + ticket, weight); 
  if (newWeight != '') {
    $('#ticketweight_' + ticket).load('?i=tickets/setmanualweight&id=' + ticket + '&weight=' + newWeight);
  }
}

function deleteTicketReminder(ticket, reminder) {
  $('#action-reminders').load('?i=tickets/myticketreminders&id=' + ticket + '&delete=' + reminder);
}

function help(lang, code, title) {
  g('?p=help/view&lang=' + lang + '&code=' + code + '&title=' + urlencode(title));
}

function ajaxload(div, url) {
  $('#' + div).load(url);
}

function hideArticleImages() {
  $('#article_hideimages').hide();
  $('#article_showimages').show();
  $('.articleimage').hide();
  $('.articleimageswitcher').show();
}

function showArticleImages() {
  $('#article_hideimages').show();
  $('#article_showimages').hide();
  $('.articleimage').show();
  $('.articleimageswitcher').hide();
}

function showArticleImage(id) {
  $('#articleimage_' + id).show();
  $('#articleimage_' + id + '_switcher').hide();
}