<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'SPECIAL_NEEDS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
        controller:'reference/special_needs',
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
    		{ header:'Kebutuhan Khusus', renderer:'option_name' }
    	]
    });

    new FormBuilder( _form , {
	    controller:'reference/special_needs',
	    fields: [
            { label:'Kebutuhan Khusus', name:'option_name' }
	    ]
  	});
</script>