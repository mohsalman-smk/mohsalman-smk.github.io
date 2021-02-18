<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>

<script type="text/javascript">
    var _grid = 'LECTURERS_AREA', _form = _grid + '_FORM';
    new GridBuilder( _grid , {
        controller:'blog/lecturers_area',
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
                    return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Image');
                },
                exclude_excel: true,
                sorting: false
            },
            { 
                header: '<i class="fa fa-search-plus"></i>', 
                renderer:function(row) {
                    var image = "'" + row.image + "'";
                    return row.image ? 
                        '<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
                },
                exclude_excel: true,
                sorting: false
            },
            { header:'Nama', renderer:'title' },
            { header:'Jabatan', renderer:'caption' },
            { header:'URL', renderer:'url' },
            { header:'E-Mail', renderer:'email' },
            { header:'Instagram', renderer:'instagram' },
            { header:'Twitter', renderer:'twitter' },
            { header:'Facebook', renderer:'facebook' }
        ],
        resize_column: 5
    });

    new FormBuilder( _form , {
        controller:'blog/lecturers_area',
        fields: [
            { label:'Nama', name:'title', type:'text' },
            { label:'Jabatan', name:'caption', type:'select', datasource: {'Kepala Sekolah':'Kepala Sekolah', 'Wakil Kepala Sekolah':'Wakil Kepala Sekolah', 'Guru':'Guru', 'Staf':'Staf', 'Kepala Perpustakaan':'Kepala Perpustakaan', 'Kepala Lab. IPA':'Kepala Lab. IPA', 'Kepala Lab. Komputer':'Kepala Lab. Komputer'} },
            { label:'URL', name:'url', type:'text' },
            { label:'E-Mail', name:'email', type:'text' },
            { label:'Instagram', name:'instagram', type:'text' },
            { label:'Twitter', name:'twitter', type:'text' },
            { label:'Facebook', name:'facebook', type:'text' }
        ]
    });

    function preview(image) {
        $.magnificPopup.open({
          items: {
            src: _BASE_URL + 'media_library/lecturers_area/' + image
          },
          type: 'image'
        });
    }
</script>