var j = 1;
$(document).ready(function() {
	$('#addEdu').click(function(action) {
	action.preventDefault();
	if (j >= 9) {
		alert("Maximum of nine schools exceeded");
	} else {
		if (j < 9) {
			var edu_inst = "$('#edu" + j + "').remove(); j -= 1;";
			$('#edu_fields').append('<div id="edu' + j + '"> \
			<p>Year: <input  maxlength="4" type="text" name="edu_year'+ j +'"><input type="button" value="-" onclick="' + edu_inst + '"></p> \
			<input class="school" type="text" name="edu_school' + j + '" rows="1" cols="60"></div>');
			j++;
		}
		$('.school').autocomplete({
			source: "school.php" 
		}); 
	}
});
});