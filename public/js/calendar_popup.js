var g_wndCalendar;
var g_just_opened;

function PopupCalendar(server, start_year, end_year, first_day_of_week, date_control_id, date_default)
{ g_just_opened = 1;
  var i, x, y, width, height;

  if(date_default)
  { i = new RegExp("([0-9]+)[^0-9]([0-9]+)[^0-9]([0-9]+)");
    i.exec(date_default);
    date_default = RegExp.$1 + RegExp.$2 + RegExp.$3;
  }
  else
  { i = new Date();
    date_default = (i.getDate() < 10 ? '0' : '') + i.getDate() + '' +  (i.getMonth() < 9 ? '0' : '') + (i.getMonth() + 1) + '' + i.getFullYear();
  }

  var wnd_name = start_year + "_" + end_year + "_" + first_day_of_week + "_" + date_default + "_" + date_control_id;
  
  width = 200;
  height = 200;
  x = (event.screenX + width > screen.availWidth ? event.screenX - width : event.screenX);
  y = (event.screenY + height > screen.availHeight ? event.screenY - height : event.screenY);
  
  var url = server + "/index.pl?p=main/calendar";
  alert(url + ' to load'); 
  g_wndCalendar = window.open(url, wnd_name,
                        "location=0, resizable=1; scrollbars=0, menubar=0, status=0, toolbar=0, " +
                        "left=" + x + ", top=" + y + ", width=" + width + ", height=" + height);
  g_wndCalendar.focus();
}
function HideCalendar()
{ if(g_just_opened == 1)
    g_just_opened = 0;
  else
  { if(g_wndCalendar) 
      g_wndCalendar.close();
  }
}