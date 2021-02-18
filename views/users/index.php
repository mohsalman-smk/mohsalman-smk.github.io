<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
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
		<meta name="csrf-token" content="<?=$this->session->userdata('csrf_token')?>">
		<link rel="icon" href="<?=base_url('media_library/images/'.$this->session->userdata('favicon'));?>">

		<?=link_tag('assets/css/vendor.css');?>
		<?=link_tag('assets/css/app-blue.css');?>
	
		<?=link_tag('assets/css/font-awesome.css');?>
		<?=link_tag('assets/css/toastr.css');?>
		<?=link_tag('assets/css/loading.css');?>
		<link rel="icon" href="<?=base_url('assets/img/favicon.png');?>">
		<script type="text/javascript">
			const _BASE_URL = '<?=base_url();?>', _CURRENT_URL = '<?=current_url();?>';
		</script>
		<script src="<?=base_url('assets/js/login.min.js');?>"></script>	
</head>
<body class="">
	<div class="login-page">
		<div class="row">
			<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
				<img src="<?=base_url('media_library/images/'. $this->session->userdata('logo'));?>" class="user-avatar" />
				<h1><?=$this->session->userdata('school_name');?></h1>
				<?php $this->load->view($content);?>


			</div>			
		</div>
	</div>
</body>
</html>