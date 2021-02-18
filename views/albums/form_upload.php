<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/plupload/plupload.full.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/plugins/plupload/jquery.plupload.queue.min.js')?>"></script>
<section class="content-header">
   <h1><i class="fa fa-upload text-green"></i> <?=$title;?></h1>
</section>
<section class="content">
   <div class="box">
      <div class="box-header with-border">
         <div class="row">
            <div class="col-sm-12">
               <div class="box-tools">
                  <button class="btn btn-primary btn-sm" id="pickfiles"><i class="fa fa-paperclip"></i> PILIH GAMBAR</button>
                  <button class="btn btn-info btn-sm" id="uploadFile"><i class="fa fa-upload"></i> UPLOAD</button>
                  <a href="<?=site_url('media/albums');?>" class="btn btn-default btn-sm pull-right"><i class="fa fa-mail-forward"></i> KEMBALI</a>
                  <span id="success"></span>
                  <span id="failled"></span>
               </div>
            </div>
         </div>
      </div>
      <div class="box-body table-responsive no-padding">
         <table class="table table-hover">
            <thead id="thead" style="display: none;">
               <tr>
                  <th>NAMA FILE</th>
                  <th width="20%">TIPE</th>
                  <th width="20%">UKURAN</th>
                  <th width="10%">STATUS</th>
               </tr>
            </thead>
            <tbody id="listFiles"></tbody>
         </table>
      </div>
   </div>
</section>
<script type="text/javascript">
$(document).ready(function() {
   var success = 0, failled =0;
   var uploader = new plupload.Uploader({
      runtimes: 'html5',
      browse_button: 'pickfiles',
      container: $('#container_upload')[0],
      url: '<?=$action;?>',
      filters: {
         mime_types: [
            { title:"Image files", extensions:'jpg,png,jpeg' }
         ]
      },
      init: {
         PostInit: function() {
            $('#listFiles').html('');
            $('#uploadFile').on('click', function() {
               $('body').addClass('loading');
               uploader.start();
               return false;
            });
         },
         FilesAdded: function(up, files) {
            $('#thead').show();
            var HTML = '';
            plupload.each(files, function(res) {
               HTML += '<tr id="row_' + res.size + '">'
               + '<td>' + res.name + '</td>'
               + '<td>' + res.type + '</td>'
               + '<td>' + plupload.formatSize(res.size) + '</td>'
               + '<td id="status_'+res.size+'"></td>'
               + '</tr>';
            });
            $('#listFiles').html( HTML );
         },
         FileUploaded: function(up, file, info) {
            var res = H.StrToObject( info.response );
            if (res.type == 'success') {
               success++;
               $('#success').html(success + ' file uploaded.');
               $('#status_' + file.size).html('<i class="fa fa-check-circle-o"></i>');
            } else {
               failled++;
               $('#failled').html(failled + ' file not uploaded.');
               $('#status_' + file.size).html('<i class="fa fa-close"></i>');
            }
         },
         UploadComplete:function(up, file) {
            $('body').removeClass('loading');
         },
         Error: function(up, err) {
            console.log(up, err);
         }
      }
   });
   uploader.init();
});
</script>
