<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $heading; ?></title>
	<style type="text/css">
	body {
		text-align: center;
		padding: 150px;
	}
	h1 {
		font-size: 50px;
	}
	body {
		font: 20px Helvetica, sans-serif;
		color: #333;
	}
	article {
		display: block;
		text-align: left;
		width: 650px;
		margin: 0 auto;
	}
	a {
		color: #dc8100;
		text-decoration: none;
	}
	a:hover {
		color: #333;
		text-decoration: none;
	}
</style>
</head>
<body>
	<article>
		<h1><?php echo $heading; ?></h1>
		<p><?php echo $message; ?></p>
	</article>
</body>
</html>
