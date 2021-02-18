<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
input[type="checkbox"] {
	width: 20px;
	height: 20px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	getProspectiveStudent();
	getAttendancesList();
});

var exam_schedule_id = '<?=$this->uri->segment(4)?>';

// Chek / unchek All Checkbox
function check_all(checked, el) {
	$('input[name="' + el + '"]').prop('checked', checked);
}

function getProspectiveStudent() {
	$('body').addClass('loading');
	$.post(_BASE_URL + '/admission/admission_exam_attendances/get_prospective_students', {'exam_schedule_id':exam_schedule_id}, function(response) {
		var res = H.StrToObject(response);
		var str = '';
		if (res.students.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-prospective-students\')" /></th>';
			str += '<th>NO. DAFTAR</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			for (var z in res.students) {
				str += '<tr>';
				str += '<td><input type="checkbox" name="checkbox-prospective-students" value="' + res.students[ z ].student_id + '" /></td>';
				str += '<td>' + res.students[ z ].registration_number + '</td>';
				str += '<td>' + res.students[ z ].full_name + '</td>';
				str += '</tr>';
			}
			str += '</tbody>';
		}
		$('table.prospective-students').empty().html(str);
		$('body').removeClass('loading');
	});
}

function getAttendancesList() {
	$('body').addClass('loading');
	var exam_schedule_id = '<?=$this->uri->segment(4)?>';
	$.post(_BASE_URL + '/admission/admission_exam_attendances/get_attendance_lists', {'exam_schedule_id':exam_schedule_id}, function(response) {
		var res = H.StrToObject(response);
		var str = '';
		if (res.students.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-attendance-list\')" /></th>';
			str += '<th>NO. DAFTAR</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '<th>PRESENSI</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			for (var z in res.students) {
				str += '<tr>';
				str += '<td><input type="checkbox" name="checkbox-attendance-list" value="' + res.students[ z ].id + '" /></td>';
				str += '<td>' + res.students[ z ].registration_number + '</td>';
				str += '<td>' + res.students[ z ].full_name + '</td>';
				str += '<td>';
				str += '<select class="form-control input-sm" name="presence" id="presence_' + res.students[ z ].id + '">';
				str += '<option value="H" ' + (res.students[ z ].presence == 'H' ? 'selected="selected"' : '') + '>H</option>';
				str += '<option value="S" ' + (res.students[ z ].presence == 'S' ? 'selected="selected"' : '') + '>S</option>';
				str += '<option value="I" ' + (res.students[ z ].presence == 'I' ? 'selected="selected"' : '') + '>I</option>';
				str += '<option value="A" ' + (res.students[ z ].presence == 'A' ? 'selected="selected"' : '') + '>A</option>';
				str += '</select>';
				str += '</td>';
				str += '</tr>';
			}
			str += '</tbody>';
		}
		$('table.attendance-list').empty().html(str);
		$('body').removeClass('loading');
	});
}

function saveAttendanceList() {
	var rows = $('input[name="checkbox-prospective-students"]:checked');
	var student_ids = [];
	rows.each(function() {
		student_ids.push($(this).val());
	});
	if (student_ids.length) {
		eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data ' + _STUDENT + ' yang terceklis akan dimasukan ke dalam daftar Peserta Ujian Tes Tulis ?', 'Konfirmasi').then(function() {
			$('body').addClass('loading');
			var values = {
				student_ids: student_ids.join(','),
				exam_schedule_id: exam_schedule_id
			};
			$.post(_BASE_URL + 'admission/admission_exam_attendances/save_attendance_lists', values, function( response ) {
				var res = H.StrToObject(response);
				H.growl(res.type, res.message);
				getProspectiveStudent();
				getAttendancesList();
				$('body').removeClass('loading');
			});
		});
	} else {
		$('body').removeClass('loading');
	}
}

// Delete Permanent Data
function deleteFromAttendanceList() {
	var rows = $('input[name="checkbox-attendance-list"]:checked');
	var ids = [];
	rows.each(function() {
		ids.push($(this).val());
	});
	if (ids.length) {
		eModal.confirm('Apakah anda yakin ' + ids.length + ' data ' + _STUDENT + ' yang terceklis akan dihapus ?', 'Konfirmasi').then(function() {
			$('body').addClass('loading');
			var values = {
				ids: ids.join(',')
			};
			$.post(_BASE_URL + 'admission/admission_exam_attendances/delete_attendance_lists', values, function( response ) {
				var res = H.StrToObject(response);
				H.growl(res.type, res.message);
				getAttendancesList();
				getProspectiveStudent();
				$('body').removeClass('loading');
			});
		});
	} else {
		H.growl('info', 'Tidak ada data yang terpilih');
	}
}

function savePresences() {
	$('.save-attendances').attr('disabled', 'disabled');
	var rows = $('select[name="presence"]');
	var presences = [];
	rows.each(function() {
		presences.push({
			id:this.id.split('_')[ 1 ],
			presence:$(this).val()
		});
	});
	if (presences.length) {
		$('body').addClass('loading');
		var values = {
			presences: JSON.stringify(presences)
		};
		$.post(_BASE_URL + 'admission/admission_exam_attendances/save_presences', values, function( response ) {
			H.growl('info', H.message(response.message));
			$('body').removeClass('loading');
			$('.save-attendances').removeAttr('disabled');
			getProspectiveStudents();
		});
	} else {
		H.growl('info', 'Tidak ada data yang tersimpan');
	}
}

</script>
<section class="content-header">
	<h3 style="margin:0;"><i class="fa fa-sign-out text-green"></i> <span class="table-header"><?=isset($title) ? ucwords(strtolower($title)) : ''?></span></h3>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-5 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<i class="fa fa-sign-out"></i>
					<h3 class="box-title">CALON <?=strtoupper($this->session->userdata('_student'))?> BARU</h3>
					<div class="box-tools">
						<button class="btn btn-sm btn-success" onclick="saveAttendanceList()"><i class="fa fa-save"></i> SIMPAN</button>
					</div>
				</div>
				<div class="box-body">
					<dl class="dl-horizontal">
						<dt><?=$this->session->userdata('_academic_year')?></dt>
						<dd><?=$query->academic_year?></dd>
						<dt>Jalur Pendaftaran</dt>
						<dd><?=$query->admission_type?></dd>
						<?php if (in_array($this->session->userdata('school_level'), have_majors())) { ?>
							<dt><?=$this->session->userdata('_major')?></dt>
							<dd><?=$query->major_name?></dd>
						<?php } ?>
						<dt>Mata Pelajaran</dt>
						<dd><?=$query->subject_name?></dd>
						<dt>Tanggal</dt>
						<dd><?=indo_date($query->exam_date)?></dd>
						<dt>Waktu</dt>
						<dd><?=$query->exam_start_time?> s.d <?=$query->exam_end_time?></dd>
						<dt>Lokasi</dt>
						<dd>Gedung <?=str_replace(['gedung', 'Gedung'], '', $query->building_name)?> Ruang <?=$query->room_name?></dd>
						<dt>Kapasitas</dt>
						<dd><?=$query->room_capacity?> Orang</dd>
					</dl>
					<table class="table table-striped table-condensed prospective-students"></table>
				</div>
			</div>
		</div>
		<div class="col-md-7 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<i class="fa fa-sign-out"></i>
					<h3 class="box-title">PESERTA UJIAN</h3>
					<div class="box-tools">
						<div class="btn-group">
							<button class="btn btn-sm btn-success save-attendances" onclick="savePresences()"><i class="fa fa-save"></i> SIMPAN PRESENSI</button>
							<button class="btn btn-sm btn-danger" onclick="deleteFromAttendanceList()"><i class="fa fa-trash"></i> HAPUS</button>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-striped table-condensed attendance-list"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
