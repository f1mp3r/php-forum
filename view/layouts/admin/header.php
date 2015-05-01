<!DOCTYPE html>
<html lang="en">
	<head>
		<base href="<?php echo BASE_URL; ?>admin/" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo isset($title) ? $title . ' :: Forum System' : 'Forum System'; ?></title>

		<!-- Main CSS -->
		<link rel="stylesheet" type="text/css" href="../assets/css/admin_style.css">
		<!-- Bootstrap CSS -->
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<?php if (isset($_auto_load_css)): ?>
		<?php foreach ($_auto_load_css as $css): echo $css; endforeach; ?>
		<?php endif; ?>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<div id="wrapper">
				<h1 class="text-center" id="header">Administration</h1>
				<div class="row">
					<div class="col-lg-2 col-md-4 col-sm-4">
						<ul class="nav nav-pills nav-stacked">
							<li<?php if (isset($thisPage)) {echo $thisPage == null || $thisPage == 'home' ? ' class="active"' : null;} ?> role="presentation"><a href="home/"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
							<li<?php if (isset($thisPage)) {echo $thisPage == 'questions' ? ' class="active"' : null;} ?> role="presentation"><a href="questions/all"><i class="glyphicon glyphicon-align-justify"></i> Questions</a></li>
							<li<?php if (isset($thisPage)) {echo $thisPage == 'categories' ? ' class="active"' : null;} ?> role="presentation"><a href="categories/all"><i class="glyphicon glyphicon-list-alt"></i> Categories</a></li>
							<li role="presentation"><a href="../"><i class="glyphicon glyphicon-step-backward"></i> Back to website</a></li>
						</ul>
					</div>
					<div class="col-lg-10 col-md-8 col-sm-8">