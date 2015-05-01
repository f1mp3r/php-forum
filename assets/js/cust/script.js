function search() {
	var query = $('#search-input').val();
	if (query.trim().length) {
		var href = $('#search-form').data('action') + query;
		location.href = href;
	}
}