<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
	DS.Options = <?=$options;?>;
	var _grid = 'REGISTRANTS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
		controller:'admission/registrants_approved',
			fields: [
				{ 
					header: '<i class="fa fa-edit"></i>', 
					renderer:function(row) {
						return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
					},
					exclude_excel: true,
					sorting: false
				},
				{ header:'No. Daftar', renderer:'registration_number' },
				{ header:'Tanggal Daftar', renderer:'created_at' },
				{ 
					header:'Hasil Seleksi', 
					renderer: function( row ) {
						var selection_result = row.selection_result;
						if (selection_result == 'approved') return 'Diterima';
						return selection_result;
					} 
				},
				{ header:'Nama Lengkap', renderer:'full_name' },
				{ header:'Tanggal Lahir', renderer:'birth_date' },
				{ 
					header:'L/P', 
					renderer: function( row ) {
						return row.gender == 'M' ? 'L' : 'P';
					}, 
					sort_field: 'gender'
				}
			],
		  	resize_column: 2,
		  	to_excel: true,
		  	can_add: false,
		  	can_delete: false,
		  	can_restore: false
	 	});

	new FormBuilder( _form , {
		controller:'admission/registrants_approved',
		fields: [
			{ label:'Hasil Seleksi', name:'selection_result', type:'select', datasource:DS.Options }
		]
	});
</script>