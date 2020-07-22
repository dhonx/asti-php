<?php
require_once "./config.php";

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
	$login_as = $_SESSION['login_as'];
	if ($login_as == "super_admin" || $login_as == "admin") {
		header("location: ./admin");
	} else {
		header("location: ./pegawai");
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="<?= BASE_PATH ?>/css/tailwind.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/main.css">
	<title>Login - ASTI</title>
</head>

<body class="font-sans min-h-screen bg-gray-200 flex items-center text-sm">
	<form action="auth" class="bg-white max-w-sm mx-auto shadow-md rounded-lg overflow-hidden px-6 py-4" method="post">
		<h2 class="text-2xl text-center my-2 font-bold">Login ASTI</h2>

		<label class="mx-1" for="email">Email</label>
		<input autocomplete="off" autofocus id="email" class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-lg" id="email" minlength="6" name="email" required spellcheck="false" type="email" value="donnisnoni.tid3@gmail.com">

		<label class="mx-1" for="password">Sandi</label>
		<input class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-md" id="password" minlength="8" name="password" required type="password" value="donnisnoni@1234">

		<label class="mx-1" for="login-as">Login sebagai</label>
		<select class="bg-gray-200 w-full px-3 py-2 mb-2 rounded-lg" id="login-as" name="login_as" value="admin">
			<option value="admin">Admin</option>
			<option value="super_admin">Super Admin</option>
		</select>

		<div class="my-3 text-red-600">
			<?php if (isset($_GET['message'])) {
				echo $_GET['message'];
			} ?>
		</div>

		<div class="flex justify-end">
			<button class="bg-blue-500 text-white block py-2 px-6 my-2 rounded-md">Login</button>
		</div>
	</form>
</body>

</html>
