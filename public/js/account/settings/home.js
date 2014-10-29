function matchPassword(newpassword, confirmPassword) {
	if (newpassword === confirmPassword) {
		return true;
	}
	else {
		return false;
	}
}

function checkNewPassword() {
	$("#new_password, #confirm_new_password").keyup(function () {

		var existing_password = $('#existing_password').val();
		var newPassword = $('#new_password').val();
		var confirm_password = $('#confirm_new_password').val();

		var conPassJScript = document.getElementById('confirm_new_password');
		var newPassJScript = document.getElementById('new_password');

		var result = matchPassword(newPassword, confirm_password);

		if (!result) {
			conPassJScript.setCustomValidity("Confirm password is not identical");
		}
		else {
			conPassJScript.setCustomValidity("");
		}

		if (existing_password === newPassword) {
			newPassJScript.setCustomValidity("Old password cannot be used as the new password");
		} else {
			newPassJScript.setCustomValidity("");
		}

	});
}