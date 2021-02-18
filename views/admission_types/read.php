<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'TYPES', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
        controller:'admission/admission_types',
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
    		{ header:'Jalur Pendaftaran', renderer:'admission_type' }
    	]
    });

    new FormBuilder( _form , {
	    controller:'admission/admission_types',
	    fields: [
            { label:'Jalur Pendaftaran', name:'admission_type' }
	    ]
  	});
</script>