<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var timezone_list = <?=$timezone_list?>;
    var _grid = 'OPTIONS',
        _form1 = _grid + '_FORM1', // video_tour_title
        _form2 = _grid + '_FORM2', // video_tour_description
        _form3 = _grid + '_FORM3'; // video_tour_url
        _form4 = _grid + '_FORM4', // About_image
        _form5 = _grid + '_FORM5', // About_image
        _form6 = _grid + '_FORM6', // About_image
        _form7 = _grid + '_FORM7', // About_image
        _form8 = _grid + '_FORM8', // About_image
        _form9 = _grid + '_FORM9', // About_image
        
        
	new GridBuilder( _grid , {
        controller:'settings/appearance',
        fields: [
            {
                header: '<i class="fa fa-edit"></i>', 
                renderer:function(row) {
                    if (row.setting_variable == 'video_tour_title') {
                        return A(_form1 + '.OnEdit(' + row.id + ')');
                    }
                    if (row.setting_variable == 'video_tour_description') {
                        return A(_form2 + '.OnEdit(' + row.id + ')');
                    }
                    if (row.setting_variable == 'video_tour_url') {
                        return A(_form3 + '.OnEdit(' + row.id + ')');
                    }
                    if (row.setting_variable == 'about_image') {
                        return UPLOAD(_form4 + '.OnUpload(' + row.id + ')', 'image', 'Upload Gambar');
                    }
                    if (row.setting_variable == 'about_title') {
                        return A(_form5 + '.OnEdit(' + row.id + ')');
                    }
                    if (row.setting_variable == 'about_description') {
                        return A(_form6 + '.OnEdit(' + row.id + ')');
                    }
                    if (row.setting_variable == 'header_1_image') {
                        return UPLOAD(_form6 + '.OnUpload(' + row.id + ')', 'image', 'Upload Gambar');
                    }
                    if (row.setting_variable == 'header_2_image') {
                        return UPLOAD(_form7 + '.OnUpload(' + row.id + ')', 'image', 'Upload Gambar');
                    }
                    if (row.setting_variable == 'header_3_image') {
                        return UPLOAD(_form8 + '.OnUpload(' + row.id + ')', 'image', 'Upload Gambar');
                    }
                    
                },
                exclude_excel: true,
                sorting: false
            },
            { 
                header: '<i class="fa fa-search-plus"></i>', 
                renderer:function(row) {
                    if (row.setting_variable == 'favicon' || row.setting_variable == 'about_image' || row.setting_variable == 'header_1_image' || row.setting_variable == 'header_2_image' || row.setting_variable == 'header_3_image') {
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
     * title
     */
    new FormBuilder( _form1 , {
        controller:'settings/appearance',
        fields: [
            { label:'Video Tour Title', name:'setting_value', type:'text', placeholder:'Judul Video'  }
        ]
    });

    /**
     * description
     */
    new FormBuilder( _form2 , {
        controller:'settings/appearance',
        fields: [
            { label:'Video Tour Description', name:'setting_value', type:'textarea', placeholder:'Deskripsi Video, untuk baris baru gunakan tanda <br>' }
        ]
    });

    /**
     * title
     */
    new FormBuilder( _form3 , {
        controller:'settings/appearance',
        fields: [
            { label:'Video Tour URL', name:'setting_value', type:'text', placeholder:'URL Video'  }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form4 , {
        controller:'settings/appearance',
        fields: [
            { label:'About Image', name:'setting_value' }
        ]
    });

    /**
     * title
     */
    new FormBuilder( _form5 , {
        controller:'settings/appearance',
        fields: [
            { label:'About Title', name:'setting_value', type:'text', placeholder:'Selamat Datang'  }
        ]
    });


    /**
     * description
     */
    new FormBuilder( _form6 , {
        controller:'settings/appearance',
        fields: [
            { label:'About Description', name:'setting_value', type:'textarea', placeholder:'Deskripsi Selamat Datang, untuk baris baru gunakan tanda <br>' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form7 , {
        controller:'settings/appearance',
        fields: [
            { label:'Header 1 Image', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form8 , {
        controller:'settings/appearance',
        fields: [
            { label:'Header 2 Image', name:'setting_value' }
        ]
    });

    /**
     * Header
     */
    new FormBuilder( _form9 , {
        controller:'settings/appearance',
        fields: [
            { label:'Header 3 Image', name:'setting_value' }
        ]
    });


    /**
     * Preview Image
     */
    function preview(image) {
        $.magnificPopup.open({
          items: {
            src: '<?=base_url()?>media_library/images/' + image
          },
          type: 'image'
        });
    }

    var th = $('thead.thead').find('th')[3];
    $(th).attr('width', '30%');
</script>