$(document).ready(function () {
	$('#addPost').click(function (event) {
		event.preventDefault();
		var i = $(".position_field").length;
		if (i >= 9) {
			alert("Maximum entries exceeded");
		} else if (i < 9) {
			$('#position_fields').append('<div class="position_field"> \
			<p>Year: <input  maxlength="4" type="text" name="year'+ i +'"><input class="position_remove" type="button" value="-"></p><br /> \
			<textarea name="desc' + i + '" rows="8" cols="80"></textarea></div>'); 
			
		}

		$(".position_remove").click(function() {
			console.log("removed clicked");
			$(this).parent().parent().remove();
		});

	});
});