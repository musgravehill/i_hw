mw.loader.implement("jquery.accessKeyLabel",function($,jQuery){(function($,mw){var cachedAccessKeyPrefix,useTestPrefix=false,labelable='button, input, textarea, keygen, meter, output, progress, select';function getAccessKeyPrefix(ua){if(!ua&&cachedAccessKeyPrefix){return cachedAccessKeyPrefix;}var profile=$.client.profile(ua),accessKeyPrefix='alt-';if(profile.name==='opera'){accessKeyPrefix='shift-esc-';}else if(profile.name==='chrome'){accessKeyPrefix=(profile.platform==='mac'?'ctrl-option-':'alt-shift-');}else if(profile.platform!=='win'&&profile.name==='safari'&&profile.layoutVersion>526){accessKeyPrefix='ctrl-alt-';}else if(!(profile.platform==='win'&&profile.name==='safari')&&(profile.name==='safari'||profile.platform==='mac'||profile.name==='konqueror')){accessKeyPrefix='ctrl-';}else if((profile.name==='firefox'||profile.name==='iceweasel')&&profile.versionBase>'1'){accessKeyPrefix='alt-shift-';}if(!ua){cachedAccessKeyPrefix=accessKeyPrefix;}return accessKeyPrefix;}function
getAccessKeyLabel(element){if(!element.accessKey){return'';}if(!useTestPrefix&&element.accessKeyLabel){return element.accessKeyLabel;}return(useTestPrefix?'test-':getAccessKeyPrefix())+element.accessKey;}function updateTooltipOnElement(element,titleElement){var array=(mw.msg('word-separator')+mw.msg('brackets')).split('$1'),regexp=new RegExp($.map(array,$.escapeRE).join('.*?')+'$'),oldTitle=titleElement.title,rawTitle=oldTitle.replace(regexp,''),newTitle=rawTitle,accessKeyLabel=getAccessKeyLabel(element);if(!oldTitle){return;}if(accessKeyLabel){newTitle+=mw.msg('word-separator')+mw.msg('brackets',accessKeyLabel);}if(oldTitle!==newTitle){titleElement.title=newTitle;}}function updateTooltip(element){var id,$element,$label,$labelParent;updateTooltipOnElement(element,element);$element=$(element);if($element.is(labelable)){id=element.id.replace(/"/g,'\\"');if(id){$label=$('label[for="'+id+'"]');if($label.length===1){updateTooltipOnElement(element,$label[0]);}}$labelParent=$element.parents(
'label');if($labelParent.length===1){updateTooltipOnElement(element,$labelParent[0]);}}}$.fn.updateTooltipAccessKeys=function(){return this.each(function(){updateTooltip(this);});};$.fn.updateTooltipAccessKeys.getAccessKeyPrefix=getAccessKeyPrefix;$.fn.updateTooltipAccessKeys.setTestMode=function(mode){useTestPrefix=mode;};}(jQuery,mediaWiki));},{},{"brackets":"[$1]","word-separator":" "});mw.loader.implement("jquery.client",function($,jQuery){(function($){var profileCache={};$.client={profile:function(nav){if(nav===undefined){nav=window.navigator;}if(profileCache[nav.userAgent+'|'+nav.platform]!==undefined){return profileCache[nav.userAgent+'|'+nav.platform];}var versionNumber,key=nav.userAgent+'|'+nav.platform,uk='unknown',x='x',wildUserAgents=['Opera','Navigator','Minefield','KHTML','Chrome','PLAYSTATION 3','Iceweasel'],userAgentTranslations=[[/(Firefox|MSIE|KHTML,?\slike\sGecko|Konqueror)/,''],['Chrome Safari','Chrome'],['KHTML','Konqueror'],['Minefield','Firefox'],['Navigator',
'Netscape'],['PLAYSTATION 3','PS3']],versionPrefixes=['camino','chrome','firefox','iceweasel','netscape','netscape6','opera','version','konqueror','lynx','msie','safari','ps3','android'],versionSuffix='(\\/|\\;?\\s|)([a-z0-9\\.\\+]*?)(\\;|dev|rel|\\)|\\s|$)',names=['camino','chrome','firefox','iceweasel','netscape','konqueror','lynx','msie','opera','safari','ipod','iphone','blackberry','ps3','rekonq','android'],nameTranslations=[],layouts=['gecko','konqueror','msie','trident','opera','webkit'],layoutTranslations=[['konqueror','khtml'],['msie','trident'],['opera','presto']],layoutVersions=['applewebkit','gecko','trident'],platforms=['win','wow64','mac','linux','sunos','solaris','iphone'],platformTranslations=[['sunos','solaris'],['wow64','win']],translate=function(source,translations){var i;for(i=0;i<translations.length;i++){source=source.replace(translations[i][0],translations[i][1]);}return source;},ua=nav.userAgent,match,name=uk,layout=uk,layoutversion=uk,platform=uk,version=x;if(
match=new RegExp('('+wildUserAgents.join('|')+')').exec(ua)){ua=translate(ua,userAgentTranslations);}ua=ua.toLowerCase();if(match=new RegExp('('+names.join('|')+')').exec(ua)){name=translate(match[1],nameTranslations);}if(match=new RegExp('('+layouts.join('|')+')').exec(ua)){layout=translate(match[1],layoutTranslations);}if(match=new RegExp('('+layoutVersions.join('|')+')\\\/(\\d+)').exec(ua)){layoutversion=parseInt(match[2],10);}if(match=new RegExp('('+platforms.join('|')+')').exec(nav.platform.toLowerCase())){platform=translate(match[1],platformTranslations);}if(match=new RegExp('('+versionPrefixes.join('|')+')'+versionSuffix).exec(ua)){version=match[3];}if(name==='safari'&&version>400){version='2.0';}if(name==='opera'&&version>=9.8){match=ua.match(/\bversion\/([0-9\.]*)/);if(match&&match[1]){version=match[1];}else{version='10';}}if(name==='chrome'&&(match=ua.match(/\bopr\/([0-9\.]*)/))){if(match[1]){name='opera';version=match[1];}}if(layout==='trident'&&layoutversion>=7&&(match=ua.
match(/\brv[ :\/]([0-9\.]*)/))){if(match[1]){name='msie';version=match[1];}}if(match=ua.match(/\bsilk\/([0-9.\-_]*)/)){if(match[1]){name='silk';version=match[1];}}versionNumber=parseFloat(version,10)||0.0;return profileCache[key]={name:name,layout:layout,layoutVersion:layoutversion,platform:platform,version:version,versionBase:(version!==x?Math.floor(versionNumber).toString():x),versionNumber:versionNumber};},test:function(map,profile,exactMatchOnly){var conditions,dir,i,op,val,j,pieceVersion,pieceVal,compare;profile=$.isPlainObject(profile)?profile:$.client.profile();if(map.ltr&&map.rtl){dir=$('body').is('.rtl')?'rtl':'ltr';map=map[dir];}if(typeof map!=='object'||map[profile.name]===undefined){return!exactMatchOnly;}conditions=map[profile.name];if(conditions===false){return false;}if(conditions===null){return true;}for(i=0;i<conditions.length;i++){op=conditions[i][0];val=conditions[i][1];if(typeof val==='string'){pieceVersion=profile.version.toString().split('.');pieceVal=val.split(
'.');while(pieceVersion.length<pieceVal.length){pieceVersion.push('0');}while(pieceVal.length<pieceVersion.length){pieceVal.push('0');}compare=0;for(j=0;j<pieceVersion.length;j++){if(Number(pieceVersion[j])<Number(pieceVal[j])){compare=-1;break;}else if(Number(pieceVersion[j])>Number(pieceVal[j])){compare=1;break;}}if(!(eval(''+compare+op+'0'))){return false;}}else if(typeof val==='number'){if(!(eval('profile.versionNumber'+op+val))){return false;}}}return true;}};}(jQuery));},{},{});mw.loader.implement("jquery.mwExtension",function($,jQuery){(function($){$.extend({trimLeft:function(str){return str===null?'':str.toString().replace(/^\s+/,'');},trimRight:function(str){return str===null?'':str.toString().replace(/\s+$/,'');},ucFirst:function(str){return str.charAt(0).toUpperCase()+str.slice(1);},escapeRE:function(str){return str.replace(/([\\{}()|.?*+\-\^$\[\]])/g,'\\$1');},isDomElement:function(el){return!!el&&!!el.nodeType;},isEmpty:function(v){var key;if(v===''||v===0||v==='0'||v===
null||v===false||v===undefined){return true;}if(v.length===0){return true;}if(typeof v==='object'){for(key in v){return false;}return true;}return false;},compareArray:function(arrThis,arrAgainst){if(arrThis.length!==arrAgainst.length){return false;}for(var i=0;i<arrThis.length;i++){if($.isArray(arrThis[i])){if(!$.compareArray(arrThis[i],arrAgainst[i])){return false;}}else if(arrThis[i]!==arrAgainst[i]){return false;}}return true;},compareObject:function(objectA,objectB){var prop,type;if(typeof objectA===typeof objectB){if(typeof objectA==='object'){if(objectA===objectB){return true;}else{for(prop in objectA){if(prop in objectB){type=typeof objectA[prop];if(type===typeof objectB[prop]){switch(type){case'object':if(!$.compareObject(objectA[prop],objectB[prop])){return false;}break;case'function':if(objectA[prop].toString()!==objectB[prop].toString()){return false;}break;default:if(objectA[prop]!==objectB[prop]){return false;}break;}}else{return false;}}else{return false;}}for(prop in
objectB){if(!(prop in objectA)){return false;}}}}}else{return false;}return true;}});}(jQuery));},{},{});mw.loader.implement("mediawiki.legacy.ajax",function($,jQuery){(function(mw){function debug(text){if(!window.sajax_debug_mode){return false;}var b,m,e=document.getElementById('sajax_debug');if(!e){e=document.createElement('p');e.className='sajax_debug';e.id='sajax_debug';b=document.getElementsByTagName('body')[0];if(b.firstChild){b.insertBefore(e,b.firstChild);}else{b.appendChild(e);}}m=document.createElement('div');m.appendChild(document.createTextNode(text));e.appendChild(m);return true;}function createXhr(){debug('sajax_init_object() called..');var a;try{a=new XMLHttpRequest();}catch(xhrE){try{a=new window.ActiveXObject('Msxml2.XMLHTTP');}catch(msXmlE){try{a=new window.ActiveXObject('Microsoft.XMLHTTP');}catch(msXhrE){a=null;}}}if(!a){debug('Could not create connection object.');}return a;}function doAjaxRequest(func_name,args,target){var i,x,uri,post_data;uri=mw.util.wikiScript(
)+'?action=ajax';if(window.sajax_request_type==='GET'){if(uri.indexOf('?')===-1){uri=uri+'?rs='+encodeURIComponent(func_name);}else{uri=uri+'&rs='+encodeURIComponent(func_name);}for(i=0;i<args.length;i++){uri=uri+'&rsargs[]='+encodeURIComponent(args[i]);}post_data=null;}else{post_data='rs='+encodeURIComponent(func_name);for(i=0;i<args.length;i++){post_data=post_data+'&rsargs[]='+encodeURIComponent(args[i]);}}x=createXhr();if(!x){alert('AJAX not supported');return false;}try{x.open(window.sajax_request_type,uri,true);}catch(e){if(location.hostname==='localhost'){alert('Your browser blocks XMLHttpRequest to "localhost", try using a real hostname for development/testing.');}throw e;}if(window.sajax_request_type==='POST'){x.setRequestHeader('Method','POST '+uri+' HTTP/1.1');x.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}x.setRequestHeader('Pragma','cache=yes');x.setRequestHeader('Cache-Control','no-transform');x.onreadystatechange=function(){if(x.readyState!==4){
return;}debug('received ('+x.status+' '+x.statusText+') '+x.responseText);if(typeof target==='function'){target(x);}else if(typeof target==='object'){if(target.tagName==='INPUT'){if(x.status===200){target.value=x.responseText;}}else{if(x.status===200){target.innerHTML=x.responseText;}else{target.innerHTML='<div class="error">Error: '+x.status+' '+x.statusText+' ('+x.responseText+')</div>';}}}else{alert('Bad target for sajax_do_call: not a function or object: '+target);}};debug(func_name+' uri = '+uri+' / post = '+post_data);x.send(post_data);debug(func_name+' waiting..');return true;}function wfSupportsAjax(){var request=createXhr(),supportsAjax=request?true:false;request=undefined;return supportsAjax;}var deprecationNotice='Sajax is deprecated, use jQuery.ajax or mediawiki.api instead.';mw.log.deprecate(window,'sajax_debug_mode',false,deprecationNotice);mw.log.deprecate(window,'sajax_request_type','GET',deprecationNotice);mw.log.deprecate(window,'sajax_debug',debug,deprecationNotice);
mw.log.deprecate(window,'sajax_init_object',createXhr,deprecationNotice);mw.log.deprecate(window,'sajax_do_call',doAjaxRequest,deprecationNotice);mw.log.deprecate(window,'wfSupportsAjax',wfSupportsAjax,deprecationNotice);}(mediaWiki));},{},{});mw.loader.implement("mediawiki.legacy.wikibits",function($,jQuery){(function(mw,$){var msg,win=window,ua=navigator.userAgent.toLowerCase(),onloadFuncts=[];msg='Use feature detection or module jquery.client instead.';mw.log.deprecate(win,'clientPC',ua,msg);mw.log.deprecate(win,'is_gecko',false,msg);mw.log.deprecate(win,'is_chrome_mac',false,msg);mw.log.deprecate(win,'is_chrome',false,msg);mw.log.deprecate(win,'webkit_version',false,msg);mw.log.deprecate(win,'is_safari_win',false,msg);mw.log.deprecate(win,'is_safari',false,msg);mw.log.deprecate(win,'webkit_match',false,msg);mw.log.deprecate(win,'is_ff2',false,msg);mw.log.deprecate(win,'ff2_bugs',false,msg);mw.log.deprecate(win,'is_ff2_win',false,msg);mw.log.deprecate(win,'is_ff2_x11',false,msg);mw.
log.deprecate(win,'opera95_bugs',false,msg);mw.log.deprecate(win,'opera7_bugs',false,msg);mw.log.deprecate(win,'opera6_bugs',false,msg);mw.log.deprecate(win,'is_opera_95',false,msg);mw.log.deprecate(win,'is_opera_preseven',false,msg);mw.log.deprecate(win,'is_opera',false,msg);mw.log.deprecate(win,'ie6_bugs',false,msg);msg='Use jQuery instead.';mw.log.deprecate(win,'doneOnloadHook',undefined,msg);mw.log.deprecate(win,'onloadFuncts',[],msg);mw.log.deprecate(win,'runOnloadHook',$.noop,msg);mw.log.deprecate(win,'changeText',$.noop,msg);mw.log.deprecate(win,'killEvt',$.noop,msg);mw.log.deprecate(win,'addHandler',$.noop,msg);mw.log.deprecate(win,'hookEvent',$.noop,msg);mw.log.deprecate(win,'addClickHandler',$.noop,msg);mw.log.deprecate(win,'removeHandler',$.noop,msg);mw.log.deprecate(win,'getElementsByClassName',function(){return[];},msg);mw.log.deprecate(win,'getInnerText',function(){return'';},msg);mw.log.deprecate(win,'addOnloadHook',function(hookFunct){if(onloadFuncts){onloadFuncts.push(
hookFunct);}else{hookFunct();}},msg);$(win).on('load',function(){var i,functs;if(!onloadFuncts){return;}functs=onloadFuncts.slice();onloadFuncts=undefined;for(i=0;i<functs.length;i++){functs[i]();}});msg='Use jquery.checkboxShiftClick instead.';mw.log.deprecate(win,'checkboxes',[],msg);mw.log.deprecate(win,'lastCheckbox',null,msg);mw.log.deprecate(win,'setupCheckboxShiftClick',$.noop,msg);mw.log.deprecate(win,'addCheckboxClickHandlers',$.noop,msg);mw.log.deprecate(win,'checkboxClickHandler',$.noop,msg);mw.log.deprecate(win,'mwEditButtons',[],'Use mw.toolbar instead.');mw.log.deprecate(win,'mwCustomEditButtons',[],'Use mw.toolbar instead.');mw.log.deprecate(win,'injectSpinner',$.noop,'Use jquery.spinner instead.');mw.log.deprecate(win,'removeSpinner',$.noop,'Use jquery.spinner instead.');mw.log.deprecate(win,'escapeQuotes',$.noop,'Use mw.html instead.');mw.log.deprecate(win,'escapeQuotesHTML',$.noop,'Use mw.html instead.');mw.log.deprecate(win,'jsMsg',function(message){if(!arguments.
length||message===''||message===null){return true;}if(typeof message!=='object'){message=$.parseHTML(message);}mw.notify(message,{autoHide:true,tag:'legacy'});return true;},'Use mediawiki.notify instead.');msg='Use mediawiki.util instead.';mw.log.deprecate(win,'addPortletLink',mw.util.addPortletLink,msg);mw.log.deprecate(win,'appendCSS',mw.util.addCSS,msg);msg='Use jquery.accessKeyLabel instead.';mw.log.deprecate(win,'tooltipAccessKeyPrefix','alt-',msg);mw.log.deprecate(win,'tooltipAccessKeyRegexp',/\[(alt-)?(.)\]$/,msg);win.updateTooltipAccessKeys=function(){return mw.util.updateTooltipAccessKeys.apply(null,arguments);};win.loadedScripts={};win.importScript=function(page){var uri=mw.config.get('wgScript')+'?title='+mw.util.wikiUrlencode(page)+'&action=raw&ctype=text/javascript';return win.importScriptURI(uri);};win.importScriptURI=function(url){if(win.loadedScripts[url]){return null;}win.loadedScripts[url]=true;var s=document.createElement('script');s.setAttribute('src',url);s.
setAttribute('type','text/javascript');document.getElementsByTagName('head')[0].appendChild(s);return s;};win.importStylesheet=function(page){var uri=mw.config.get('wgScript')+'?title='+mw.util.wikiUrlencode(page)+'&action=raw&ctype=text/css';return win.importStylesheetURI(uri);};win.importStylesheetURI=function(url,media){var l=document.createElement('link');l.rel='stylesheet';l.href=url;if(media){l.media=media;}document.getElementsByTagName('head')[0].appendChild(l);return l;};}(mediaWiki,jQuery));},{},{});mw.loader.implement("mediawiki.notify",function($,jQuery){(function(mw){'use strict';mw.notify=function(message,options){return mw.loader.using('mediawiki.notification').then(function(){return mw.notification.notify(message,options);});};}(mediaWiki));},{},{});mw.loader.implement("mediawiki.util",function($,jQuery){(function(mw,$){'use strict';var util={init:function(){util.$content=(function(){var i,l,$node,selectors;selectors=['.mw-body-primary','.mw-body','#mw-content-text',
'body'];for(i=0,l=selectors.length;i<l;i++){$node=$(selectors[i]);if($node.length){return $node.first();}}return util.$content;}());},rawurlencode:function(str){str=String(str);return encodeURIComponent(str).replace(/!/g,'%21').replace(/'/g,'%27').replace(/\(/g,'%28').replace(/\)/g,'%29').replace(/\*/g,'%2A').replace(/~/g,'%7E');},wikiUrlencode:function(str){return util.rawurlencode(str).replace(/%20/g,'_').replace(/%3B/g,';').replace(/%40/g,'@').replace(/%24/g,'$').replace(/%21/g,'!').replace(/%2A/g,'*').replace(/%28/g,'(').replace(/%29/g,')').replace(/%2C/g,',').replace(/%2F/g,'/').replace(/%3A/g,':');},getUrl:function(str,params){var url=mw.config.get('wgArticlePath').replace('$1',util.wikiUrlencode(typeof str==='string'?str:mw.config.get('wgPageName')));if(params&&!$.isEmptyObject(params)){url+=(url.indexOf('?')!==-1?'&':'?')+$.param(params);}return url;},wikiScript:function(str){str=str||'index';if(str==='index'){return mw.config.get('wgScript');}else if(str==='load'){return mw.
config.get('wgLoadScript');}else{return mw.config.get('wgScriptPath')+'/'+str+mw.config.get('wgScriptExtension');}},addCSS:function(text){var s=mw.loader.addStyleTag(text);return s.sheet||s.styleSheet||s;},getParamValue:function(param,url){if(url===undefined){url=document.location.href;}var re=new RegExp('^[^#]*[&?]'+$.escapeRE(param)+'=([^&#]*)'),m=re.exec(url);if(m){return decodeURIComponent(m[1].replace(/\+/g,'%20'));}return null;},$content:null,addPortletLink:function(portlet,href,text,id,tooltip,accesskey,nextnode){var $item,$link,$portlet,$ul;if(arguments.length<3){return null;}$link=$('<a>').attr('href',href).text(text);if(tooltip){$link.attr('title',tooltip);}$portlet=$('#'+portlet);if($portlet.length===0){return null;}$ul=$portlet.find('ul').eq(0);if($ul.length===0){$ul=$('<ul>');if($portlet.find('div:first').length===0){$portlet.append($ul);}else{$portlet.find('div').eq(-1).append($ul);}}if($ul.length===0){return null;}$portlet.removeClass('emptyPortlet');if($portlet.hasClass
('vectorTabs')){$item=$link.wrap('<li><span></span></li>').parent().parent();}else{$item=$link.wrap('<li></li>').parent();}if(id){$item.attr('id',id);}if(accesskey){$link.attr('accesskey',accesskey);}if(tooltip){$link.attr('title',tooltip).updateTooltipAccessKeys();}if(nextnode){if(nextnode.nodeType||typeof nextnode==='string'){nextnode=$ul.find(nextnode);}else if(!nextnode.jquery||(nextnode.length&&nextnode[0].parentNode!==$ul[0])){$ul.append($item);return $item[0];}if(nextnode.length===1){nextnode.before($item);return $item[0];}}$ul.append($item);return $item[0];},validateEmail:function(mailtxt){var rfc5322Atext,rfc1034LdhStr,html5EmailRegexp;if(mailtxt===''){return null;}rfc5322Atext='a-z0-9!#$%&\'*+\\-/=?^_`{|}~';rfc1034LdhStr='a-z0-9\\-';html5EmailRegexp=new RegExp('^'+'['+rfc5322Atext+'\\.]+'+'@'+'['+rfc1034LdhStr+']+'+'(?:\\.['+rfc1034LdhStr+']+)*'+'$','i');return(mailtxt.match(html5EmailRegexp)!==null);},isIPv4Address:function(address,allowBlock){if(typeof address!=='string'){
return false;}var block=allowBlock?'(?:\\/(?:3[0-2]|[12]?\\d))?':'',RE_IP_BYTE='(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])',RE_IP_ADD='(?:'+RE_IP_BYTE+'\\.){3}'+RE_IP_BYTE;return address.search(new RegExp('^'+RE_IP_ADD+block+'$'))!==-1;},isIPv6Address:function(address,allowBlock){if(typeof address!=='string'){return false;}var block=allowBlock?'(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?':'',RE_IPV6_ADD='(?:'+':(?::|(?::'+'[0-9A-Fa-f]{1,4}'+'){1,7})'+'|'+'[0-9A-Fa-f]{1,4}'+'(?::'+'[0-9A-Fa-f]{1,4}'+'){0,6}::'+'|'+'[0-9A-Fa-f]{1,4}'+'(?::'+'[0-9A-Fa-f]{1,4}'+'){7}'+')';if(address.search(new RegExp('^'+RE_IPV6_ADD+block+'$'))!==-1){return true;}RE_IPV6_ADD='[0-9A-Fa-f]{1,4}'+'(?:::?'+'[0-9A-Fa-f]{1,4}'+'){1,6}';return address.search(new RegExp('^'+RE_IPV6_ADD+block+'$'))!==-1&&address.search(/::/)!==-1&&address.search(/::.*::/)===-1;}};mw.log.deprecate(util,'wikiGetlink',util.getUrl,'Use mw.util.getUrl instead.');mw.log.deprecate(util,'tooltipAccessKeyPrefix',$.fn.
updateTooltipAccessKeys.getAccessKeyPrefix(),'Use jquery.accessKeyLabel instead.');mw.log.deprecate(util,'tooltipAccessKeyRegexp',/\[(ctrl-)?(option-)?(alt-)?(shift-)?(esc-)?(.)\]$/,'Use jquery.accessKeyLabel instead.');mw.log.deprecate(util,'updateTooltipAccessKeys',function($nodes){if(!$nodes){if(document.querySelectorAll){$nodes=$(document.querySelectorAll('[accesskey]'));}else{$nodes=$('#column-one a, #mw-head a, #mw-panel a, #p-logo a, input, label, button');}}else if(!($nodes instanceof $)){$nodes=$($nodes);}$nodes.updateTooltipAccessKeys();},'Use jquery.accessKeyLabel instead.');mw.log.deprecate(util,'jsMessage',function(message){if(!arguments.length||message===''||message===null){return true;}if(typeof message!=='object'){message=$.parseHTML(message);}mw.notify(message,{autoHide:true,tag:'legacy'});return true;},'Use mw.notify instead.');mw.util=util;}(mediaWiki,jQuery));},{},{});mw.loader.implement("mediawiki.page.startup",function($,jQuery){(function(mw,$){mw.page={};$('html'
).addClass('client-js').removeClass('client-nojs');$(function(){mw.util.init();mw.hook('wikipage.content').fire($('#mw-content-text'));});}(mediaWiki,jQuery));},{},{});
/* cache key: embedders_eewiki-eewiki_:resourceloader:filter:minify-js:7:281ff6b46e03d9e7d3ebaed50461c7e1 */