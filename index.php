<?php
require_once __DIR__ . '/settings/core.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;600&display=swap" rel="stylesheet">
	<!--<link rel="stylesheet" href="./css/index.css">-->
	<title>Homepage</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
		body, html{
			height: 100%;
			margin: 0;
		}

		.bg-image {
			background: url("images/notebook.jpg") no-repeat center center fixed;
			background-size: cover;
			filter: blur(6px);
			height: 100%;
			width: 100%;
			position: absolute;
			top: 0;
			left: 0;
			z-index: -1;
		}

		.card-overlay{
				/*background-color: rgba(255, 255, 255, 0.9);*/
			border-radius: 1rem;
			padding: 2rem;
			max-width: 500px;
			margin: auto;
		}
		</style>
</head>

	<body>
		<nav class="navbar navbar-light bg-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="/index.php">Virtual Pharmacy</a>
				<div>
					<?php if (!isLoggedIn()): ?>
						<a href="/Login/register.php" class="btn btn-outline-primary me-2">Register</a>
						<a href="/Login/login.php" class="btn btn-primary">Login</a>
					<?php else: ?>
						<form method="post" action="/Login/logout.php" style="display:inline">
							<button class="btn btn-outline-danger">Logout</button>
						</form>
						<?php if (isAdmin()): ?>
							<a href="/admin/category_add.php" class="btn btn-secondary ms-2">Category</a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</nav>

		<div class="bg-image"></div>

		<div class="d-flex align-items-center justify-content-center vh-100">
			<div class="card-overlay text-center shadow-lg">
				<h1 class="mb-3">Welcome</h1>
				<p class="mb-4">Use the menu below to Register or Login</p>
				<div class="d-flex justify-content-center gap-3">
					<a href="Login/register.php" class="btn btn-primary">Register</a>
					<a href="Login/login.php" class="btn btn-secondary">Login</a>
				</div>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>

	