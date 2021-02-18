<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'STUDENT_SAY', _form = _grid + '_FORM';
    new GridBuilder( _grid , {
        controller:'blog/student_say',
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
            { header:'Nama Siswa', renderer:'title' },
            { header:'Kelas/Pekerjaan', renderer:'bottom' },
            { header:'Sosial Media', renderer:'url' },
            { header:'Deskripsi', renderer:'caption' }
        ],
        resize_column: 5
    });

    new FormBuilder( _form , {
        controller:'blog/student_say',
        fields: [
            { label:'Nama Siswa', name:'title', type:'text' },
            { label:'Kelas/Pekerjaan', name:'bottom', type:'text' },
            { label:'Sosial Media', name:'url', type:'text' },
            { label:'Deskripsi', name:'caption', type:'textarea' }
        ]
    });

    function preview(image) {
        $.magnificPopup.open({
          items: {
            src: _BASE_URL + 'media_library/student_say/' + image
          },
          type: 'image'
        });
    }
</script>