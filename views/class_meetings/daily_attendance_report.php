<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
$(document).ready(function() {
	// Select2
	$(".select2").select2({ width: '100%' });
	// Datepicker
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		todayBtn: 'linked',
		minDate: '0001-01-01',
		setDate: new Date(),
		todayHighlight: true,
		autoclose: true
	});
});

function search() {
	var values = {
		academic_year_id: $('#academic_year_id').val(),
		semester: $('#semester').val(),
		class_group_id: $('#class_group_id').val(),
		start_date: $('#start_date').val(),
		end_date: $('#end_date').val()
	}
	if (values['start_date'] &&
		moment(values['start_date'], 'YYYY-MM-DD', true).isValid() &&
		values['end_date'] &&
		moment(values['end_date'], 'YYYY-MM-DD', true).isValid()
	) {
		_get_daily_attendance_report(values);
	} else {
		H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
	}
}

/**
* Monthly Attendance Report
*/
function _get_daily_attendance_report( values ) {
	$('body').addClass('loading');
	$.post(_BASE_URL + 'academic/daily_attendance_report/get_daily_attendance_report', values, function( response ) {
		var dates = response.dates;
		var query = response.query;
		if (query.length) {
			var students = [];
			for (var x in query) {
				var row = query[ x ];
				if (!inArray('identity_number', row.identity_number, students)) {
					var student = {
						'identity_number': row.identity_number,
						'full_name': row.full_name,
						'gender': row.gender
					}
					students.push(student);
				}
			}
			var str = '';
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th colspan="' + (8 + dates.length) + '">';
			str += 'DAILY ATTENDANCE REPORT | ' + $('#start_date').val() + ' - ' + $('#end_date').val() + '<button type="button" onclick="save2excel(); return false;" class="btn btn-xs btn-success pull-right"><i class="fa fa-file-excel-o"></i> SIMPAN SEBAGAI FILE EXCEL</button>';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th>' + _IDENTITY_NUMBER + '</th>';
			str += '<th>NAMA ' + _STUDENT.toUpperCase() + '</th>';
			str += '<th width="60px">L/P</th>';
			for (var x in dates) {
				var date = dates[ x ];
				var is_sunday = '';
				if (moment( date ).format('dddd') == 'Sunday') {
					is_sunday = 'class="text-red"';
				}
				str += '<th ' + is_sunday + ' width="30px" title="' + date + '">' + date.substr(8, 2) + '</th>';
			}
			str += '<th width="30px">H</th>';
			str += '<th width="30px">S</th>';
			str += '<th width="30px">I</th>';
			str += '<th width="30px">A</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = 1;
			for( var y in students) {
				var student = students[ y ];
				str += '<tr>';
				str += '<td>' + no + '</td>';
				str += '<td>' + student.identity_number + '</td>';
				str += '<td>' + student.full_name + '</td>';
				str += '<td>' + student.gender + '</td>';
				var H = 0, S = 0, I = 0, A = 0;
				for (var z in dates) {
					var date = dates[ z ];
					var presence = searchAttendance(student.identity_number, date, query);
					if (presence == 'H') H++;
					if (presence == 'S') S++;
					if (presence == 'I') I++;
					if (presence == 'A') A++;
					var is_sunday = '';
					if (moment( date ).format('dddd') == 'Sunday') {
						is_sunday = 'class="text-red"';
					}
					str += '<td ' + is_sunday + '>' + presence + '</td>';
				}
				str += '<td>' + H + '</td>';
				str += '<td>' + S + '</td>';
				str += '<td>' + I + '</td>';
				str += '<td>' + A + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('.daily-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.daily-report').empty();
			toastr.info('Data tidak ditemukan!', 'Info' );
		}
		$('body').removeClass('loading');
	});
}

/**
* Search in Array
* @return Boolean
*/
function inArray(key, value, array) {
	for (var z in array) {
		if (array[ z ][ key ] === value) return true;
	}
	return false;
}

/**
* Search Attendance
* @return Boolean
*/
function searchAttendance(identity_number, date, array) {
	for (var z in array) {
		if (array[ z ].identity_number === identity_number && array[ z ].date == date) return array[ z ].presence;
	}
	return '-';
}

/**
* Save to Excel
*/
function save2excel() {
	var elementId = 'excel-report';
	var excelWrapper = '<div id="' + elementId + '" style="display: none;"></div>';
	$( excelWrapper ).appendTo( document.body );
	var tableReport = $('.table-responsive').html();
	$( '#' + elementId ).html( tableReport );
	var fileName = 'DAILY-ATTENDANCE-REPORT' + '-' + new Date().toISOString() + '.xls';
	Export( fileName, elementId ); // Export to Excel
}
</script>
<section class="content-header">
	<h1><i class="fa fa-sign-out text-green"></i> <?=ucwords(strtolower($title));?></h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="callout callout-info">
				<button type="button" onclick="removeCallout()" class="close">×</button>
				<h4>Petunjuk Singkat</h4>
				<ul>
					<li>Jika dalam satu hari <?=$this->session->userdata('_student')?> masuk di salah satu jam <?=$this->session->userdata('school_level') >= 5 ? 'kuliah' : 'pelajaran' ;?>, maka sistem akan menganggapnya hadir meskipun di jam berikutnya dinyatakan <b>Izin</b>, <b>Sakit</b>, ataupun <b>Alpa</b>.</li>
					<li>Jika ada data presensi dengan keterangan <b>"NA"</b>, itu disebabkan adanya kesalahan dalam penginputan data. Kesalahan tersebut dikarenakan adanya ketidaksamaan dalam mengisi status presensi <?=$this->session->userdata('_student')?> yang tidak hadir. Jika <?=$this->session->userdata('_student')?> mulai dari jam pertama sampai jam terakhir tidak hadir dengan keterangan <b>Sakit [ S ]</b>, maka semua presensi pada setiap <?=$this->session->userdata('_subject')?> di hari tersebut harus diisi dengan keterangan <b>Sakit [ S ]</b>, berlaku juga untuk presensi dengan status <b>Izin [ I ]</b>, dan <b>Alpa [ A ]</b>. Untuk memperbaikinya, silahkan klik menu <a href="<?=base_url('academic/student_attendance_report')?>"><b>Rekap Presensi / Tampilkan Semua</b></a>.</li>
				</ul>
			</div>
			<div class="box box-warning">
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="academic_year_id" class="col-sm-5 control-label"><?=$this->session->userdata('_academic_year')?></label>
									<div class="col-sm-7">
										<?=form_dropdown('academic_year_id', $academic_year_dropdown, '', 'class="form-control select2" id="academic_year_id"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="semester" class="col-sm-5 control-label">Semester</label>
									<div class="col-sm-7">
										<?=form_dropdown('semester', ['odd' => 'Ganjil', 'even' => 'Genap'], '', 'class="form-control select2" id="semester"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="class_group_id" class="col-sm-5 control-label">Kelas</label>
									<div class="col-sm-7">
										<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="class_group_id"');?>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="start_date" class="col-sm-5 control-label">Dari Tanggal</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" autocomplete="off" class="form-control datepicker" id="start_date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="end_date" class="col-sm-5 control-label">Sampai Tanggal</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" autocomplete="off" class="form-control datepicker" id="end_date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-5 col-sm-7">
										<div class="btn-group">
											<button type="button" onclick="search(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
											<button type="reset" class="btn btn-sm btn-inverse"><i class="fa fa-refresh"></i> RESET</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-responsive">
						<table width="100%" class="table table-striped table-condensed daily-report"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
