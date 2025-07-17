$(document).ready(function () {
	console.log("auth_toggle_password.js loaded.");

	// Untuk Login
	const togglePasswordLogin = $("#togglePassword");
	const passwordInputLogin = $("#exampleInputPassword");

	console.log(
		"Login Toggle Button Found:",
		togglePasswordLogin.length > 0 ? "YES" : "NO",
		"ID: #togglePassword"
	);
	console.log(
		"Login Input Field Found:",
		passwordInputLogin.length > 0 ? "YES" : "NO",
		"ID: #exampleInputPassword"
	);

	if (togglePasswordLogin.length && passwordInputLogin.length) {
		togglePasswordLogin.on("click", function () {
			console.log("Login Toggle Clicked!");
			const type =
				passwordInputLogin.attr("type") === "password" ? "text" : "password";
			passwordInputLogin.attr("type", type);
			$(this).find("i").toggleClass("fa-eye fa-eye-slash");
		});
		console.log("Login Toggle Click Listener Attached.");
	} else {
		console.warn("Login toggle elements not found or ready.");
	}

	// Untuk Registrasi Password 1
	const togglePassword1 = $("#togglePassword1");
	// ID input password pertama di registrasi BISA SAMA dengan ID input login: exampleInputPassword
	// Ini berpotensi jadi masalah jika elemennya memang unik di halaman.
	// Jika form_login.php dan registrasi.php menggunakan ID exampleInputPassword untuk input password mereka,
	// maka JavaScript hanya akan menemukan yang pertama (di halaman login) atau yang di halaman sendiri.
	// Mari kita periksa ID input password di registrasi.php
	// DARI CODINGAN ANDA SEBELUMNYA:
	// Registrasi password_1: <input type="password" ... id="exampleInputPassword" ...>
	// Registrasi password_2: <input type="password" ... id="exampleRepeatPassword" ...>
	// Jadi, passwordInput1 harusnya menargetkan ID yang BENAR untuk input password pertama di REGISTRASI,
	// dan itu adalah #exampleInputPassword. Ini konflik ID.

	// SOLUSI KRITIS: UBAH ID INPUT PASSWORD DI REGISTRASI.PHP
	// Di registrasi.php, ubah:
	// <input type="password" ... id="exampleInputPassword" ... name="password_1"> menjadi:
	// <input type="password" ... id="registerPassword1" ... name="password_1">
	// Lalu, sesuaikan script JS di bawah:
	const passwordInputReg1 = $("#registerPassword1"); // <<<< UBAH INI

	console.log(
		"Reg Password 1 Toggle Button Found:",
		togglePassword1.length > 0 ? "YES" : "NO",
		"ID: #togglePassword1"
	);
	console.log(
		"Reg Password 1 Input Field Found:",
		passwordInputReg1.length > 0 ? "YES" : "NO",
		"ID: #registerPassword1"
	);

	if (togglePassword1.length && passwordInputReg1.length) {
		// <<<< UBAH INI
		togglePassword1.on("click", function () {
			console.log("Reg Password 1 Toggle Clicked!");
			const type =
				passwordInputReg1.attr("type") === "password" ? "text" : "password"; // <<<< UBAH INI
			passwordInputReg1.attr("type", type); // <<<< UBAH INI
			$(this).find("i").toggleClass("fa-eye fa-eye-slash");
		});
		console.log("Reg Password 1 Toggle Click Listener Attached.");
	} else {
		console.warn("Reg Password 1 toggle elements not found or ready.");
	}

	// Untuk Registrasi Password 2
	const togglePassword2 = $("#togglePassword2");
	const passwordInput2 = $("#exampleRepeatPassword"); // Ini sudah benar, id unik

	console.log(
		"Reg Password 2 Toggle Button Found:",
		togglePassword2.length > 0 ? "YES" : "NO",
		"ID: #togglePassword2"
	);
	console.log(
		"Reg Password 2 Input Field Found:",
		passwordInput2.length > 0 ? "YES" : "NO",
		"ID: #exampleRepeatPassword"
	);

	if (togglePassword2.length && passwordInput2.length) {
		togglePassword2.on("click", function () {
			console.log("Reg Password 2 Toggle Clicked!");
			const type =
				passwordInput2.attr("type") === "password" ? "text" : "password";
			passwordInput2.attr("type", type);
			$(this).find("i").toggleClass("fa-eye fa-eye-slash");
		});
		console.log("Reg Password 2 Toggle Click Listener Attached.");
	} else {
		console.warn("Reg Password 2 toggle elements not found or ready.");
	}
});
