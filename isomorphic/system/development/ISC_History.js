
/*

  SmartClient Ajax RIA system
  Version 6.5.1/LGPL Development Only (2008-06-30)

  Copyright 2000-2007 Isomorphic Software, Inc. All rights reserved.
  "SmartClient" is a trademark of Isomorphic Software, Inc.

  LICENSE NOTICE
     INSTALLATION OR USE OF THIS SOFTWARE INDICATES YOUR ACCEPTANCE OF THE
     SOFTWARE EVALUATION LICENSE AGREEMENT. If you have received this file
     without an Isomorphic Software license file, please see:

         http://www.isomorphic.com/licenses/isc_eval_license_050316.html

     You are not required to accept this agreement, however, nothing else
     grants you the right to copy or use this software. Unauthorized copying
     and use of this software is a violation of international copyright law.

  EVALUATION ONLY
     This software is provided for limited evaluation purposes only. You must
     acquire a deployment license from Isomorphic Software in order to use
     the SmartClient system, or any portion thereof, in any non-evaluation
     application, including internal or non-commercial applications.

  PROPRIETARY & PROTECTED MATERIAL
     This software contains proprietary materials that are protected by
     contract and intellectual property law. YOU ARE EXPRESSLY PROHIBITED
     FROM ATTEMPTING TO REVERSE ENGINEER THIS SOFTWARE OR MODIFY THIS
     SOFTWARE FOR HUMAN READABILITY.

  CONTACT ISOMORPHIC
     For more information regarding license rights and restrictions, or to
     report possible license violations, please contact Isomorphic Software
     by email (licensing@isomorphic.com) or web (www.isomorphic.com).

*/

var isc = window.isc ? window.isc : {};if(window.isc&&!window.isc.module_History){isc.module_History=1;isc._moduleStart=isc._History_start=(isc.timestamp?isc.timestamp():new Date().getTime());if(isc._moduleEnd&&(!isc.Log||(isc.Log && isc.Log.logIsDebugEnabled('loadTime')))){isc._pTM={ message:'History load/parse time: ' + (isc._moduleStart-isc._moduleEnd) + 'ms', category:'loadTime'};
if(isc.Log && isc.Log.logDebug)isc.Log.logDebug(isc._pTM.message,'loadTime')
else if(isc._preLog)isc._preLog[isc._preLog.length]=isc._pTM
else isc._preLog=[isc._pTM]}var isc=window.isc?window.isc:{};isc.$d=new Date().getTime();isc.version="6.5.1/LGPL Development Only";isc.versionNumber="6.5.1";isc.buildDate="2008-06-30";isc.expirationDate="${expiration}";isc.licenseType="LGPL";isc.licenseCompany="Tumi";isc.licenseSerialNumber="7bd104107f4a75633f26b8ae409c3708";isc.licensingPage="http://smartclient.com/licensing";isc.$41r={SCServer:{present:"false",name:"SmartClient Server",serverOnly:true},Drawing:{present:"false",name:"Drawing Module"},PluginBridges:{present:"false",name:"PluginBridges Module"},RichTextEditor:{present:"false",name:"RichTextEditor Module"},Calendar:{present:"false",name:"Calendar Module"},Analytics:{present:"false",name:"Analytics Module"},NetworkPerformance:{present:"false",name:"Network Performance Module"},FileLoader:{present:"false",name:"Network Performance Module"},RealtimeMessaging:{present:"false",name:"RealtimeMessaging Module"}};isc.canonicalizeModules=function(_1){if(!_1)return null;if(isc.isA.String(_1)){if(_1.indexOf(",")!=-1)_1=_1.split(",");else _1=[_1]}
return _1};isc.hasOptionalModules=function(_1){if(!_1)return true;_1=isc.canonicalizeModules(_1);for(var i=0;i<_1.length;i++)if(!isc.hasOptionalModule(_1[i]))return false;return true};isc.getMissingModules=function(_1){var _2=[];_1=isc.canonicalizeModules(_1);for(var i=0;i<_1.length;i++){var _4=_1[i];if(!isc.hasOptionalModule(_4))_2.add(isc.$41r[_4])}
return _2};isc.hasOptionalModule=function(_1){var v=isc.$41r[_1];if(!v){if(isc.Log)isc.Log.logWarn("isc.hasOptionalModule - unknown module: "+_1);return false}
return v.present=="true"||v.present.charAt(0)=="$"};isc.$a=window.isc_useSimpleNames;if(isc.$a==null)isc.$a=true;if(window.OpenAjax){isc.$b=isc.versionNumber.replace(/[a-zA-Z_]+/,".0");OpenAjax.registerLibrary("SmartClient","http://smartclient.com/SmartClient",isc.$b,{namespacedMode:!isc.$a,iscVersion:isc.version,buildDate:isc.buildDate,licenseType:isc.licenseType,licenseCompany:isc.licenseCompany,licenseSerialNumber:isc.licenseSerialNumber});OpenAjax.registerGlobals("SmartClient",["isc"])}
isc.$e=window.isc_useLongDOMIDs;isc.$f="isc.";isc.addGlobal=function(_1,_2){if(_1.indexOf(isc.$f)==0)_1=_1.substring(4);isc[_1]=_2;if(isc.$a)window[_1]=_2}
isc.onLine=true;isc.isOffline=function(){return!isc.onLine};isc.goOffline=function(){isc.onLine=false};isc.goOnline=function(){isc.onLine=true};if(window.addEventListener){window.addEventListener("online",isc.goOnline,false);window.addEventListener("offline",isc.goOffline,false)}
isc.addGlobal("Browser",{isSupported:false});isc.Browser.isOpera=(navigator.appName=="Opera"||navigator.userAgent.indexOf("Opera")!=-1);isc.Browser.isNS=(navigator.appName=="Netscape"&&!isc.Browser.isOpera);isc.Browser.isIE=(navigator.appName=="Microsoft Internet Explorer"&&!isc.Browser.isOpera);isc.Browser.isMSN=(isc.Browser.isIE&&navigator.userAgent.indexOf("MSN")!=-1);isc.Browser.minorVersion=parseFloat(isc.Browser.isIE?navigator.appVersion.substring(navigator.appVersion.indexOf("MSIE")+5):navigator.appVersion);isc.Browser.version=parseInt(isc.Browser.minorVersion);isc.Browser.isIE6=isc.Browser.isIE&&isc.Browser.version<=6;isc.Browser.isMoz=navigator.userAgent.indexOf("Gecko/")!=-1;isc.Browser.isCamino=(isc.Browser.isMoz&&navigator.userAgent.indexOf("Camino/")!=-1);if(isc.Browser.isCamino){isc.Browser.caminoVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Camino/")+7)}
isc.Browser.isFirefox=(isc.Browser.isMoz&&navigator.userAgent.indexOf("Firefox/")!=-1);if(isc.Browser.isFirefox){isc.Browser.firefoxVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox/")+8)}
if(isc.Browser.isMoz){isc.Browser.$g=navigator.userAgent.indexOf("Gecko/")+6;isc.Browser.geckoVersion=parseInt(navigator.userAgent.substring(isc.Browser.$g,isc.Browser.$g+8));if(isc.Browser.isFirefox){if(isc.Browser.firefoxVersion.match(/^1\.0/))isc.Browser.geckoVersion=20050915;else if(isc.Browser.firefoxVersion.match(/^2\.0/))isc.Browser.geckoVersion=20071108}}
isc.Browser.isStrict=document.compatMode=="CSS1Compat";if(isc.Browser.isStrict&&isc.Browser.isMoz){isc.Browser.$51p=document.doctype.publicId;isc.Browser.$51q=document.doctype.systemId;isc.Browser.isTransitional=isc.Browser.$51p.indexOf("Transitional")!=-1||isc.Browser.$51p.indexOf("Frameset")!=-1}
isc.Browser.isBorderBox=isc.Browser.isIE&&!isc.Browser.isStrict;isc.Browser.isAIR=(navigator.userAgent.indexOf("AdobeAIR")!=-1);isc.Browser.AIRVersion=(isc.Browser.isAIR?navigator.userAgent.substring(navigator.userAgent.indexOf("AdobeAir/")+9):null);isc.Browser.isSafari=navigator.userAgent.indexOf("Safari")!=-1||isc.Browser.isAIR;if(isc.Browser.isSafari){if(isc.Browser.isAIR){isc.Browser.safariVersion=530}else{isc.Browser.rawSafariVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Safari/")+7)
isc.Browser.safariVersion=(function(){var _1=isc.Browser.rawSafariVersion,_2=_1.indexOf(".");if(_2==-1)return parseInt(_1);var _3=_1.substring(0,_2+1),_4;while(_2!=-1){_2+=1;_4=_1.indexOf(".",_2);_3+=_1.substring(_2,(_4==-1?_1.length:_4));_2=_4}
return parseFloat(_3)})()}}
isc.Browser.isWin=navigator.platform.toLowerCase().indexOf("win")>-1;isc.Browser.isWin2k=navigator.userAgent.match(/NT 5.01?/)!=null;isc.Browser.isMac=navigator.platform.toLowerCase().indexOf("mac")>-1;isc.Browser.isUnix=(!isc.Browser.isMac&&!isc.Browser.isWin);isc.Browser.lineFeed=(isc.Browser.isWin?"\r\n":"\r");isc.Browser.$h=false;isc.Browser.isDOM=(isc.Browser.isMoz||isc.Browser.isOpera||isc.Browser.isSafari||(isc.Browser.isIE&&isc.Browser.version>=5));isc.Browser.isSupported=((isc.Browser.isIE&&isc.Browser.minorVersion>=5.5&&isc.Browser.isWin)||isc.Browser.isMoz||isc.Browser.isOpera||isc.Browser.isSafari||isc.Browser.isAIR);if(isc.addProperties==null){isc.addGlobal("addProperties",function(_1,_2){for(var _3 in _2)
_1[_3]=_2[_3];return _1})}
isc.addGlobal("defineStandaloneClass",function(_1,_2){if(isc[_1])return;isc.addGlobal(_1,_2);isc.addProperties(_2,{$i:_1,fireSimpleCallback:function(_3){_3.method.apply(_3.target?_3.target:window,_3.args?_3.args:[])},logMessage:function(_3,_4,_5){if(isc.Log){isc.Log.logMessage(_3,_4,_5);return}
if(!isc.$j)isc.$j=[];isc.$j[isc.$j.length]={priority:_3,message:_4,category:_5,timestamp:new Date()}},logWarn:function(_3){this.logMessage(3,_3,this.$i)},logInfo:function(_3){this.logMessage(4,_3,this.$i)},logDebug:function(_3){this.logMessage(5,_3,this.$i)},isA:{String:function(_3){if(_3==null)return false;if(_3.constructor&&_3.constructor.$k!=null){return _3.constructor.$k==4}
return typeof _3=="string"}}});_2.isAn=_2.isA;return _2});isc.defineStandaloneClass("SA_Page",{$l:false,$m:[],isLoaded:function(){return this.$l},onLoad:function(_1,_2,_3){this.$m.push({method:_1,target:_2,args:_3});if(!this.$n){this.$n=true;if(isc.Browser.isIE||isc.Browser.isOpera){window.attachEvent("onload",function(){isc.SA_Page.$o()})}else{window.addEventListener("load",function(){isc.SA_Page.$o()},true)}}},$o:function(){if(!window.isc||this.$l)return;this.$l=true;for(var i=0;i<this.$m.length;i++){var _2=this.$m[i];this.fireSimpleCallback(_2)}
delete this.$m}});isc.SA_Page.onLoad(function(){this.$l=true},isc.SA_Page);isc.defineStandaloneClass("History",{registerCallback:function(_1){this.$p=_1;if(isc.Browser.isMoz||isc.Browser.isOpera)this.$q()},getCurrentHistoryId:function(){var _1=this.$r(location.href);if(_1=="init")return null;return _1},getHistoryData:function(_1){return this.historyState?this.historySate.data[_1]:null},setHistoryTitle:function(_1){this.historyTitle=_1},addHistoryEntry:function(_1,_2,_3){this.logDebug("addHistoryEntry: "+_1);if(isc.Browser.isSafari){return}
if(!isc.SA_Page.isLoaded()){this.logWarn("You must wait until the page has loaded before calling "+"isc.History.addHistoryEntry()");return}
var _4=this.$r(location.href);if(_4==_1){this.historyState.data[_1]=_3;return}
while(this.historyState.stack.length){var _5=this.historyState.stack.pop();if(_5==_4){this.historyState.stack.push(_5);break}
delete this.historyState.data[_5]}
this.historyState.stack.add(_1);this.historyState.data[_1]=_3;this.$s();if(isc.Browser.isIE){if(_1!=null&&document.getElementById(_1)!=null){this.logWarn("Warning - attempt to add synthetic history entry with id that conflicts"+" with an existing DOM element node ID - this is known to break in IE")}
if(_4==null){var _6=location.href;var _7=document.getElementsByTagName("title");if(_7.length)_6=_7[0].innerHTML;this.$t("init",_6)}
this.$t(_1,_2)}else{location.href=this.$u(location.href,_1)}
this.$v=location.href},$t:function(_1,_2){this.$w=true;var _3=!this.isA.String(_1)?_1:_1.replace(/\\/g,"\\\\").replace(/\"/g,"\\\"").replace(/\t/g,"\\t").replace(/\r/g,"\\r").replace(/\n/g,"\\n");var _4="<HTML><HEAD><TITLE>"+(_2!=null?_2:this.historyTitle!=null?this.historyTitle:_1)+"</TITLE></HEAD><BODY><SCRIPT>top.isc.History.historyCallback(window,\""+_3+"\");</SCRIPT></BODY></HTML>";var _5=this.$x.contentWindow;_5.document.open();_5.document.write(_4);_5.document.close()},haveHistoryState:function(){if(isc.Browser.isIE&&!isc.SA_Page.isLoaded()){this.logWarn("haveHistoryState() called before pageLoad - this always returns false"+" in IE because state information is not available before pageLoad")}
return this.historyState&&this.historyState.stack[0]!=null},$y:function(){return window.isomorphicDir?window.isomorphicDir:"../isomorphic/"},$z:function(){this.logInfo("History initializing");if(this.$0)return;this.$0=true;if(isc.Browser.isSafari)return;var _1="<form style='position:absolute;top:-1000px' id='isc_historyForm'>"+"<textarea id='isc_historyField' style='display:none'></textarea></form>";document.write(_1);if(isc.Browser.isIE){var _2="<iframe id='isc_historyFrame' src='"+this.getBlankFrameURL()+"' style='position:absolute;visibility:hidden;top:-1000px'></iframe>";document.write(_2);this.$x=document.getElementById('isc_historyFrame');document.write("<span id='isc_history_buffer_marker' style='display:none'></span>")}
if(isc.Browser.isIE){isc.SA_Page.onLoad(function(){this.$1()},this)}else if(isc.Browser.isMoz||isc.Browser.isOpera){this.$1()}},getBlankFrameURL:function(){if(isc.Page)return isc.Page.getBlankFrameURL();if(isc.Browser.isIE&&("https:"==window.location.protocol||document.domain!=location.hostname))
{var _1=window.location.href;if(_1.charAt(_1.length-1)!="/"){_1=_1.substring(0,_1.lastIndexOf("/")+1)}
_1+=(window.isomorphicDir||"../isomorphic/");_1+="system/helpers/empty.html";return _1}
return"about:blank"},$2:function(){var _1=document.getElementById("isc_historyField");return _1?_1.value:null},$3:function(_1){var _2=document.getElementById("isc_historyField");if(_2)_2.value=_1},$1:function(){var _1=this.$2();if(_1){_1=new Function("return "+_1)()}
if(!_1)_1={stack:[],data:[]};this.historyState=_1;this.logInfo("History init complete");this.$v=location.href;this.$4=window.setInterval("isc.History.$5()",this.$6);if(isc.Browser.isMoz||isc.Browser.isOpera){isc.SA_Page.onLoad(this.$q,this)}},$q:function(){if(this.$7)return;if(this.$p&&isc.SA_Page.isLoaded()){this.$7=true;var _1=this.$r(location.href);if(this.haveHistoryState())this.$8(_1)}},$u:function(_1,_2){var _3=_1.match(/([^#]*).*/);return _3[1]+"#"+encodeURIComponent(_2)},$r:function(_1){var _2=location.href.match(/([^#]*)#(.*)/);return _2?decodeURIComponent(_2[2]):null},$9:function(_1,_2){return _1+"?ba="+encodeURIComponent(_2)},$aa:function(_1){var _2=_1.match(/.*\?ba=(.*)/);return _2?decodeURIComponent(_2[1]):null},$6:100,$s:function(){if(isc.Comm){this.$3(isc.Comm.serialize(this.historyState))}},$5:function(){if(location.href!=this.$v){var _1=this.$r(location.href);this.$8(_1)}
this.$v=location.href},historyCallback:function(_1,_2){if(_2==null)_2=this.$aa(_1.location.href);var _3=this.$u(location.href,_2);if(isc.SA_Page.isLoaded()){location.href=_3;this.$v=_3}else{isc.SA_Page.onLoad(function(){location.href=this.$u(location.href,_2);this.$v=_3},this)}
if(this.$w){this.$w=false;return}
if(isc.SA_Page.isLoaded()){this.$8(_2)}else{isc.SA_Page.onLoad(function(){this.$8(_2)},this)}},$8:function(_1){this.$ab=_1;if(!this.$p){this.logWarn("ready to fire history callback, but no callback registered."+"Please call isc.History.registerCallback() before pageLoad."+" If you can't register your callback before pageLoad, you"+" can call isc.History.getCurrentHistoryId() to get the ID"+" when you're ready.");return}
if(!this.haveHistoryState())return;if(_1=="init")_1=null;var _2=this.$p;this.logDebug("history callback: "+_1);if(isc.Class&&this.isA.String(_2)){isc.Class.fireCallback(_2,["id","data"],[_1,this.historyState.data[_1]])}else{_2=isc.addProperties({},_2);_2.args=[_1,this.historyState.data[_1]];this.fireSimpleCallback(_2)}}});isc.History.$z();isc._moduleEnd=isc._History_end=(isc.timestamp?isc.timestamp():new Date().getTime());if(isc.Log&&isc.Log.logIsInfoEnabled('loadTime'))isc.Log.logInfo('History module init time: ' + (isc._moduleEnd-isc._moduleStart) + 'ms','loadTime');}else{if(window.isc && isc.Log && isc.Log.logWarn)isc.Log.logWarn("Duplicate load of module 'History'.");}

/*

  SmartClient Ajax RIA system
  Version 6.5.1/LGPL Development Only (2008-06-30)

  Copyright 2000-2007 Isomorphic Software, Inc. All rights reserved.
  "SmartClient" is a trademark of Isomorphic Software, Inc.

  LICENSE NOTICE
     INSTALLATION OR USE OF THIS SOFTWARE INDICATES YOUR ACCEPTANCE OF THE
     SOFTWARE EVALUATION LICENSE AGREEMENT. If you have received this file
     without an Isomorphic Software license file, please see:

         http://www.isomorphic.com/licenses/isc_eval_license_050316.html

     You are not required to accept this agreement, however, nothing else
     grants you the right to copy or use this software. Unauthorized copying
     and use of this software is a violation of international copyright law.

  EVALUATION ONLY
     This software is provided for limited evaluation purposes only. You must
     acquire a deployment license from Isomorphic Software in order to use
     the SmartClient system, or any portion thereof, in any non-evaluation
     application, including internal or non-commercial applications.

  PROPRIETARY & PROTECTED MATERIAL
     This software contains proprietary materials that are protected by
     contract and intellectual property law. YOU ARE EXPRESSLY PROHIBITED
     FROM ATTEMPTING TO REVERSE ENGINEER THIS SOFTWARE OR MODIFY THIS
     SOFTWARE FOR HUMAN READABILITY.

  CONTACT ISOMORPHIC
     For more information regarding license rights and restrictions, or to
     report possible license violations, please contact Isomorphic Software
     by email (licensing@isomorphic.com) or web (www.isomorphic.com).

*/
