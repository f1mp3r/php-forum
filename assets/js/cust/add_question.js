$(function() {
	$('#board').selectpicker();

	// $('#tags').tagsinput({
	// 	itemValue: 'id',
	// 	itemText: 'tag',
	// 	typeahead: {
	// 		source: function(query) {
	// 			return $.get('rest/tags/' + query);
	// 		},
	// 	},
	// 	freeInput: true
	// });

	// $('#tags').on('itemAdded', function (e) {
	// 	var input = $('.bootstrap-tagsinput>input');
	// 	input.val('');
	// 	if ($('.typeahead dropdown-menu').is(':visible')) {
	// 		$('.typeahead dropdown-menu').css('display', 'none');
	// 	}
	// 	$('#tags').tagsinput('refresh');
	// });
});