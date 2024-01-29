function doValidate() {
	try {
		console.log("Validating...");
		var pw = document.getElementById("id_1723").value;
		console.log("Validating pw= " + pw);
		var email = document.getElementById("e-mail").value;
		console.log("Validating email= " + email);
		if (pw == null || pw == "") {
			alert("Both fields must be filled out");
			return false;
		} else if (email == null || email == "") {
			alert("Invalid email adress");
			return false;
		}
		return true;
	} catch(e) {
		return false;
	}
	return false;
}