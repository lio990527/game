function get(id){
	return document.getElementById(id);
}

function getInfo(){
	var s = "";
	s += "网页可见区域宽："+ document.body.clientWidth;
	s += " \n网页可见区域高："+ document.body.clientHeight;
	s += " \n网页可见区域宽："+ document.body.offsetWidth + " (包括边线和滚动条的宽)";
	s += " \n网页可见区域高："+ document.body.offsetHeight + " (包括边线的宽)";
	s += " \n网页正文全文宽："+ document.body.scrollWidth;
	s += " \n网页正文全文高："+ document.body.scrollHeight;
	s += " \n网页被卷去的高(ff)："+ document.body.scrollTop;
	s += " \n网页被卷去的高(ie)："+ document.documentElement.scrollTop;
	s += " \n网页被卷去的左："+ document.body.scrollLeft;
	s += " \n网页正文部分上："+ window.screenTop;
	s += " \n网页正文部分左："+ window.screenLeft;
	s += " \n屏幕分辨率的高："+ window.screen.height;
	s += " \n屏幕分辨率的宽："+ window.screen.width;
	s += " \n屏幕可用工作区高度："+ window.screen.availHeight;
	s += " \n屏幕可用工作区宽度："+ window.screen.availWidth;
	s += " \n你的屏幕设置是 "+ window.screen.colorDepth +" 位彩色";
	s += " \n你的屏幕设置 "+ window.screen.deviceXDPI +" 像素/英寸";
	alert (s);
}

function setCookie(cname,cvalue,exdays = 0)
{
	var expires = '';
	if(exdays > 0){
		var d = new Date();
		d.setTime(d.getTime()+(exdays*24*60*60*1000));
		expires = "expires="+d.toGMTString();
	}
	document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname)
{
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) 
	{
		var c = ca[i].trim();
		if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	}
	return "";
}