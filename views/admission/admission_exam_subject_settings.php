<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    DS.Subjects = <?=$subjects_dropdown;?>;
    var _grid = 'SCHEDULE_DETAILS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
        controller:'admission/admission_exam_subject_settings',
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
                header: '<i class="fa fa-calendar"></i>', 
                renderer:function(row) {
                    return Ahref(_BASE_URL + 'admission/admission_exam_schedule_details/index/' + row.id, 'Pengaturan Waktu Pelaksanaan dan Ruang Ujian', '<i class="fa fa-calendar"></i>', '_self');
                },
                exclude_excel: true,
                sorting: false
            },
            { header:'Mata Pelajaran', renderer:'subject_name' }
        ],
        extra_params: { subject_setting_id: <?=$this->uri->segment(4)?> },
        resize_column: 4
    });

    new FormBuilder( _form , {
	    controller:'admission/admission_exam_subject_settings',
	    fields: [
            { label:'Mata Pelajaran', name:'subject_id', type:'select', datasource:DS.Subjects }
        ],
        extra_params: { subject_setting_id: <?=$this->uri->segment(4)?> }
  	});
</script>