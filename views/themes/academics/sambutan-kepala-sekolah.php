<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<div class="col-xs-12 col-md-9">
	<div class="thumbnail no-border">
		<div class="caption">
			<h3><?= $page_title; ?></h3>
			<?= get_welcome() ?>
			<div id="share1"></div>
			<script>
				$("#share1").jsSocials({
					shares: ["email", "twitter", "facebook", "googleplus", "whatsapp"]
				});
			</script>
		</div>
	</div>
</div>
<?php $this->load->view('themes/academics/sidebar') ?>