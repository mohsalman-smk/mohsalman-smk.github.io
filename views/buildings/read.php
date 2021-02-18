<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'BUILDINGS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
        controller:'reference/buildings',
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
    		{ header:'Nama Gedung', renderer:'building_name' }
    	]
    });

    new FormBuilder( _form , {
	    controller:'reference/buildings',
	    fields: [
            { label:'Nama Gedung', name:'building_name' }
	    ]
  	});
</script>