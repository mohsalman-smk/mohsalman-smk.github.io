<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
	.checkbox-bg {
		width: 20px;
		height: 20px;
	}
</style>
<script type="text/javascript">
	function check_addmission_form( el ) {
		var is_checked = el.checked;
		$( document ).find( 'input.admission_form:enabled' ).prop('checked', el.checked);
	}

	function check_required_form( el ) {
		var is_checked = el.checked;
		$( document ).find( 'input.required_form:enabled' ).prop('checked', el.checked);
	}

	function save() {
		$('body').addClass('loading');
		$('#save').attr('disabled', 'disabled');
		var values = [];
		$(".table").find("tr").each(function() {
			if (this.id) {
				var admission = $('input[name="admission_' + this.id + '"]').is(':checked') ? 'true' : 'false';
				var admission_required = admission == 'true' ? ($('input[name="admission_required_' + this.id + '"]').is(':checked') ? 'true' : 'false') : 'false';
				var value = {
					id: this.id,
					admission : admission,
					admission_required : admission_required
				};
				values.push(value);
			}
		});
		$.post(_BASE_URL + 'admission/form_settings/save', {'field_setting':JSON.stringify(values)}, function( response ) {
			H.growl('info', response.message);
			$('body').removeClass('loading');
			$('#save').removeAttr('disabled');
			window.location.reload();
		});
	}
</script>
<section class="content-header">
   <div class="row">
      <div class="col-xs-6">
         <h3 style="margin:0;"><i class="fa fa-sign-out text-green"></i> <span class="table-header">Pengaturan Formulir Penerimaan <?=$this->session->userdata('_student')?> Baru <?=$this->session->userdata('admission_year')?></span></h3>
      </div>
      <div class="col-xs-6">
      	<div class="btn-group pull-right">
         	<button id="save" onclick="save(); return false;" class="btn btn-sm btn-warning"><i class="fa fa-save"></i> SIMPAN PENGATURAN</button>
      	</div>
      </div>
   </div>
</section>

<section class="content">
	<div class="callout callout-info">
		<button type="button" onclick="removeCallout()" class="close">×</button>
   	<h4>Petunjuk Singkat</h4>
		<ul>
			<li>Form isian <strong>Nama Lengkap</strong>, <strong>Jenis Kelamin</strong>, <strong>Jalur Pendaftaran</strong>, dan <strong>Jenis Pendaftaran</strong> tidak dapat diubah karena menjadi identitas utama Calon <?=$this->session->userdata('school_level') >= 5 ? 'Mahasiswa Baru':'Peserta Didik Baru';?>.</li>
			<li>Form isian <strong>Tanggal Lahir</strong> tidak dapat diubah statusnya, tetap tampil dan wajib diisi karena digunakan untuk pencarian data ketika cetak formulir <?=$this->session->userdata('school_level') >= 5 ? 'PMB':'PPDB';?> maupun pengecekan hasil seleksi <?=$this->session->userdata('school_level') >= 5 ? 'PMB':'PPDB';?>.</li>
			<li>Form isian <strong>Kabupaten</strong> tidak dapat diubah statusnya, tetap tampil dan wajib diisi karena digunakan pada footer formulir <?=$this->session->userdata('school_level') >= 5 ? 'PMB':'PPDB';?>.</li>
			<li>Panduan Pengaturan <strong>PPDB ONLINE</strong> dapat didownload <a href="<?=site_url('media_library/files/PANDUAN-PPDB-ONLINE.pdf');?>"><strong>DISINI.</strong></a></li>
		</ul>
	</div>
   <div class="box">
      <div class="box-body">
         <div class="table-responsive">
            <table class="table table-hover table-striped table-condensed">
				 	<thead>
				 		<tr>
				 			<th>NO</th>
				 			<th>NAMA ISIAN</th>
				 			<th style="text-align: center;">TAMPIL DI FORMULIR <?=$this->session->userdata('school_level') >= 5 ? 'PMB':'PPDB'?> ?
								<br>
								<input type="checkbox" class="checkbox-bg check_addmission_form" onclick="check_addmission_form(this)">
							</th>
				 			<th style="text-align: center;">FORMULIR <?=$this->session->userdata('school_level') >= 5 ? 'PMB':'PPDB'?> HARUS DIISI ?
								<br>
								<input type="checkbox" class="checkbox-bg check_required_form" onclick="check_required_form(this)">
							</th>
				 		</tr>
				 	</thead>
				 	<tbody>
				 		<?php
				 		$no = 1;
				 		$required_fields = ['is_transfer', 'admission_type_id', 'full_name', 'birth_date', 'gender', 'district'];
				 		foreach($query->result() as $row) { ?>
					 		<?php $setting = json_decode($row->field_setting);?>
					 		<tr id="<?=$row->id?>">
					 			<td><?=$no?></td>
					 			<td><?=$row->field_description?></td>
					 			<td style="text-align: center;"><input <?=in_array($row->field_name, $required_fields) ? 'disabled="disabled"':'';?> <?=$setting->admission == 'true' ? 'checked="checked"':"";?> type="checkbox" class="checkbox-bg admission_form" name="admission_<?=$row->id?>"></td>
					 			<td style="text-align: center;"><input <?=in_array($row->field_name, $required_fields) ? 'disabled="disabled"':'';?> <?=$setting->admission_required == 'true' ? 'checked="checked"':"";?> type="checkbox" class="checkbox-bg required_form" name="admission_required_<?=$row->id?>"></td>
					 		</tr>
				 		<?php $no++; } ?>
				 	</tbody>
				</table>
         </div>
      </div>
      <div class="overlay" style="display: none;">
     		<i class="fa fa-refresh fa-spin"></i>
      </div>
   </div>
 </section>
