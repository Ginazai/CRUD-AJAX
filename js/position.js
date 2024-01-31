$(document).ready(function () {
	$('#addPost').click(function (event) {
		event.preventDefault();
		var i = $(".position_field").length;
		if (i >= 9) {
			alert("Maximum entries exceeded");
		} else if (i < 9) {
			$('#position_fields').append('<div class="position_field"> \
			<label for="year'+ i +'" class="form-label">Year:</label><input id="year'+ i +'" class="form-control col-6" maxlength="4" type="text" name="year'+ i +'">\
			<textarea class="form-control" name="desc' + i + '" rows="8" cols="80"></textarea>\
			<button class="col-6 form-control position_remove btn btn-sm btn-danger mt-2" type="button"><span class="fas fa-trash"></span></button></div>'); 
		}

		$(".position_remove").click(function() {
			console.log("removed clicked");
			$(this).parent().remove();
		});

	});
});