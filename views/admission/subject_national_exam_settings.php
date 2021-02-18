<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    DS.AcademicYears = <?=$academic_years_dropdown;?>;
    DS.AdmissionTypes = <?=$admission_types_dropdown;?>;
    DS.Majors = <?=$majors_dropdown;?>;
    var _grid = 'SUBJECT_SEMESTER_REPORT_SETTIGNS', _form = _grid + '_FORM';
    var grid_field = [
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
            header: '<i class="fa fa-book"></i>', 
            renderer:function(row) {
                return Ahref('subject_national_exam_details/index/' + row.id, 'Pengaturan Mata Pelajaran', '<i class="fa fa-book"></i>', '_self');
            },
            exclude_excel: true,
            sorting: false
        },
        { header:_ACADEMIC_YEAR, renderer:'academic_year' },
        { header:'Jalur Pendaftaran', renderer:'admission_type' }
    ];
    // if SMA / SMK / PT
    if (_SCHOOL_LEVEL >= 3) {
        grid_field.push(
            { header:_MAJOR, renderer:'major_name' }
        );
    }
	new GridBuilder( _grid , {
        controller:'admission/subject_national_exam_settings',
        fields: grid_field,
        resize_column: 4
    });

    var form_field = [
        { label:_ACADEMIC_YEAR, name:'academic_year_id', type:'select', datasource:DS.AcademicYears },
        { label:'Jalur Pendaftaran', name:'admission_type_id', type:'select', datasource:DS.AdmissionTypes }
    ];
    // if SMA / SMK / PT
    if (_SCHOOL_LEVEL >= 3) {
        form_field.push(
            { label:_MAJOR, name:'major_id', type:'select', datasource:DS.Majors }
        );
    }
    
    new FormBuilder( _form , {
	    controller:'admission/subject_national_exam_settings',
	    fields: form_field
  	});
</script>