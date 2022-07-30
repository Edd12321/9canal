// set cookie
function
setCookie(name, value, days)
{
	let day = new Date();
	day.setDate(day.getDate() + days);

	let finalv = escape(value) + ((days == null) ? "" : "; expires=" + day.toUTCString());
	document.cookie = name + '=' + finalv + "; Path=/";
}

// get cookie
function
getCookie(name)
{
	let cookies = document.cookie.split(';');
	let oo = cookies.length;
	
	for (let i = 0; i < oo; ++i) {
		let a = cookies[i].substr(0, cookies[i].indexOf("="));
		let b = cookies[i].substr(cookies[i].indexOf("=") + 1);
		
		a = a.replce(/^\s+|\s+$/g, "");
		if (a == name) {
			return unescape(b);
		}
	}
}

// delete cookie
function
deleteCookie(name)
{
	document.cookie = name +"=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}

// set theme
function
whichTheme()
{
	var theme = document.getElementById("theme").value;
	deleteCookie(theme);
	setCookie("theme", theme, 9999);
}
