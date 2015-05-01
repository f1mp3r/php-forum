$(function() {
	$('#board').selectpicker();

	var populate = [];
	var prefix = '';
	var tags = [];

	if ($('input[name="tags"]').length == 1) {
		var value = $('input[name="tags"]').val();
		var separated = value.split(', ');
		for (var i = 0; i < separated.length; i++) {
			if (separated[i].trim().length == 0) {
				continue;
			}
			populate.push(separated[i].trim());
		}
		tags = tags.concat(populate);
		if ($('#prefix').length) {
			prefix = '../';
		}
	}
	
	$.getJSON(prefix + "rest/tags/tag", function(json){
		$('#tags').tags({
			tagSize: "lg",
			promptText: 'Question tags',
			suggestions: json,
			afterAddingTag: function(tag) {
				tags.push(tag);
				updateTags(tags);
			},
			afterDeletingTag: function(tag) {
				tags.splice(tags.indexOf(tag), 1);
				updateTags(tags);
			},
			caseInsensitive: true,
			tagData: populate
		});
	});
});

function updateTags(tags) {
	var string = '';
	for (var i = 0; i < tags.length; i++) {
		string += tags[i] + ',';
	}

	string = string.substr(0, string.length - 1);
	$('input[name="tags"').val(string);
}