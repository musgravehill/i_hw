var Report=function(){var e="";var t=0;var n=function(){var e="";if(document.getSelection){e=document.getSelection()}else if(document.selection){e=document.selection.createRange().text}else if(window.getSelection){e=window.getSelection()}return e.toString().trim()};var r=function(){if(e!=""){if(e.length<10||e.length>255){alert("Для отправки сообщения об ошибке необходимо выделить текст (от 10 до 255 символов)");return false}}window.open("/report.php","Сообщение об ошибке","width=400,height=300")};$(document).keypress(function(t){t=t||window.event;if(t.ctrlKey&&(t.keyCode==10||t.keyCode==13)){e=n();if(e=="")return false;r()}});return{getSelectedText:function(){return e},getTypeValue:function(){return t},show:function(i){e=n();t=i;r()}}}()
