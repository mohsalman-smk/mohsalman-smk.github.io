<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
	input[type="checkbox"] {
		width: 20px;
		height: 20px;
	}
</style>
<script type="text/javascript">
	// Data Source
	DS.Presence = {
		present: 'Hadir',
		sick: 'Sakit',
		permit: 'Izin',
		absent: 'Alpa'
	};
	// Course Class ID
	var course_class_id = '<?=$this->uri->segment(4)?>';

	$( document ).ready( function() {
		// Select2
		$('.select2').select2();

		$('#btn-change').on('click', function() {
			$('#date').removeAttr('disabled');
			$('#btn-change, #btn-save-class-meetings, #show-meeting-attendence, #save-meeting-attendence').hide();
			$('#btn-search').show();
			$('#start_time, #end_time, #discussion').val('');
			$('.meeting-attendence').empty();
		});

		// Datepicker
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			todayBtn: 'linked',
			minDate: '0001-01-01',
			setDate: new Date(),
			todayHighlight: true,
			autoclose: true
		});

		// Input type timepicker
		$('.timepicker').clockpicker({
			placement: 'bottom',
			align: 'left',
			autoclose: true,
			default: 'now'
		});
	});

	/**
	* Search Class Meetings by Class Group ID and Date
	*/
	function is_exist() {
		var values = {
			course_class_id: course_class_id,
			date: $('#date').val()
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/class_meetings/is_exist', values, function( response ) {
				$('body').removeClass('loading');
				var is_exist = response.is_exist;
				if (is_exist) {
					$('#btn-search').hide();
					$('#btn-change, #btn-save-class-meetings, #show-meeting-attendence').show();
					_get_class_meetings();
				} else {
					eModal.confirm('Tidak ditemukan data Kegiatan Belajar Mengajar (KBM) di tanggal ' + H.indo_date(values['date']) + ' untuk ' + _SUBJECT + ' ' + ($('#subject_name').text()) + '. Apakah Anda akan mengajar di kelas ' + ($('#class_group').text()) + ' dengan ' + _SUBJECT + ' ' + ($('#subject_name').text()) + ' di tanggal ' + H.indo_date(values['date']) + ' ?', 'Konfirmasi').then(function() {
						_insert_class_meetings();
					});
				}
			});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}
	}

	/**
	 * Insert/Update Class Meetings by Class Group ID and Date
	 */
	function _insert_class_meetings() {
		var values = {
			course_class_id: course_class_id,
			date: $('#date').val()
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/class_meetings/insert', values, function( response ) {
				$('body').removeClass('loading');
				if (response.status == 'success') {
					// Get Class Meetings
					_get_class_meetings();
					H.growl('success', 'Proses penginputan data Kegiatan Belajar Mengajar (KBM) di tanggal ' + H.indo_date(values['date']) + ' berhasil disimpan. Silahkan lengkapi data jam mulai, jam selesai, materi pembahasan, dan presensi siswa.');
				} else {
					H.growl('error', 'Proses penginputan data Kegiatan Belajar Mengajar (KBM) di tanggal ' + H.indo_date(values['date']) + ' gagal disimpan. Silahkan periksa kembali data Anda.');
				}
			});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}

	}

	/**
	 * Get Class Meetings
	 */
	function _get_class_meetings() {
		var values = {
			course_class_id: course_class_id,
			date: $('#date').val()
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/class_meetings/get_class_meetings', values, function( response ) {
				var res = H.StrToObject(response);
				$('#date').val(res.date);
				$('#start_time').val(res.start_time);
				$('#end_time').val(res.end_time);
				$('#discussion').val(res.discussion || '');
				$('body').removeClass('loading');
				$('#btn-search').hide();
				$('#btn-change, #btn-save-class-meetings, #show-meeting-attendence').show();
				$('#start_time, #end_time, #discussion').removeAttr('disabled');
				$('#date').attr('disabled', 'disabled');
			});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}
	}

	/**
	 * Get Meeting Attendances
	 */
	function _get_meeting_attendence() {
		var values = {
			course_class_id: course_class_id,
			date: $('#date').val()
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/meeting_attendences/get_meeting_attendences', values, function( response ) {
				$('body').removeClass('loading');
				$('#print-meeting-attendence').show();
				var res = H.StrToObject(response);
				var meeting_attendences = res.meeting_attendences;
				var str = '';
				if (meeting_attendences.length) {
					$('#save-meeting-attendence').show();
					str += '<thead class="header">';
					str += '<tr>';
					str += '<th width="30px">NO</th>';
					str += '<th>' + _IDENTITY_NUMBER + '</th>';
					str += '<th>NAMA LENGKAP</th>';
					str += '<th>L/P</th>';
					str += '<th>PRESENSI</th>';
					str += '</tr>';
					str += '</thead>';
					str += '<tbody>';
					var no = 1;
					for (var z in meeting_attendences) {
						str += '<tr>';
						str += '<td>' + no + '.</td>';
						str += '<td>' + meeting_attendences[ z ].identity_number + '</td>';
						str += '<td>' + meeting_attendences[ z ].full_name + '</td>';
						str += '<td>' + meeting_attendences[ z ].gender + '</td>';
						str += '<td>';
						str += '<select class="form-control select2" id="ma_' + meeting_attendences[ z ].id + '">';
						for (var y in DS.Presence) {
							var selected = '';
							if (y == meeting_attendences[ z ].presence) {
								selected = 'selected="selected"';
							}
							str += '<option value="'+ y +'" ' + selected + '>' + DS.Presence[ y ] + '</option>';
						}
						str += '</select>';
						str += '</td>';
						str += '</tr>';
						no++;
					}
					str += '</tbody>';
					$('.meeting-attendence').empty().html(str);
					$(".select2").select2({ width: '100%' });
					$('#save-meeting-attendence').show();
				} else {
					$('.meeting-attendence').empty();
					$('#save-meeting-attendence').hide();
				}
			});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}
	}

	/**
	 * Update Class Meetings
	 */
	function _update_class_meetings() {
		var values = {
			course_class_id: course_class_id,
			date: $('#date').val(),
			start_time: $('#start_time').val(),
			end_time: $('#end_time').val(),
			discussion: $('#discussion').val()
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/class_meetings/update', values, function( response ) {
				$('body').removeClass('loading');
				var type = response.type;
				if (response.type == 'success') {
					var message = 'Proses penginputan data Kegiatan Belajar Mengajar (KBM) di tanggal ' + H.indo_date(values['date']) + ' berhasil disimpan. Silahkan lengkapi data jam mulai, jam selesai, materi pembahasan, dan presensi siswa.';
					if (response.method == 'update') {
						message = 'Data Kegiatan Belajar Mengajar (KBM) di tanggal ' + H.indo_date(values['date']) + ' berhasil diperbaharui.';
					}
					H.growl('success', message);
				} else {
					H.growl('error', 'Proses penginputan data Kegiatan Belajar Mengajar (KBM) di tanggal ' + H.indo_date(values['date']) + ' gagal disimpan. Silahkan periksa kembali data Anda.');
				}
			});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}
	}

	/**
	 * Update Meeting Attendences
	 */
	function _update_meeting_attendences() {
		var rows = $('.meeting-attendence').find(':input');
		var meeting_attendences = [];
		rows.each(function() {
			meeting_attendences.push({
				id:this.id.split('_')[ 1 ],
				presence:$(this).val()
			});
		});

		var values = {
			course_class_id: course_class_id,
			date: $('#date').val(),
			meeting_attendences: JSON.stringify(meeting_attendences)
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/meeting_attendences/update', values, function( response ) {
				$('body').removeClass('loading');
				if (response.status == 'success') {
					H.growl('success', 'Proses penyimpanan data presensi tanggal ' + H.indo_date(values['date']) + ' berhasil.');
					_get_meeting_attendence();
				} else {
					H.growl('warning', 'Terjadi kesalahan dalam proses penyimpanan data presensi tanggal ' + H.indo_date(values['date']) + '. Silahkan periksa kembali data Anda.');
				}
			});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}
	}

	/**
	 * Print Meeting Attendences
	 */
	function _print_meeting_attendance() {
		var values = {
			course_class_id: course_class_id,
			date: $('#date').val()
		}
		if (values['date'] && moment(values['date'], 'YYYY-MM-DD', true).isValid()) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'teacher/class_meetings/print_meeting_attendance', values, function(response) {
				$('body').removeClass('loading');
				var res = H.StrToObject(response);
				if (res.type == 'success') {
					window.open(_BASE_URL + 'media_library/meeting_attendances/' + res.file_name,'_self');
				}
				H.growl('error', 'Format data tidak valid.');
			}).fail(function(xhr) {
	    		console.log(xhr);
	  		});
		} else {
			H.growl('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
		}
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
					<div class="row">
						<div class="col-md-5">
							<form class="form-horizontal">
								<div class="form-group">
									<label for="class_group_id" class="col-sm-5 control-label"><?=$this->session->userdata('_subject')?> :</label>
									<div class="col-sm-7">
										<p class="form-control-static" id="subject_name"><?=$query->subject_name?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="academic_year_id" class="col-sm-5 control-label"><?=$this->session->userdata('_academic_year')?> :</label>
									<div class="col-sm-7">
										<p class="form-control-static"><?=$query->academic_year?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="semester" class="col-sm-5 control-label">Semester :</label>
									<div class="col-sm-7">
										<p class="form-control-static"><?=$query->semester?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="class_group_id" class="col-sm-5 control-label">Kelas :</label>
									<div class="col-sm-7">
										<p class="form-control-static" id="class_group"><?=$query->class_group?></p>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-7">
							<form class="form-inline" style="margin-bottom: 5px">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" autocomplete="off" class="form-control datepicker" id="date" placeholder="Tanggal">
									</div>
								</div>
								<div class="btn-group">
									<button type="submit" class="btn btn-warning" id="btn-search" onclick="is_exist(); return false;"><i class="fa fa-search"></i> CARI</button>
									<button style="display: none;" onclick="return false;" class="btn btn-success" id="btn-change"><i class="fa fa-edit"></i> UBAH TANGGAL</button>
									<button style="display: none;" onclick="_update_class_meetings(); return false;" id="btn-save-class-meetings" class="btn btn-primary" ><i class="fa fa-save"></i> SIMPAN</button>
								</div>
							</form>
							<form>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
										<input disabled="disabled" id="start_time" type="text" autocomplete="off" class="form-control timepicker" placeholder="Jam Mulai">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
										<input disabled="disabled" id="end_time" type="text" autocomplete="off" class="form-control timepicker" placeholder="Jam Selesai">
									</div>
								</div>
								<div class="form-group">
									<textarea disabled="disabled" id="discussion" class="form-control" rows="5" placeholder="Materi Pembahasan"></textarea>
								</div>
							</form>
						</div>
					</div>
					<div class="btn-group">
						<button id="show-meeting-attendence" style="display: none; margin-bottom: 10px;" type="button" onclick="_get_meeting_attendence(); return false;" class="btn btn-warning"><i class="fa fa-search"></i> TAMPILKAN <?=strtoupper($this->session->userdata('_student'))?></button>
						<button id="print-meeting-attendence" style="display: none; margin-bottom: 10px;" type="button" onclick="_print_meeting_attendance(); return false;" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> CETAK</button>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-condensed meeting-attendence"></table>
					</div>
					<button style="display: none;" id="save-meeting-attendence" type="button" onclick="_update_meeting_attendences(); return false;" class="btn btn-block btn-primary"><i class="fa fa-save"></i> SIMPAN PRESENSI</button>
				</div>
			</div>
		</div>
	 </div>
 </section>
