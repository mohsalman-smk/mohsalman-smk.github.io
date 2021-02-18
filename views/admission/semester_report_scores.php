<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
.table > thead > tr {
	background-color: #f9f9f9;
	border-bottom: 1px solid #d2d6de;
}
.table > thead > tr > th {
	text-align: center;
}
.table > thead > tr > th, .table > tbody > tr > td {
	border: 1px solid #d2d6de;
}
.number {
	text-align: right;
	font-weight: bold;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$(".select2").select2({ width: '100%' });
});

/*
* @var Page Number
*/
var page_number = 0;

/*
* @var Per Page
*/
var per_page = 10;

/*
* @var Total Page
*/
var total_page = 0;

/*
* @var Total Rows
*/
var total_rows = 0;

/*
* button next
*/
function Next() {
	$('body').addClass('loading');
	page_number++;
	getProspectiveStudents();
};

/*
* button previous
*/
function Prev() {
	$('body').addClass('loading');
	page_number--;
	getProspectiveStudents();
};

/*
* button first
*/
function First() {
	$('body').addClass('loading');
	page_number = 0;
	getProspectiveStudents();
};

/*
* button last
*/
function Last() {
	$('body').addClass('loading');
	page_number = total_page - 1;
	getProspectiveStudents();
};

/*
* render Pagination Info
*/
PaginationInfo = function PaginationInfo() {
	var page_info = 'Page ' + ((total_rows == 0) ? 0 : (page_number + 1));
	page_info += ' of ' + total_page.to_money();
	page_info += ' &sdot; Total : ' + total_rows.to_money() + ' Rows.';
	$('.page-info').html(page_info);
};

/*
* Set pagination button
*/
function PaginationButton() {
	$('.box-footer').show();
	$('.next').attr('onclick', 'Next()');
	$('.previous').attr('onclick', 'Prev()');
	$('.first').attr('onclick', 'First()');
	$('.last').attr('onclick', 'Last()');
	$('.per-page').attr('onchange', 'SetPerPage()');
	$(".previous, .first").prop('disabled', page_number == 0);
	$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
};

/*
* select per-page
*/
function SetPerPage() {
	$('body').addClass('loading');
	page_number = 0;
	per_page = $('.per-page option:selected').val();
	getProspectiveStudents();
};

/**
* Get Prospective Students
*/
function getProspectiveStudents() {
	$('body').addClass('loading');
	$('.save-scores, .save-excel').attr('disabled', 'disabled');
	var values = {
		admission_year_id: $('#admission_year_id').val(),
		admission_type_id: $('#admission_type_id').val(),
		major_id: $('#major_id').val() || 0,
		page_number: page_number,
		per_page: per_page
	};
	$.post(_BASE_URL + 'admission/semester_report_scores/get_prospective_students', values, function( response ) {
		var res = H.StrToObject(response);
		total_page = res.total_page;
		total_rows = res.total_rows;
		var students = res.students;
		var str = '';
		if (students.length) {
			PaginationButton();
			PaginationInfo();
			if (total_rows <= per_page) $(".next").prop('disabled', true);
			$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px">NO.</th>';
			str += '<th>NO. PENDAFTARAN</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '<th>MATA PELAJARAN</th>';
			str += '<th width="10%">NILAI</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = (page_number * per_page) + 1;
			for (var z in students) {
				str += '<tr>';
				str += '<td>' + (no) + '.</td>';
				str += '<td>' + students[ z ].registration_number + '</td>';
				str += '<td>' + students[ z ].full_name + '</td>';
				str += '<td>' + students[ z ].subject_name + '</td>';
				str += '<td class="score_' + students[ z ].id + '">';
				str += '<input class="form-control input-sm number" type="text" name="score" id="score_' + students[ z ].id + '" value="' + students[ z ].score + '" />';
				str += '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('table.prospective-students').empty().html(str);
			$('.save-scores, .save-excel').removeAttr('disabled');
		} else {
			$('table.prospective-students').empty();
		}
		$('body').removeClass('loading');
	});
}

// Delete Permanent Data
function saveScores() {
	$('body').addClass('loading');
	$('.save-scores').attr('disabled', 'disabled');
	var rows = $('input[name="score"]');
	var scores = [];
	rows.each(function() {
		scores.push({
			id:this.id.split('_')[ 1 ],
			score:$(this).val()
		});
	});

	if (scores.length) {
		var values = {
			scores: JSON.stringify(scores)
		};
		$.post(_BASE_URL + 'admission/semester_report_scores/save', values, function( response ) {
			H.growl('info', H.message(response.message));
			$('body').removeClass('loading');
			$('.save-scores').removeAttr('disabled');
			getProspectiveStudents();
		});
	} else {
		H.growl('info', 'Tidak ada data yang tersimpan');
	}
}

function save2excel() {
	var elementId = 'excel-report';
	var excelWrapper = '<div id="' + elementId + '" style="display: none;"></div>';
	$( excelWrapper ).appendTo( document.body );
	var tableReport = $('.table-responsive').html();
	$( '#' + elementId ).html( tableReport );
	var inputs = $( '#' + elementId ).find('input[name="score"]');
	inputs.each(function() {
		var score = $('#'+this.id).val();
		$('#' + elementId).find('td.' + this.id).text(score);
	});
	var fileName = 'REKAP-NILAI-RAPOR-' + (_SCHOOL_LEVEL >= 5 ? 'PMB':'PPDB') + '-TAHUN-' + $('#admission_year_id option:selected').text() + '-JALUR-PENDAFTARAN-' + $('#admission_type_id option:selected').text();
	fileName += ($('#major_id').length ? '-PROGRAM-KEAHLIAN-' + $('#major_id option:selected').text() : '');
	fileName += fileName + '-' + new Date().toISOString() + '.xls';
	Export( fileName, elementId ); // Export to Excel
}
</script>
<section class="content-header">
	<h1><i class="fa fa-sign-out text-green"></i> <?=ucwords(strtolower($title));?></h1>
</section>
<section class="content">
	<div class="box box-warning">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label for="admission_year_id" class="col-sm-3 control-label"><?=$this->session->userdata('school_level') >= 5 ? 'PMB' : 'PPDB';?> Tahun</label>
								<div class="col-md-9">
									<?=form_dropdown('admission_year_id', $ds_admission_years, '', 'class="form-control select2" id="admission_year_id"');?>
								</div>
							</div>
							<div class="form-group">
								<label for="admission_type_id" class="col-sm-3 control-label">Jalur Pendaftaran</label>
								<div class="col-md-9">
									<?=form_dropdown('admission_type_id', $ds_admission_types, '', 'class="form-control select2" id="admission_type_id"');?>
								</div>
							</div>
							<?php if (in_array($this->session->userdata('school_level'), have_majors())) { ?>
								<div class="form-group">
									<label for="major_id" class="col-sm-3 control-label"><?=$this->session->userdata('_major')?></label>
									<div class="col-md-9">
										<?=form_dropdown('major_id', $ds_majors, '', 'class="form-control select2" id="major_id"');?>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<div class="btn-group">
										<button type="button" onclick="getProspectiveStudents(); return false;" class="btn btn-sm btn-info"><i class="fa fa-search"></i> CARI DATA</button>
										<button disabled="disabled" type="button" onclick="saveScores(); return false;" class="btn btn-sm btn-success save-scores"><i class="fa fa-save"></i> SIMPAN PERUBAHAN NILAI</button>
										<button disabled="disabled" type="button" onclick="save2excel(); return false;" class="btn btn-sm btn-warning save-excel"><i class="fa fa-file-excel-o"></i> EXPORT KE FILE EXCEL</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed prospective-students"></table>
			</div>
		</div>
		<div class="box-footer" style="display: none;">
			<div class="row">
				<div class="col-md-9">
					<em class="page-info"></em>
				</div>
				<div class="col-md-3">
					<div class="btn-group pull-right">
						<button type="button" class="btn bg-navy btn-sm first" title="First"><i class="fa fa-angle-double-left"></i></button>
						<button type="button" class="btn bg-navy btn-sm previous" title="Prev"><i class="fa fa-angle-left"></i></button>
						<button type="button" class="btn bg-navy btn-sm next" title="Next"><i class="fa fa-angle-right"></i></button>
						<button type="button" class="btn bg-navy btn-sm last" title="Last"><i class="fa fa-angle-double-right"></i></button>
						<div class="btn-group">
							<select class="btn bg-navy input-sm per-page" style="padding: 5px 5px" onchange="SetPerPage()">
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="0">All</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
