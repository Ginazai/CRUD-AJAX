$(document).ready(function() {
	$('#addEdu').click(function(e) {
		e.preventDefault();
		var j = $('.edu_field').length;
		console.log(j);
		if (j >= 9) {
			alert("Maximum number of institutions exceeded");
		} else {
			if (j < 9) {
				$('#edu_fields').append('<div class="edu_field row"> \
				<div class="col-6"><label for="edu_year'+ j +'" class="form-label">Year:</label><input class="form-control" id="edu_year'+ j +'" maxlength="4" type="text" name="edu_year'+ j +'"></div>\
				<div class="col-6"><label for="edu_school'+ j +'" class="form-label">Institution:</label><input class="school form-control" type="text" name="edu_school' + j + '" rows="1" cols="60"></div>\
				<div class="col-12"><button class="edu_rm form-control btn btn-sm btn-danger mt-3" type="button"><span class="fas fa-trash"></span></button></div></div>');
			}

			$('.edu_rm').click(function() {
				console.log("removed clicked");
				$(this).parent().parent().remove();
			});
			
			$('.school').autocomplete({
				source: "school.php" 
			}); 
		}
	});
});