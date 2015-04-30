<!DOCTYPE html>
<html lang="en">
	<head>
		<base href="http://localhost/softuni/webdev/php-forum/" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo isset($title) ? $title . ' :: Forum System' : 'Forum System'; ?></title>
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">

		<!-- Bootstrap CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="home/">PHP Forum</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="navigation">
					<ul class="nav navbar-nav">
						<li class="active"><a href="home/">Home</a></li>
					</ul>
					<?php if(!$user->is_logged_in()): ?>
					<ul class="nav navbar-nav pull-right">
						<li><a href="user/signin">Login / Register</a></li>
					</ul>
					<?php else: ?>
					<ul class="nav navbar-nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<span class="glyphicon glyphicon-user"></span> <?php echo $user->get_logged_user()['username']; ?> <span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="user/profile">Profile</a></li>
							<li><a href="questions/new">Create question</a></li>
							<li class="divider"></li>
							<li><a href="user/logout">Logout</a></li>
						</ul>
					</li>
					</ul>
					<?php endif; ?>
				</div><!-- /.navbar-collapse -->
			</div>
		</nav>
		<div class="container">