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
	<?=link_tag('assets/css/bootstrap.css');?>
	<?=link_tag('assets/css/font-awesome.css');?>
	<?=link_tag('assets/css/toastr.css');?>
	<?=link_tag('assets/css/bootstrap-datepicker.css');?>
	<?=link_tag('assets/css/AdminLTE.css');?>
	<?=link_tag('assets/css/jquery.timepicker.css');?>
	<?=link_tag('assets/css/backend-style.css');?>
	<?=link_tag('assets/css/magnific-popup.css');?>
	<?=link_tag('assets/css/bootstrap-colorpicker.css');?>
	<?=link_tag('assets/css/jquery-clockpicker.css');?>
	<?=link_tag('assets/css/select2.css');?>
	<?=link_tag('assets/css/jquery.tagsinput.min.css');?>
	<?=link_tag('assets/css/loading.css');?>
	<script type="text/javascript">
	const _BASE_URL = '<?=base_url();?>';
	const _CURRENT_URL = '<?=current_url();?>';
	const _SCHOOL_LEVEL = '<?=$this->session->userdata('school_level');?>';
	const _ACADEMIC_YEAR = '<?=$this->session->userdata('_academic_year');?>';
	const _STUDENT = '<?=$this->session->userdata('_student');?>';
	const _IDENTITY_NUMBER = '<?=$this->session->userdata('_identity_number');?>';
	const _EMPLOYEE = '<?=$this->session->userdata('_employee');?>';
	const _HEADMASTER = '<?=$this->session->userdata('_headmaster');?>';
	const _MAJOR = '<?=$this->session->userdata('_major');?>';
	const _SUBJECT = '<?=$this->session->userdata('_subject');?>';
	</script>
	<script src="<?=base_url('assets/js/backend.min.js');?>"></script>
</head>
<!-- sidebar-collapse -->
<body class="hold-transition skin-blue sidebar-mini <?=$this->session->userdata('sidebar_collapse') ? 'sidebar-collapse':''?>">
	<div class="wrapper">
		<header class="main-header">
			<a href="javascript:(0)" class="logo">
				<span class="logo-mini"><i class="fa fa-cogs"></i></span>
				<span class="logo-lg"><b>CONTROL</b> PANEL</span>
			</a>
			<nav class="navbar navbar-static-top">
				<a onclick="sidebarCollapse(); return false;" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<div class="collapse navbar-collapse pull-right" id="navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="javascript:(0)" class="current-time"></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question-circle-o"></i> BANTUAN <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="https://www.smknj.sch.id" target="_blank"><i class="fa fa-globe"></i> Situs Resmi</a></li>
								<li><a href="https://www.instagram.com/smknuruljadidpaiton" target="_blank"><i class="fa fa-instagram"></i> Instagram</a></li>
								<li><a href="javascript:(0)" data-toggle="modal" data-target="#cms-info"><i class="fa fa-info-circle"></i> Tentang</a></li>
							</ul>
						</li>
						<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
							<li <?=isset($user_profile) ? 'class="active"' : '';?>><a href="<?=site_url('profile');?>"><i class="fa fa-edit"></i> UBAH PROFIL</a></li>
						<?php } ?>
						<li <?=isset($change_password) ? 'class="active"' : '';?>><a href="<?=site_url('change_password');?>"><i class="fa fa-key"></i> UBAH KATA SANDI</a></li>
						<li class="logout"><a href="<?=site_url('logout');?>"><i class="fa fa-power-off"></i> KELUAR</a></li>
					</ul>
				</div>
			</nav>
		</header>
		<aside class="main-sidebar">
			<?php $this->load->view('backend/sidebar');?>
		</aside>
		<div class="content-wrapper">
			<?php $this->load->view($content);?>
		</div>
		<div class="modal" id="cms-info">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span></button>
							<h4 class="modal-title"><i class="fa fa-info-circle"></i> Tentang</h4>
						</div>
						<div class="modal-body">
							<table class="table table-condensed table-bordered">
								<tbody>
									<tr>
										<td width="20%">Code Name</td>
										<td width="1px">:</td>
										<td><?=config_item('apps')?></td>
									</tr>
									<tr>
										<td>Author</td>
										<td>:</td>
										<td><a href="https://www.instagram.com/aansix15">Muhammad Anshori</a></td>
									</tr>
									<tr>
										<td>Email</td>
										<td>:</td>
										<td><?=config_item('email')?></td>
									</tr>
									<tr>
										<td>Version</td>
										<td>:</td>
										<td><?=config_item('version')?></td>
									</tr>
									<tr>
										<td>Link</td>
										<td>:</td>
										<td><a href="<?=config_item('website')?>">SMK Nurul Jadid</a></td>
									</tr>
									<tr>
										<td>Copyright</td>
										<td>:</td>
										<td>&copy; 2020-<?=date('Y')?></td>
									</tr>
								</tbody>
							</table>
							<p>SYARAT DAN KETENTUAN :</p>
							<ol>
								<li>Tidak diperkenankan memperjualbelikan CMS ini tanpa seizin dari <a href="https://www.instagram.com/aansix15">Pengembang.</a>.</li>
								<li>Tidak diperkenankan membuat Aplikasi turunan dari CMS ini dengan nama baru.</li>
								<li>Tidak diperkenankan menghapus kode sumber aplikasi yang berada di bagian footer CMS.</li>
								<li>Tidak diperkenankan menyertakan link komersil seperti Layanan Hosting maupun domain yang menguntungkan sepihak.</li>
							</ol>
						</div>
					</div>
				</div>
			</div>

			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<p>Powered by <a href="<?=$this->config->item('website');?>" target="_blank"><?=$this->config->item('apps');?> <?=$this->config->item('version');?></a></p>
				</div>
				<p>Copyright &copy; <?=date('Y');?> <?=$this->session->userdata('school_name')?>. All rights reserved.</p>
			</footer>
			<div class="control-sidebar-bg"></div>
		</div>
		<a href="javascript:" id="return-to-top"><i class="fa fa-angle-double-up"></i></a>
	</body>
	</html>
