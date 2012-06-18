function StopClick()
{ event.cancelBubble = 1;
}
function CreateCalendar(calendar_name)
{ var calendar_div = document.body.appendChild(document.createElement("DIV"));
  
  calendar_div.id = calendar_name + "_outer_div";
  calendar_div.onclick = StopClick;
  calendar_div.style.position = "absolute";
  calendar_div.style.zIndex = 10;
  calendar_div.style.visibility = "hidden";
  CalendarSetProperty(calendar_name + "_hide_on_click", 1);
}
function DateToCalendar(str)
{ var date_calendar;
  if(str)
  { i = new RegExp("([0-9]+)[^0-9]([0-9]+)[^0-9]([0-9]+)")
    i.exec(str);
    date_calendar = new Date(RegExp.$3, RegExp.$2 - 1, RegExp.$1);
//    alert(str + "\n" + date_calendar + "\n" + RegExp.$3 + "-" + RegExp.$2 + "-" + RegExp.$1)
  }
  else
  { date_calendar = new Date();
  }
  return date_calendar;
}
function CalendarSetProperty(property_name, property_value)
{ var ctrl = document.getElementById(property_name);
  if( ! ctrl)
  { var obj = document.createElement("INPUT");
    obj.type = "hidden";
    ctrl = document.body.appendChild(obj);
    ctrl.id = property_name;
  }
  ctrl.value = property_value;
}
function CalendarGetProperty(property_name)
{ var ctrl = document.getElementById(property_name);
  if(ctrl)
  { return ctrl.value;
  }
  else
  { return "";
  }
}
function ShowCalendar(calendar_name, date_control_id, start_year, end_year, first_day_of_week)
{ var date_calendar;
  var calendar_div = document.getElementById(calendar_name + "_outer_div");
  var date_control = document.getElementById(date_control_id);
  var i, str, ctrl, x, y;

  CalendarSetProperty(calendar_name + "_date_control_id", date_control_id);
  CalendarSetProperty(calendar_name + "_first_day_of_week", first_day_of_week);

  date_calendar = DateToCalendar(date_control.value);
  
  if(start_year > date_calendar.getFullYear())
  { start_year = date_calendar.getFullYear();
  }
  if(end_year < date_calendar.getFullYear())
  { end_year = date_calendar.getFullYear();
  }
  
  for(i = start_year, str = ''; i <= end_year; i++)
  { str += "<option value='" + i + "'>" + i + "\n";
  }
  html = "<table style='background-color: white; border-collapse:collapse;'>\n<tr><td style='border : 1px solid #cccccc'>\n" + 
  		     "<select id='" + calendar_name + "_mnth' style='font-family: Tahoma, Arial, Helvetica; font-size: 8pt;'>\n" + 
  		     "<option value='1'>january\n" +
  		     "<option value='2'>february\n" +
  		     "<option value='3'>march\n" +
  		     "<option value='4'>april\n" +
  		     "<option value='5'>may\n" +
  		     "<option value='6'>june\n" +
  		     "<option value='7'>july\n" +
  		     "<option value='8'>august\n" +
  		     "<option value='9'>september\n" +
  		     "<option value='10'>ocotober\n" +
  		     "<option value='11'>november\n" +
  		     "<option value='12'>december\n" +
  		     "</select><select id='" + calendar_name + 
  		     						"_year' style='font-family: Tahoma, Arial, Helvetica; font-size: 8pt;'>\n" + str +
             "</select>\n<div id='" + calendar_name + "_inner_div'></div></td></tr></table>";
  calendar_div.innerHTML = html;
  ctrl = document.getElementById(calendar_name + "_year");
  ctrl.value = date_calendar.getFullYear();
  ctrl.onchange = CalendarChange;
  ctrl = document.getElementById(calendar_name + "_mnth");
  ctrl.value = date_calendar.getMonth() + 1;
  ctrl.onchange = CalendarChange;

  FillCalendarWeeks(calendar_name);

  x = y = 0;
  ctrl = date_control;
  while(ctrl)
  { y += ctrl.offsetTop;
    x += ctrl.offsetLeft;
    ctrl = ctrl.offsetParent;
  }
  calendar_div.style.left = x;
  calendar_div.style.top = y + date_control.offsetHeight;
  calendar_div.style.visibility = "visible";
  StopClick();
}
function HideCalendar(calendar_name)
{ var calendar_div = document.getElementById(calendar_name + "_outer_div");
  calendar_div.style.visibility = "hidden";
}
function CalendarChange()
{ var str = new String(event.srcElement.id);
  FillCalendarWeeks(str.substr(0, str.length - 5));
}
function FillCalendarWeeks(calendar_name)
{ var date_calendar = new Date();
  var calendar_div = document.getElementById(calendar_name + "_inner_div");
  var i, str, html, d, week_day, ctrl, month, year;
  var week_days = new Array(7);
  var date_control = document.getElementById(CalendarGetProperty(calendar_name + "_date_control_id"));

  date_calendar = DateToCalendar(date_control.value);
  
  ctrl = document.getElementById(calendar_name + "_year");
  year = ctrl.value;
  ctrl = document.getElementById(calendar_name + "_mnth");
  month = ctrl.value - 1;
  
  week_day = CalendarGetProperty(calendar_name + "_first_day_of_week") * 1;
  for(i = 0; i < 7; i++)
  { week_days[i] = (week_day == 7 ? 0 : week_day);
    if(week_day == 7)
    { week_day = 1;
    }
    else
    { week_day++;
    }
  }
  
  html = "<table class='lined' style='border-collapse:collapse;' cellpadding=3>\n<tr>";
  str = "<th style='border : 1px solid #999999; font-family: Courier New, Courier; font-size: 8pt;'>";
  for(i = 0; i < 7; i++)
  { switch(week_days[i])
    { case 1:
          html += str + "mon</th>";
        break;
      case 2:
          html += str + "tue</th>";
        break;
      case 3:
          html += str + "wed</th>";
        break;
      case 4:
          html += str + "thu</th>";
        break;
      case 5:
          html += str + "fri</th>";
        break;
      case 6:
          html += str + "sat</th>";
        break;
      case 0:
          html += str + "sun</th>";
        break;
    }
  }
  html += "</tr>\n<tr>";
  d = new Date(year, month, 1);
  for(i = 0; i < 7 && d.getDay() != week_days[i]; i++)
  { html += "<td>&nbsp;</td>";
  }
  while(d.getMonth() == month)
  { i++;
    html += "<td style='cursor: hand; text-align: center; border : 1px solid #999999; " +
    		"font-family: Tahoma, Arial, Helvetica; font-size: 8pt;";
    if(d.getDate() == date_calendar.getDate() && 
    		  d.getMonth() == date_calendar.getMonth() && 
    		  d.getFullYear() == date_calendar.getFullYear())
    { html += " background-color: blue; color: yellow;' id='" + calendar_name + "_selected_cell' ";
    }
    else
    { html += "' ";
    }
    html +=	"onmouseover=\"this.style.fontWeight='bold';\" " +
    		"onmouseout=\"this.style.fontWeight='normal';\" " +
    		"onclick=\"CalendarToControl('" + calendar_name + "', this);\">" + d.getDate() + "</td>";
    if(d.getDay() == week_days[6])
    { html += "</tr>\n<tr>";
      i = 0;
    }
    d.setDate(d.getDate() + 1);
  }
  if(i != 0)
  { for(; i < 7; i++)
    { html += "<td>&nbsp;</td>";
    }
  }
  calendar_div.innerHTML = html + "</tr></table>";
}
function CalendarToControl(calendar_name, cell)
{ var ctrl, day, year, month;
  
  ctrl = document.getElementById(calendar_name + "_selected_cell");
  if(ctrl)
  { ctrl.style.backgroundColor = "white";
    ctrl.style.color = "black";
    ctrl.id = "";
  }
  cell.style.backgroundColor = "blue";
  cell.style.color = "yellow";
  cell.id = calendar_name + "_selected_cell";
  
  day = cell.innerHTML;
  ctrl = document.getElementById(calendar_name + "_year");
  year = ctrl.value;
  ctrl = document.getElementById(calendar_name + "_mnth");
  month = ctrl.value;
  ctrl = document.getElementById(CalendarGetProperty(calendar_name + "_date_control_id"));
  ctrl.value = day + "." + month + "." + year;
  ctrl.fireEvent("onchange");

  if(CalendarGetProperty(calendar_name + "_hide_on_click") == 1)
  { HideCalendar(calendar_name)
  }
}
