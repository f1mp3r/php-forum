function search() {
	var query = $('#search-input').val();
	var href = $('#search-form').data('action') + query;
	location.href = href;
}