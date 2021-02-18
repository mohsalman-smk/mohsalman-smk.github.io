<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var timezone_list = <?=$timezone_list?>;
    var _grid = 'OPTIONS',
        _form1 = _grid + '_FORM1', // student_join_1
        _form2 = _grid + '_FORM2', // student_join_2
        _form3 = _grid + '_FORM3', // student_join_3
        _form4 = _grid + '_FORM4', // student_join_4
        _form5 = _grid + '_FORM5', // student_join_5
        _form6 = _grid + '_FORM6', // student_join_6
        _form7 = _grid + '_FORM7', // student_join_7
        _form8 = _grid + '_FORM8', // student_join_8
        _form9 = _grid + '_FORM9', // student_join_9
        _form10 = _grid + '_FORM10', // student_join_10
        _form11 = _grid + '_FORM11', // student_join_11
        _form12 = _grid + '_FORM12', // student_join_12
        _form13 = _grid + '_FORM13', // student_join_13
        _form14 = _grid + '_FORM14', // student_join_14
        _form15 = _grid + '_FORM15', // student_join_15
        _form16 = _grid + '_FORM16'; // student_join_16
        _form17 = _grid + '_FORM17', // student_join_17
        _form18 = _grid + '_FORM18', // student_join_18
        _form19 = _grid + '_FORM19', // student_join_19
        _form20 = _grid + '_FORM20', // student_join_20
        _form21 = _grid + '_FORM21', // student_join_21
        _form22 = _grid + '_FORM22', // student_join_22
        _form23 = _grid + '_FORM23', // student_join_23
        _form24 = _grid + '_FORM24', // student_join_24
        _form25 = _grid + '_FORM25', // student_join_25
        _form26 = _grid + '_FORM26', // student_join_26
        _form27 = _grid + '_FORM27', // student_join_27
        _form28 = _grid + '_FORM28', // student_join_28
        _form29 = _grid + '_FORM29', // student_join_29
        _form30 = _grid + '_FORM30', // student_join_30
        _form31 = _grid + '_FORM31', // student_join_31
        _form32 = _grid + '_FORM32'; // student_join_32
        
        
  new GridBuilder( _grid , {
        controller:'settings/student_join',
        fields: [
            {
                header: '<i class="fa fa-edit"></i>', 
                renderer:function(row) {
                    if (row.setting_variable == 'student_join_1') {
                        return UPLOAD(_form1 + '.OnUpload(' + row.id + ')', 'image', 'Upload Siswa 1');
                    }
                    if (row.setting_variable == 'student_join_2') {
                        return UPLOAD(_form2 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_3') {
                        return UPLOAD(_form3 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_4') {
                        return UPLOAD(_form4 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_5') {
                        return UPLOAD(_form5 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_6') {
                        return UPLOAD(_form6 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_7') {
                        return UPLOAD(_form7 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_8') {
                        return UPLOAD(_form8 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_9') {
                        return UPLOAD(_form9 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_10') {
                        return UPLOAD(_form10 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_11') {
                        return UPLOAD(_form11 + '.OnUpload(' + row.id + ')', 'image', 'Upload Siswa 1');
                    }
                    if (row.setting_variable == 'student_join_12') {
                        return UPLOAD(_form12 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_13') {
                        return UPLOAD(_form13 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_14') {
                        return UPLOAD(_form14 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_15') {
                        return UPLOAD(_form15 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_16') {
                        return UPLOAD(_form16 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_17') {
                        return UPLOAD(_form17 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_18') {
                        return UPLOAD(_form18 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_19') {
                        return UPLOAD(_form19 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_20') {
                        return UPLOAD(_form20 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_21') {
                        return UPLOAD(_form21 + '.OnUpload(' + row.id + ')', 'image', 'Upload Siswa 1');
                    }
                    if (row.setting_variable == 'student_join_22') {
                        return UPLOAD(_form22 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_23') {
                        return UPLOAD(_form23 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_24') {
                        return UPLOAD(_form24 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_25') {
                        return UPLOAD(_form25 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_26') {
                        return UPLOAD(_form26 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_27') {
                        return UPLOAD(_form27 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_28') {
                        return UPLOAD(_form28 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_29') {
                        return UPLOAD(_form29 + '.OnUpload(' + row.id + ')', 'image', 'Upload Favicon');
                    }
                    if (row.setting_variable == 'student_join_30') {
                        return UPLOAD(_form30 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_31') {
                        return UPLOAD(_form31 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    if (row.setting_variable == 'student_join_32') {
                        return UPLOAD(_form32 + '.OnUpload(' + row.id + ')', 'image', 'Upload Header');
                    }
                    
                },
                exclude_excel: true,
                sorting: false
            },
            { 
                header: '<i class="fa fa-search-plus"></i>', 
                renderer:function(row) {
                    if (row.setting_variable == 'student_join_1' || row.setting_variable == 'student_join_2' || row.setting_variable == 'student_join_3' || row.setting_variable == 'student_join_4' || row.setting_variable == 'student_join_5' || row.setting_variable == 'student_join_6' || row.setting_variable == 'student_join_7' || row.setting_variable == 'student_join_8' || row.setting_variable == 'student_join_9' || row.setting_variable == 'student_join_10' || row.setting_variable == 'student_join_11' || row.setting_variable == 'student_join_12' || row.setting_variable == 'student_join_13' || row.setting_variable == 'student_join_14' || row.setting_variable == 'student_join_15' || row.setting_variable == 'student_join_16' || row.setting_variable == 'student_join_17' || row.setting_variable == 'student_join_18' || row.setting_variable == 'student_join_19' || row.setting_variable == 'student_join_20' || row.setting_variable == 'student_join_21' || row.setting_variable == 'student_join_22' || row.setting_variable == 'student_join_23' || row.setting_variable == 'student_join_24' || row.setting_variable == 'student_join_25' || row.setting_variable == 'student_join_26' || row.setting_variable == 'student_join_27' || row.setting_variable == 'student_join_28' || row.setting_variable == 'student_join_29' || row.setting_variable == 'student_join_30' || row.setting_variable == 'student_join_31' || row.setting_variable == 'student_join_32') {
                        var image = "'" + row.setting_value + "'";
                        return row.setting_value ? 
                            '<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
                    }
                },
                sorting: false
            },
        { header:'Setting Name', renderer: 'setting_description' },
            { header:'Setting Value', renderer: 'setting_value' }
      ],
        can_add: false,
        can_delete: false,
        can_restore: false,
        resize_column: 3,
        per_page: 50,
        per_page_options: [50, 100]
    });

    /**
     * Header
     */
    new FormBuilder( _form1 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form2 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form3 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form4 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form5 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form6 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form7 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form8 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form9 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form10 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form11 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form12 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form13 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form14 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form15 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form16 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form17 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form18 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form19 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form20 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form21 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form22 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form23 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form24 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form25 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form26 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form27 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form28 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form29 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form30 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form31 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form32 , {
        controller:'settings/student_join',
        fields: [
            { label:'Header', name:'setting_value' }
        ]
    });

    /**
     * Preview Image
     */
    function preview(image) {
        $.magnificPopup.open({
          items: {
            src: '<?=base_url()?>media_library/student_join/' + image
          },
          type: 'image'
        });
    }

    var th = $('thead.thead').find('th')[3];
    $(th).attr('width', '30%');
</script>