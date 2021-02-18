<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#birth_date').datepicker({
			format: 'yyyy-mm-dd',
			todayBtn: 'linked',
			minDate: '0001-01-01',
			setDate: new Date(),
			todayHighlight: true,
			autoclose: true
		});
	});
</script>
<div class="col-xs-12 col-md-9">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-edit"></i> <?= strtoupper($page_title) ?></h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal">
				<div class="form-group">
					<label for="full_name" class="col-sm-4 control-label">Nama Lengkap <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="full_name" name="full_name">
					</div>
				</div>
				<div class="form-group">
					<label for="gender" class="col-sm-4 control-label">Jenis Kelamin <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<?= form_dropdown('gender', ['' => 'Pilih :', 'M' => 'Laki-laki', 'F' => 'Perempuan'], set_value('gender'), 'class="form-control input-sm" id="gender"') ?>
					</div>
				</div>
				<div class="form-group">
					<label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<div class="input-group">
							<input readonly="true" type="text" value="<?php echo set_value('birth_date') ?>" class="form-control input-sm" id="birth_date" name="birth_date">
							<span class="input-group-addon input-sm"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="end_date" class="col-sm-4 control-label">Tahun Lulus <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="end_date" name="end_date">
					</div>
				</div>
				<div class="form-group">
					<label for="identity_number" class="col-sm-4 control-label"><?= $this->session->userdata('school_level') >= 5 ? 'Nomor Induk Mahasiswa' : 'Nomor Induk Siswa'; ?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="identity_number" name="identity_number">
					</div>
				</div>
				<div class="form-group">
					<label for="street_address" class="col-sm-4 control-label">Alamat <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<textarea rows="5" class="form-control input-sm" id="street_address" name="street_address"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">Email <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="email" name="email">
					</div>
				</div>
				<div class="form-group">
					<label for="phone" class="col-sm-4 control-label">Telepon</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="phone" name="phone">
					</div>
				</div>
				<div class="form-group">
					<label for="mobile_phone" class="col-sm-4 control-label">Handphone</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="mobile_phone" name="mobile_phone">
					</div>
				</div>
				<div class="form-group">
					<label for="file" class="col-sm-4 control-label">Foto</label>
					<div class="col-sm-8">
						<input type="file" id="file" name="file">
						<p class="help-block">Foto harus JPG dan ukuran file maksimal 1 Mb</p>
					</div>
				</div>
				<?php if (NULL !== $this->session->userdata('recaptcha_status') && $this->session->userdata('recaptcha_status') == 'enable') { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label"></label>
						<div class="col-sm-8">
							<div class="g-recaptcha" data-sitekey="<?= $recaptcha_site_key ?>"></div>
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<button type="button" onclick="save_alumni_request(); return false;" class="btn btn-success"><i class="fa fa-send"></i> SUBMIT</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('themes/academics/sidebar') ?>