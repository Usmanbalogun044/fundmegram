(function ($) {
	"use strict";
	let btnSwitchTheme = document.querySelector('#switchTheme');

	if (btnSwitchTheme) {
		btnSwitchTheme.addEventListener('click', event => {
			let theme = document.getElementById("theme-asset");
			var currentTheme = theme.getAttribute("data-bs-theme");
			let dark = 'dark';
			let light = 'light';

			if (currentTheme == 'dark') {
				theme.setAttribute('data-bs-theme', light);
				btnSwitchTheme.innerHTML = '<i class="bi-moon-stars me-lg-0 me-2"></i> <span class="d-lg-none">' + darkMode + '</span>';
				Cookies.remove('theme');
				Cookies.set('theme', light, { expires: 365 });
			} else {
				theme.setAttribute('data-bs-theme', dark);
				btnSwitchTheme.innerHTML = '<i class="bi-sun me-lg-0 me-2"></i> <span class="d-lg-none">' + lightMode + '</span>';
				Cookies.remove('theme');
				Cookies.set('theme', dark, { expires: 365 });
			}
		});
	}
})(jQuery)