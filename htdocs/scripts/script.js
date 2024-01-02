const form = document.querySelector(".signup-form");
const form_submit = document.querySelector(".signup-form input[type='submit']");
const uname = document.querySelector("[name='uname']");
const upasswd = document.querySelector("[name='upasswd']");
const conf_upasswd = document.querySelector("[name='conf_upasswd']");

function validateInput() {
	let username = uname.value;
	let password = upasswd.value;
	let confirm_password = conf_upasswd.value;

	for (element of [uname, upasswd, conf_upasswd]) {
		element.setCustomValidity("");
	}

	if (!/^[a-zA-Z0-9][a-zA-Z0-9_]{2,}[a-zA-Z0-9]$/.test(username) || username.length > 30) {
		uname.setCustomValidity(
			"username must be between 3 and 30 characters long," +
				"and include only english letters, numbers, and underscores",
		);
	}

	if (
		!/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*#?&;: ])[A-Za-z\d@$!%*#?&;: ]{8,}$/.test(password) ||
		password.length > 254
	) {
		upasswd.setCustomValidity(
			"password must be between 8 and 254 characters long," +
				" include letters and numbers, " +
				"and at least one of the following special characters: '@$!%*#?&;: '",
		);
	}

	if (confirm_password !== password) {
		conf_upasswd.setCustomValidity("passwords don't match");
	}
}

form_submit.addEventListener("click", () => {
	validateInput();
	if (form.checkValidity()) {
		form.submit();
	}
});
