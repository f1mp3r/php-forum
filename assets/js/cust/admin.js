$(function() {
	$('.delete-confirm').click(function () {
		var conf = confirm('Are you sure you want to delete this?');
		if (conf) {
			return true;
		}
		return false;
	});
});