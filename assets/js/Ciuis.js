var App = (function () {
	'use strict';
	var config = {
		assetsPath: 'assets',
		imgPath: 'img',
		jsPath: 'js',
		libsPath: 'lib',
		leftSidebarSlideSpeed: 200,
		leftSidebarToggleSpeed: 300,
		enableSwipe: true,
		swipeTreshold: 100,
		scrollTop: true,
		openRightSidebarClass: 'open-right-sidebar',
		closeRsOnClickOutside: true,
		removeLeftSidebarClass: 'ciuis-body-nosidebar-left',
		transitionClass: 'ciuis-body-animate',
		openSidebarDelay: 400
	};
	return {
		init: function (options) {
			$.extend(config, options);
		}
	};
})();
