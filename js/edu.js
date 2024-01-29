$(document).ready(function() {
	$('#addEdu').click(function(e) {
		e.preventDefault();
		var j = $('.edu_field').length;
		console.log(j);
		if (j >= 9) {
			alert("Maximum number of institutions exceeded");
		} else {
			if (j < 9) {
				$('#edu_fields').append('<div class="edu_field"> \
				<p>Year: <input  maxlength="4" type="text" name="edu_year'+ j +'"><input class="edu_rm" type="button" value="-"></p> \
				<input class="school" type="text" name="edu_school' + j + '" rows="1" cols="60"></div>');
			}

			if (j > 0) {
				$(".edu_rm").click(function() {
					$(this).parent().parent().remove();
				});
			}

			$('.school').autocomplete({
				source: "school.php" 
			}); 
		}
	});
});