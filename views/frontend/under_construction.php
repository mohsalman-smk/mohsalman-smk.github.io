<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$this->session->userdata('school_name')?></title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="keywords" content="<?=$this->session->userdata('meta_keywords');?>"/>
	<meta name="description" content="<?=$this->session->userdata('meta_description');?>"/>
	<meta name="subject" content="Pendidikan Indonesia">
	<meta name="copyright" content="<?=$this->session->userdata('school_name')?>">
	<meta name="language" content="Indonesia">
	<meta name="robots" content="index,follow" />
	<meta name="revised" content="Senin, 05 November 2018" />
	<meta name="Classification" content="Pendidikan, Kurikulum 2013">
	<meta name="identifier-URL" content="https://www.radiustheme.com/">
	<meta name="author" content="Radiustheme, support@radiustheme.com">
	<meta name="designer" content="Radiustheme, https://www.radiustheme.com/">
	<meta name="reply-to" content="support@radiustheme.com">
	<meta name="owner" content="Radiustheme">
	<meta name="url" content="https://www.radiustheme.com/">
	<meta name="category" content="PPDB, SIMAK">
	<meta name="coverage" content="Worldwide">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Copyright" content="<?=$this->session->userdata('school_name');?>" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="7" />
	<meta name="webcrawlers" content="all" />
	<meta name="rating" content="general" />
	<meta name="spiders" content="all" />
	<meta itemprop="name" content="<?=$this->session->userdata('school_name');?>" />
	<meta itemprop="description" content="<?=$this->session->userdata('meta_description');?>" />
	<meta itemprop="image" content="<?=base_url('media_library/images/'. $this->session->userdata('logo'));?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="<?=base_url('media_library/images/'.$this->session->userdata('favicon'));?>">
</head>
<body>
	<style>
	body { text-align: center; padding: 150px; }
	h1 { font-size: 50px; }
	body { font: 20px Helvetica, sans-serif; color: #333; }
	article { display: block; text-align: left; width: 650px; margin: 0 auto; }
	a { color: #dc8100; text-decoration: none; }
	a:hover { color: #333; text-decoration: none; }
	</style>
	<article>
		<h1>We&rsquo;ll be back soon!</h1>
		<div>
			<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment.</p>
			<p>&mdash; IT Team <?=$this->session->userdata('school_name');?></p>
		</div>
	</article>
</body>
</html>