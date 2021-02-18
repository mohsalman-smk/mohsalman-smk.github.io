<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.SpecialNeeds = <?=datasource('special_needs')?>;
DS.Religions = <?=datasource('religions')?>;
DS.Residences = <?=datasource('residences')?>;
DS.Transportations = <?=datasource('transportations')?>;
DS.MonthlyIncomes = <?=datasource('monthly_incomes')?>;
DS.StudentStatus = <?=datasource('student_status')?>;
DS.Employments = <?=datasource('employments')?>;
DS.Educations = <?=datasource('educations')?>;
DS.Majors = <?=$ds_majors;?>;
var _grid = 'STUDENTS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
	controller:'academic/students',
	fields: [
		{
			header: '<input type="checkbox" class="check-all">',
			renderer:function(row) {
				return CHECKBOX(row.id, 'id');
			},
			exclude_excel: true,
			sorting: false
		},
		{
			header: '<i class="fa fa-edit"></i>',
			renderer:function(row) {
				return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
			},
			exclude_excel: true,
			sorting: false
		},
		{
			header: '<i class="fa fa-file-image-o"></i>',
			renderer:function(row) {
				return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Photo');
			},
			exclude_excel: true,
			sorting: false
		},
		{
			header: '<i class="fa fa-search-plus"></i>',
			renderer:function(row) {
				var image = "'" + row.photo + "'";
				return row.photo ?
				'<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
			},
			exclude_excel: true,
			sorting: false
		},
		{
			header: '<i class="fa fa-key"></i>',
			renderer:function( row ) {
				return A('create_account(' + "'" + row.full_name + "'" + ', ' + row.id + ')', 'Aktivasi Akun', '<i class="fa fa-key"></i>');
			},
			exclude_excel: true,
			sorting: false
		},
		{
			header: '<i class="fa fa-search"></i>',
			renderer:function(row) {
				return Ahref(_BASE_URL + 'academic/students/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
			},
			exclude_excel: true,
			sorting: false
		},
		{ header:_IDENTITY_NUMBER, renderer:'identity_number' },
		{ header:'Nama Lengkap', renderer:'full_name' },
		{
			header:'Status',
			renderer: function( row ) {
				return row.student_status;
			},
			sort_field: 'student_status'
		},
		{ header:'Tempat Lahir', renderer:'birth_place' },
		{
			header:'Tanggal Lahir',
			renderer: function(row) {
				return row.birth_date && row.birth_date !== '0000-00-00' ? H.indo_date(row.birth_date) : '';
			},
			sort_field: 'birth_date'
		},
		{
			header:'L/P',
			renderer: function( row ) {
				return row.gender == 'M' ? 'L' : 'P';
			},
			sort_field: 'gender'
		}

	],
	resize_column: 7,
	to_excel: false,
	extra_buttons: '<button class="btn btn-success btn-sm add" onclick="student_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></button>' +
	'<button title="Create All Student Account" onclick="create_accounts()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-key"></i></button>'
});

var form_fields = [];
form_fields.push(
	{ label:'Pindahan ?', name:'is_transfer', type:'select', datasource:DS.TrueFalse },
	{ label:'Tanggal Masuk Sekolah', name:'start_date', type:'date' },
	{ label:_IDENTITY_NUMBER, name:'identity_number' }
);

// khusus untuk SD
if (parseInt(_SCHOOL_LEVEL) == 1) {
	form_fields.push(
		{ label:'Apakah pernah PAUD ?', name:'paud', type:'select', datasource:DS.TrueFalse },
		{ label:'Apakah pernah TK ?', name:'tk', type:'select', datasource:DS.TrueFalse }
	);
}

// Jika bukan SD atau SMP
if (_SCHOOL_LEVEL >= 3) {
	form_fields.push(
		{ label: _MAJOR, name:'major_id', type:'select', datasource:DS.Majors }
	);
}

form_fields.push(
	{ label:'Nomor SKHUN Sebelumnya', name:'skhun' },
	{ label:'Nomor Peserta Ujian Sebelumnya', name:'prev_exam_number' },
	{ label:'Nomor Ijazah Sebelumnya', name:'prev_diploma_number' },
	{ label:'Hobi', name:'hobby' },
	{ label:'Cita-cita', name:'ambition' },
	{ label:'Nama Lengkap', name:'full_name' },
	{ label:'Jenis Kelamin', name:'gender', type:'select', datasource:DS.Gender },
	{ label:'NISN', name:'nisn' },
	{ label:'NIK', name:'nik' },
	{ label:'Tempat Lahir', name:'birth_place' },
	{ label:'Tanggal Lahir', name:'birth_date', type:'date' },
	{ label:'Agama', name:'religion_id', type:'select', datasource:DS.Religions },
	{ label:'Berkebutuhan Khusus', name:'special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Alamat Jalan', name:'street_address' },
	{ label:'RT', name:'rt' },
	{ label:'RW', name:'rw' },
	{ label:'Nama Dusun', name:'sub_village' },
	{ label:'Nama Kelurahan/ Desa', name:'village' },
	{ label:'Kecamatan', name:'sub_district' },
	{ label:'Kabupaten', name:'district' },
	{ label:'Kode Pos', name:'postal_code' },
	{ label:'Tempat Tinggal', name:'residence_id', type:'select', datasource:DS.Residences },
	{ label:'Moda Transportasi', name:'transportation_id', type:'select', datasource:DS.Transportations },
	{ label:'Nomor Telepon', name:'phone' },
	{ label:'Nomor HP', name:'mobile_phone' },
	{ label:'Email', name:'email' },
	{ label:'Nomor SKTM', name:'sktm', placeholder:'Surat Keterangan Tidak Mampu' },
	{ label:'Nomor KKS', name:'kks', placeholder:'Kartu Keluarga Sejahtera' },
	{ label:'Nomor KPS', name:'kps', placeholder:'Kartu Pra Sejahtera' },
	{ label:'Nomor KIP', name:'kip', placeholder:'Kartu Indonesia Pintar' },
	{ label:'Nomor KIS', name:'kis', placeholder:'Kartu Indonesia Pintar' },
	{ label:'Kewarganegaraan', name:'citizenship', type:'select', datasource:DS.Citizenship },
	{ label:'Nama Negara', name:'country' },
	{ label:'Nama ayah Kandung', name:'father_name' },
	{ label:'Tahun Lahir Ayah', name:'father_birth_year' },
	{ label:'Pendidikan Ayah', name:'father_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ayah', name:'father_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan  Bulanan Ayah', name:'father_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ayah', name:'father_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Nama Ibu Kandung', name:'mother_name' },
	{ label:'Tahun Lahir Ibu', name:'mother_birth_year' },
	{ label:'Pendidikan Ibu', name:'mother_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ibu', name:'mother_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan  Bulanan Ibu', name:'mother_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ibu', name:'mother_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Nama Wali', name:'guardian_name' },
	{ label:'Tahun Lahir Wali', name:'guardian_birth_year' },
	{ label:'Pendidikan Wali', name:'guardian_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Wali', name:'guardian_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Bulanan Wali', name:'guardian_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Jarak Tempat Tinggal ke Sekolah', name:'mileage', type:'number' },
	{ label:'Waktu Tempuh', name:'traveling_time', type:'number' },
	{ label:'Tinggi Badan', name:'height', type:'number' },
	{ label:'Berat Badan', name:'weight', type:'number' },
	{ label:'Jumlah Saudara Kandung', name:'sibling_number', type:'number' },
	{ label:'Status Peserta Didik', name:'student_status_id', type:'select', datasource:DS.StudentStatus },
	{ label:'Nama Sekolah Sebelumnya', name:'prev_school_name' },
	{ label:'Alamat Sekolah Sebelumnya', name:'prev_school_address' },
	{ label:'Tanggal Keluar', name:'end_date', type:'date' },
	{ label:'Alasan Keluar', name:'reason', type:'textarea' }
);
new FormBuilder( _form , {
	controller:'academic/students',
	fields: form_fields
});

/**
 * Create Student Account - Single Activation
 * @param String
 * @param Number
 */
function create_account( full_name, id ) {
	eModal.confirm('Apakah anda yakin akan mengaktifkan akun dengan nama ' + full_name + ' ?', 'Konfirmasi').then(function() {
		$.post(_BASE_URL + 'academic/students/create_account', {'id':id}, function(response) {
			var res = H.StrToObject(response);
			H.growl(res.type, H.message(res.message));
		});
	});
}

/**
 * Create All Student Account - All Activation
 * @param String
 * @param Number
 */
function create_accounts() {
	eModal.confirm('Nama Pengguna dan Kata Sandi ' + _STUDENT + ' akan digenerate dengan menggunakan ' + _IDENTITY_NUMBER + '. Apakah anda yakin akan mengaktifkan seluruh akun ' + _STUDENT + ' ?', 'Konfirmasi').then(function() {
		$('body').addClass('loading');
		$.post(_BASE_URL + 'academic/students/create_accounts', {}, function( response ) {
			$('body').removeClass('loading');
			var res = H.StrToObject(response);
			H.growl(res.type, H.message(res.message));
		});
	});
}

function preview(image) {
	$.magnificPopup.open({
		items: {
			src: _BASE_URL + 'media_library/students/' + image
		},
		type: 'image'
	});
}

// Export All Field to Excel
function student_reports() {
	$.post(_BASE_URL + 'academic/students/student_reports', {}, function(response) {
		var res = H.StrToObject(response);
		var header = {};
		for (var x in res[ 0 ]) {
			if ((_SCHOOL_LEVEL == 1 || _SCHOOL_LEVEL == 2) && x == 'program_keahlian') continue;
			if (_SCHOOL_LEVEL >= 5 && x == 'identity_number') continue;
			if (_SCHOOL_LEVEL >= 5 && x == 'nisn') continue;
			if (x == 'id') continue;
			if (x == 'pilihan_1') continue;
			if (x == 'pilihan_2') continue;
			if (x == 'nomor_pendaftaran') continue;
			if (x == 'nomor_peserta_ujian') continue;
			if (x == 'hasil_seleksi') continue;
			if (x == 'gelombang_pendaftaran') continue;
			if (x == 'jalur_pendaftaran') continue;
			if (x == 'jenis_pendaftaran') continue;
			if (x == 'daftar_ulang') continue;
			header[ x ] = x.replace('_', ' ').toUpperCase();
		}
		var table = '<table>';
		table += '<tr>';
		for (var y in header) {
			table += '<th>' + header[ y ] + '</th>';
		}
		table += '</tr>'
		for (var z in res) {
			table += '</tr>';
			for (var zz in header) {
				table += '<td>' + (res[ z ][ zz ] ? res[ z ][ zz ] : '-') + '</td>';
			}
			table += '</tr>';
		}
		table += '</table>';
      var excelWrapper = '<div id="excel-report" style="display: none;"></div>';
      $( excelWrapper ).appendTo( document.body );
      $( '#excel-report' ).html(table);
		var fileName = 'LAPORAN-DATA-' + _STUDENT.toUpperCase() + '-' + new Date().toISOString() + '.xls';
      Export( fileName, 'excel-report' ); // Export to Excel
	}).fail(function(xhr) {
		console.log(xhr);
	});
}
</script>
