// set cookie
function setCookie(name, value, days) {
	let day = new Date();
	day.setDate(day.getDate() + days);

	let finalv = escape(value) + ((days == null) ? "" : "; expires=" + day.toUTCString());
	document.cookie = name + '=' + finalv;
}

// get cookie
function getCookie(name) {
	let cookies = document.cookie.split(';');
	let oo = cookies.length;

	for (let i = 0; i < cookies; ++i) {
		let a = cookies[i].substr(0, cookies[i].indexOf("="));
		let b = cookies[i].substr(cookies[i].indexOf("=") + 1);

		a = a.replce(/^\s+|\s+$/g, "");
		if (a == name) {
			return unescape(b);
		}
	}
}

// set theme
function whichTheme() {
	var theme = document.getElementById("theme").value;
	setCookie("theme", theme, 9999);
}
