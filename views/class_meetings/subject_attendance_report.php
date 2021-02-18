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
		subject_id: $('#subject_id').val(),
		employee_id: $('#employee_id').val(),
		start_date: $('#start_date').val(),
		end_date: $('#end_date').val()
	}
	if (values['start_date'] &&
	moment(values['start_date'], 'YYYY-MM-DD', true).isValid() &&
	values['end_date'] &&
	moment(values['end_date'], 'YYYY-MM-DD', true).isValid()
) {
	_get_summary_report(values);
	_get_detail_report(values);
} else {
	H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
}
}
/**
* All Attendance Report
*/
function _get_summary_report( values ) {
	$('body').addClass('loading');
	$.post(_BASE_URL + 'academic/subject_attendance_report/summary_report', values, function( response ) {
		var res = H.StrToObject(response);
		var str = '';
		if (res.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th colspan="10">';
			str += 'ATTENDANCE SUMMARY REPORT | ' + $('#employee_id option:selected').text().toUpperCase() + '<button type="button" onclick="save2excel(\'summary\'); return false;" class="btn btn-xs btn-success pull-right"><i class="fa fa-file-excel-o"></i> SIMPAN SEBAGAI FILE EXCEL</button>';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th>TANGGAL</th>';
			str += '<th>JAM MULAI</th>';
			str += '<th>JAM SELESAI</th>';
			str += '<th width="40%">MATERI PELAJARAN</th>';
			str += '<th>H</th>';
			str += '<th>S</th>';
			str += '<th>I</th>';
			str += '<th>A</th>';
			str += '<th>TOTAL</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = 1;
			for (var z in res) {
				str += '<tr>';
				str += '<td>' + no + '.</td>';
				str += '<td>' + res[ z ].date + '</td>';
				str += '<td>' + res[ z ].start_time + '</td>';
				str += '<td>' + res[ z ].end_time + '</td>';
				str += '<td>' + res[ z ].discussion + '</td>';
				str += '<td>' + res[ z ].H + '</td>';
				str += '<td>' + res[ z ].S + '</td>';
				str += '<td>' + res[ z ].I + '</td>';
				str += '<td>' + res[ z ].A + '</td>';
				str += '<td>' + res[ z ].total + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('.summary-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.summary-report').empty();
			toastr.info('Data tidak ditemukan!', 'Info' );
		}
		$('body').removeClass('loading');
	});
}

/**
* Monthly Report
*/
function _get_detail_report( values ) {
	$('body').addClass('loading');
	$.post(_BASE_URL + 'academic/subject_attendance_report/detail_report', values, function( response ) {
		var dates = response.dates;
		var query = response.query;
		if (query.length) {
			var students = [];
			for (var x in query) {
				var row = query[ x ];
				if (!inArray('identity_number', row.identity_number, students)) {
					var student = {
						'identity_number': row.identity_number,
						'full_name': row.full_name
					}
					students.push(student);
				}
			}
			var str = '';
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th colspan="' + (7 + dates.length) + '">';
			str += 'ATTENDANCE DETAIL REPORT | ' + $('#employee_id option:selected').text().toUpperCase() + '<button type="button" onclick="save2excel(\'detail\'); return false;" class="btn btn-xs btn-success pull-right"><i class="fa fa-file-excel-o"></i> SIMPAN SEBAGAI FILE EXCEL</button>';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th width="150px">' + _IDENTITY_NUMBER + '</th>';
			str += '<th width="250px">NAMA PESERTA DIDIK</th>';
			for (var x in dates) {
				var is_sunday = '';
				if (moment(dates[ x ]).format('dddd') == 'Sunday') {
					is_sunday = 'class="text-red"';
				}
				str += '<th ' + is_sunday + ' width="30px" title="' + dates[ x ] + '">' + dates[ x ].substr(8, 2) + '</th>';
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
				str += '<tr>';
				str += '<td width="30px">' + no + '</td>';
				str += '<td>' + students[ y ].identity_number + '</td>';
				str += '<td>' + students[ y ].full_name + '</td>';
				var H = 0, S = 0, I = 0, A = 0;
				for (var z in dates) {
					var presence = searchAttendance(students[ y ].identity_number, dates[ z ], query);
					if (presence == 'H') H++;
					if (presence == 'S') S++;
					if (presence == 'I') I++;
					if (presence == 'A') A++;
					var is_sunday = '';
					if (moment(dates[ z ]).format('dddd') == 'Sunday') {
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
			$('.detail-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.detail-report').empty();
			H.growl('info', 'Data tidak ditemukan!');
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
function save2excel( type ) {
	var elementId = 'excel-' + type + '-report';
	var excelWrapper = '<div id="' + elementId + '" style="display: none;"></div>';
	$( excelWrapper ).appendTo( document.body );
	var tableReport = $('.table-' + type + '-report').html();
	$( '#' + elementId ).html( tableReport );
	var fileName = 'ATTENDANCE-' + type.toUpperCase() + '-REPORT | ' + $('#employee_id option:selected').text().toUpperCase();
	fileName += fileName + '-' + new Date().toISOString() + '.xls';
	Export( fileName, elementId ); // Export to Excel
}
</script>
<section class="content-header">
	<h1><i class="fa fa-sign-out text-green"></i> <?=ucwords(strtolower($title));?></h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-md-4 col-sm-12 col-xs-12">
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
							</div>
							<div class="col-md-8 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="class_group_id" class="col-sm-4 control-label">Kelas</label>
									<div class="col-sm-8">
										<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="class_group_id"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="employee_id" class="col-sm-4 control-label"><?=$this->session->userdata('school_level') >= 5 ? 'Dosen Pengampu' : 'Guru Pengajar'?></label>
									<div class="col-sm-8">
										<?=form_dropdown('employee_id', $employee_dropdown, '', 'class="form-control select2" id="employee_id"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="subject_id" class="col-sm-4 control-label"><?=$this->session->userdata('_subject')?></label>
									<div class="col-sm-8">
										<?=form_dropdown('subject_id', $subject_dropdown, '', 'class="form-control select2" id="subject_id"');?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<div class="btn-group">
											<button type="button" onclick="search(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
											<button type="reset" class="btn btn-sm btn-inverse"><i class="fa fa-refresh"></i> RESET</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-summary-report table-responsive">
						<table width="100%" class="table table-striped table-condensed summary-report"></table>
					</div>
					<div class="table-detail-report table-responsive">
						<table width="100%" class="table table-striped table-condensed detail-report"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
