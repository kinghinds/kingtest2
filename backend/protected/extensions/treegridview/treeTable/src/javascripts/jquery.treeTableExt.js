$.fn.collapseAll = function() {
	$(this).find("tr").removeClass("expanded").addClass("collapsed").each(
			function() {
				$(this).collapse();
			});
};

$.fn.expandAll = function() {
	$(this).find("tr").removeClass("collapsed").addClass("expanded").each(
			function() {
				$(this).expand();
			});
};