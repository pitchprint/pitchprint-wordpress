<?php

namespace pitchprint\functions\admin;

function actions() {
	add_menu_page(
	    'PitchPrint Settings', 
	    'PitchPrint', 
	    'manage_options', 
	    'pitchprint', 
	    'pitchprint\\functions\\admin\\admin_page', 
	    'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB2ZXJzaW9uPSIxLjEiICAgdmlld0JveD0iMCAwIDUuMjkxNjY2NSA1LjI5MTY2NiIgICBoZWlnaHQ9IjE5Ljk5OTk5OCIgICB3aWR0aD0iMjAiPiAgPHBhdGggICAgIGQ9Ik0gMS40MDQ0MDczLC0xLjMxMTMwMjJlLTcgMS4zNTU4MzEzLDAuNjk0NTQ4ODcgMi40MjUwNjMzLDEuNDQ5NTc3OSAyLjQ2MjE0MTMsMC45MTkzNTY4NyBIIDIuOTcyNDkgYyAwLjMwNjQwOTcsMCAwLjUyNzA0ODcsMC4wNjI3NyAwLjY2MTg2NDcsMC4xODgyMjUwMyAwLjEzNDc1NDcsMC4xMjU0OTUgMC4xOTI5NzcsMC4zMTk3MTUgMC4xNzQ1OTA2LDAuNTgyNjk3IC0wLjAxODIyNSwwLjI2MDYwNSAtMC4xMTc3NTk5LDAuNDU5MTE1IC0wLjI5ODU5NTUsMC41OTU0MjcgLTAuMTgwODk1NiwwLjEzNjM0MyAtMC40NDM4MzY3LDAuMjA0NDg1IC0wLjc4ODg2NzUsMC4yMDQ0ODUgSCAyLjM1MjMwNDEgbCAwLjA0Mjc0NCwtMC42MTE1NjUgLTEuMTc0NzU4NCwwLjc1NDQxNiAtMC4xODU5MjU3LDIuNjU4NjI0IGggMS4xMjIwMzU4IGwgMC4xMzE2MDEzLC0xLjg4MjExNyBoIDAuNDgxNDAxMiBjIDAuNjU2MzE1MiwwIDEuMTcyOTYxMywtMC4xNTA3OTkgMS41NTAwMTQ4LC0wLjQ1MjQ1MSBDIDQuNjk2NDAwOSwyLjY1NTQ5MzkgNC45MDQ4Mzk3LDIuMjE5OTkxOSA0Ljk0NDY1NDIsMS42NTA0OTI5IDQuOTgyOTYxOSwxLjEwMjc3MjkgNC44NDQ2MzE0LDAuNjkwNzU0ODcgNC41Mjk3NzI3LDAuNDE0NDI4ODcgNC4yMTQ4NTY2LDAuMTM4MTcxODcgMy43MzY1MjQ0LC0xLjMxMTMwMjJlLTcgMy4wOTQ2ODY1LC0xLjMxMTMwMjJlLTcgWiBNIDAuNDQzNDU0MzMsMC41OTUzODU4NyAwLjI5NTMwNTgzLDIuNzE0MzAyOSAxLjg4NjkxNzMsMS42NTUyOTY5IFoiICAgICBzdHlsZT0iZmlsbDpub25lIiAvPjwvc3ZnPg=='
	);
}

function admin_page() {
	if (!class_exists('WooCommerce')) {
		echo ('<h3>This plugin depends on WooCommerce plugin. Kindly install <a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce here!</a></h3>');
		exit();
	}
	settings_errors();

	echo '<form method="post" action="options.php"><div class="wrap">';
		settings_fields('pitchprint');
		do_settings_sections('pitchprint');
		submit_button();
	echo '</div></form>';
}