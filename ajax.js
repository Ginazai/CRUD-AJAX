$(document).ready(function () {
	var name = $('.school').val();
	$.ajax({
		url: "school.php",
		type: "post",
		data: {term:name},
		dataType: 'json',
		succes: function() {
			$('.school').autocomplete({
			source: "school.php" 
		}); 
		}

	});
});