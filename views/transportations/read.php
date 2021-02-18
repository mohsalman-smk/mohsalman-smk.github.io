<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'TRANSPORTATIONS', _form = _grid + '_FORM';
    new GridBuilder( _grid , {
        controller:'academic/transportations',
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
            { header:'Moda Transportasi', renderer:'option_name' }
        ]
    });

    new FormBuilder( _form , {
        controller:'academic/transportations',
        fields: [
            { label:'Moda Transportasi', name:'option_name' }
        ]
    });
</script>