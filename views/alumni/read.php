<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'ALUMNI', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/alumni',
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
            return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Banner');
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
            return Ahref(_BASE_URL + 'academic/alumni/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header: _IDENTITY_NUMBER, renderer:'identity_number' },
      { header:'Nama Lengkap', renderer:'full_name' },
      {
         header:'L/P',
         renderer: function( row ) {
            return row.gender == 'M' ? 'L' : 'P';
         },
         sort_field: 'gender'
      },
      { header:'TGL Masuk', renderer:'start_date' },
      { header:'TGL Keluar', renderer:'end_date' },
      { header:'Alamat', renderer:'street_address' },
      {
         header:'Alumni ?',
         renderer: function( row ) {
            var is_alumni = row.is_alumni;
            if (is_alumni == 'true') return 'Ya';
            if (is_alumni == 'false') return 'Tidak';
            if (is_alumni == 'unverified') return 'Belum Diverifikasi';
            return '';
         },
         sort_field: 'is_alumni'
      }
   ],
   resize_column: 7,
   to_excel: false,
   can_add: false,
   extra_buttons: '<a class="btn btn-success btn-sm add" href="javascript:void(0)" onclick="alumni_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>' +
   '<button title="Create All Alumni Account" onclick="create_accounts()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-key"></i></button>'
});

new FormBuilder( _form , {
   controller:'academic/alumni',
   fields: [
      { label:'Alumni ?', name:'is_alumni', type:'select', datasource:DS.IsAlumni },
      { label:'Tahun Keluar', name:'end_date', placeholder:'Tanggal Keluar', type:'date' },
      { label:'Alasan Keluar', name:'reason', placeholder:'Alasan', type:'textarea' },
      { label:'Nama Lengkap', name:'full_name', placeholder:'Nama Lengkap' },
      { label:'Alamat Jalan', name:'street_address', placeholder:'Alamat Jalan' },
      { label:'RT', name:'rt', placeholder:'Rukun Tetangga' },
      { label:'RW', name:'rw', placeholder:'Rukun warga' },
      { label:'Nama Dusun', name:'sub_village', placeholder:'Nama Dusun' },
      { label:'Nama Kelurahan / Desa', name:'village', placeholder:'Nama Desa' },
      { label:'Nama Kecamatan', name:'sub_district', placeholder:'Kecamatan' },
      { label:'Nama Kabupaten', name:'district', placeholder:'Kabupaten' },
      { label:'Kode Pos', name:'postal_code', placeholder:'Kode POS' },
      { label:'No. Telepon', name:'phone', placeholder:'Nomor Telepon' },
      { label:'No. Handphone', name:'mobile_phone', placeholder:'Nomor Hand Phone' },
      { label:'Email', name:'email', placeholder:'Alamat Email' }
   ]
});

function preview(image) {
   $.magnificPopup.open({
      items: {
         src: _BASE_URL + 'media_library/students/' + image
      },
      type: 'image'
   });
}

/**
* Create Student Account - Single Activation
* @param String
* @param Number
*/
function create_account( full_name, id ) {
   eModal.confirm('Apakah anda yakin akan mengaktifkan akun dengan nama ' + full_name + ' ?', 'Konfirmasi').then(function() {
      $.post(_BASE_URL + 'academic/alumni/create_student_account', {'id':id}, function(response) {
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
   eModal.confirm('Nama Pengguna dan Kata Sandi Alumni akan digenerate dengan menggunakan ' + _IDENTITY_NUMBER + '. Apakah anda yakin akan mengaktifkan seluruh akun Alumni ?', 'Konfirmasi').then(function() {
      $('body').addClass('loading');
      $.post(_BASE_URL + 'academic/alumni/create_accounts', {}, function( response ) {
         $('body').removeClass('loading');
         var res = H.StrToObject(response);
         H.growl(res.type, H.message(res.message));
      });
   });
}

// Export All Field to Excel
function alumni_reports() {
   $.post(_BASE_URL + 'academic/alumni/alumni_reports', {}, function(response) {
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
      var fileName = 'DATA-ALUMNI' + '-' + new Date().toISOString() + '.xls';
      Export( fileName, 'excel-report' ); // Export to Excel
   }).fail(function(xhr) {
      console.log(xhr);
   });
}
</script>
