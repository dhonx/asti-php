<?php
require_once "connection/connection.php";
require_once "config.php";
require_once "utils.php";
require_once "vendor/autoload.php";

use Rakit\Validation\Validator;

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
	$login_as = $_SESSION['login_as'];
	if ($login_as == "super_admin" || $login_as == "admin") {
		header("location: ./admin");
	} else {
		header("location: ./pegawai");
	}
}

$errors = [];

if (isset($_POST["login"])) {
	$validator = new Validator(VALIDATION_MESSAGES);
	$valid_login_as_values = ["admin", "super_admin", "pegawai"];
	$validation = $validator->make($_POST, [
		"email"		=> "required|email",
		"sandi"     => "required|min:8",
		"login_as"	=> ["required", $validator("in", $valid_login_as_values)]
	]);
	$validation->validate();

	if (!$validation->fails()) {
		$email      = $_POST["email"];
		$sandi		= $_POST["sandi"];
		$login_as   = $_POST["login_as"];

		// Login as super admin or admin
		if ($login_as == "super_admin" || $login_as == "admin") {
			$q_get_admin = "SELECT email, sandi, nama FROM admin WHERE email = '$email' AND tipe_admin = '$login_as'";
			$result = $connection->query($q_get_admin);
			if ($result && $result->num_rows == 1) {
				$result_array = $result->fetch_assoc();
				$sandi_from_db = $result_array["sandi"];
				$nama = $result_array["nama"];
				if (password_verify($sandi, $sandi_from_db)) {
					$_SESSION["email"] = $email;
					$_SESSION["nama"] = $nama;
					$_SESSION["login_as"] = $login_as;
					$_SESSION["logged_in"] = true;
					$_SESSION["sidenav"] = 1;
					redirect("./admin");
				} else {
					array_push($errors, "Email atau sandi salah");
				}
			} else {
				array_push($errors, "Email atau sandi salah");
			}
		}

		// else {
		// TODO: Handle login for pegawai
		// }
	}

	// Validation fails
	else {
		$errors = $validation->errors()->firstOfAll();
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php require_once "includes/css.php" ?>
	<title>Login - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 flex items-center text-sm">
	<?php require_once "includes/loading.php" ?>
	<form class="bg-white max-w-sm mx-auto shadow-md rounded-lg overflow-hidden px-6 py-4" method="POST">
		<h2 class="text-2xl text-center my-2 font-bold">Login ASTI</h2>

		<?php if ($errors != null) { ?>
			<div class="bg-red-400 p-2 mb-2 text-white">
				<?php foreach ($errors as $error) { ?>
					<div><?= $error ?></div>
				<?php } ?>
			</div>
		<?php } ?>

		<label class="mx-1" for="email">Email</label>
		<input autofocus id="email" class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="email" minlength="6" name="email" required spellcheck="false" type="email" value="donnisnoni.tid3@gmail.com">

		<label class="mx-1" for="sandi">Sandi</label>
		<input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="sandi" minlength="8" name="sandi" required type="password" value="donnisnoni@1234">

		<label class="mx-1" for="login-as">Login sebagai</label>
		<select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="login-as" name="login_as" value="admin">
			<option value="admin">Admin</option>
			<option value="super_admin">Super Admin</option>
			<option value="pegawai">Pegawai</option>
		</select>

		<div class="flex justify-end">
			<button class="active-scale bg-blue-900 text-white block py-2 px-6 my-2 rounded-md" name="login" type="submit">Login</button>
		</div>
	</form>
</body>

</html>
