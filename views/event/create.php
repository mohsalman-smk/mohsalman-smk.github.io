<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/css/jquery-ui.css');?>
<script type="text/javascript" src="<?=base_url('assets/js/jquery-ui.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<script type="text/javascript">
	/** @namespace tinymce */
	tinymce.init({
      selector: "#post_content",
      theme: 'modern',
      paste_data_images:true,
      relative_urls: false,
      remove_script_host: false,
      toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
      toolbar2: "print preview forecolor backcolor emoticons",
      image_advtab: true,
      plugins: [
         "advlist autolink lists link image charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code fullscreen",
         "insertdatetime nonbreaking save table contextmenu directionality",
         "emoticons template paste textcolor colorpicker textpattern"
      ],
      automatic_uploads: true,
      images_upload_url: _BASE_URL + 'blog/event/do_upload',
      file_picker_types: 'image', 
      file_picker_callback: function(cb, value, meta) {
         var input = document.createElement('input');
         input.setAttribute('type', 'file');
         input.setAttribute('accept', 'image/*');
         input.onchange = function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
               var id = 'post-image-' + (new Date()).getTime();
               var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
               var blobInfo = blobCache.create(id, file, reader.result);
               blobCache.add(blobInfo);
               cb(blobInfo.blobUri(), { title: file.name });
            };
         };
         input.click();
      }
   });

	/* Save Post Category */
	function save_post_category() {
		var values = {
			category_name: $('#category_name').val()
		}
		$.post(_BASE_URL + 'blog/post_categories/save', values, function(response) {
			var res = H.StrToObject(response);
			if (res.type == 'error') {
				H.growl(res.type, H.message(res.message));
			}
			if (res.type == 'success') {
				var str = '<div class="checkbox">' 
				+ '<label>' 
				+ '<input type="checkbox" class="checkbox" name="post_categories[]" value="'+res.insert_id+'">' 
				+ values.category_name
				+ '</label>'
				+ '</div>';
				var el = $("div.checkbox:last"); 
				$( str ).insertAfter(el);
				$('.category-form').modal('hide');
			}
		});
	}

	/** @namespace event */
	$( document ).ready( function() {
		/* Show Form Add New category */
		$('.add-new-category').on('click', function(e) {
			e.preventDefault();
			$('#category_name').val('');
			$('.category-form').modal('show');
		});

		// Image Preview 
		$('#post_image').on('change', function() {
			$('#preview').show();
			H.preview(this);
		});

		// remove Image Preview 
		$('#preview').on('dblclick', function() {
			$('#preview').hide().removeAttr('src');
		});

		/* Tags */
		$('#post_tags').tagsInput({
			'width': 'auto',
			'autocomplete_url': _BASE_URL + 'blog/tags/autocomplete',
		   'interactive':true,
		   'defaultText':'Add New',
		   'delimiter': [', '],   // Or a string with a single delimiter. Ex: ';'
		   'removeWithBackspace' : true,
		   'minChars' : 0,
		   'maxChars' : 0, // if not provided there is no limit
		   'placeholderColor' : '#666666'
		});

		/* Submit event */
		$( '#submit' ).on('click', function(event) {
			event.preventDefault();
			$('body').addClass('loading');
			var categories = $("input.checkbox:checked");
			var post_categories = [];
			categories.each( function() {
			  post_categories.push($(this).val());
			});

			var fill_data = new FormData();
			fill_data.append('post_title', $('#post_title').val());
			fill_data.append('waktu', $('#waktu').val());
			fill_data.append('tempat', $('#tempat').val());
			fill_data.append('tanggal', $('#tanggal').val());
			fill_data.append('bulan', $('#bulan').val());
			fill_data.append('tahun', $('#tahun').val());
			fill_data.append('post_content', tinyMCE.get('post_content').getContent());
			fill_data.append('post_categories', post_categories.join(','));
			fill_data.append('post_status', $('#post_status').val());
			fill_data.append('post_visibility', $('#post_visibility').val());
			fill_data.append('post_comment_status', $('#post_comment_status').val());
			fill_data.append('post_image', $('input[type=file]')[ 0 ].files[ 0 ]);
			fill_data.append('post_tags', $('#post_tags').val());
			// send data
			$.ajax({
				url: '<?=$action;?>',
				type: 'POST',
				data: fill_data,
				contentType: false,
				processData: false,
				success : function( response ) {
					var res = H.StrToObject( response );
					H.growl(res.type, H.message(res.message));
					if (res.action == 'save')  {
						$('#post_tags').importTags('');
						$('input[type="text"], input[type="file"]').val('');
						$('#post_status').val('publish');
						$('#post_visibility').val('public');
						$('#post_status').val('open');
						tinyMCE.get('post_content').setContent('');
						$("input.checkbox").prop('checked', false);
						$('#post_title').focus();
						$('#waktu').focus();
						$('#tempat').focus();
						$('#tanggal').focus();
						$('#bulan').focus();
						$('#tahun').focus();
						$('#preview').removeAttr('src').hide();
					}
					$('body').removeClass('loading');
				}
			});   
		});  
	});
</script>
<section class="content-header">
   <h1><i class="fa fa-edit text-green"></i> <?=$title;?></h1>
</section>
<section class="content">
	<form>
		<div class="row">
			<div class="col-lg-8">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="form-group" style="margin-bottom: 10px;">
							<input id="post_title" name="post_title" value="<?=($query ? $query->post_title : '');?>" autofocus required="true" type="text" class="form-control input-lg" placeholder="Judul Acara" style="font-size: 16px">
						</div>
						<div class="form-group">
							<textarea rows="25" id="post_content" name="content" class="form-control ckeditor"><?=($query ? $query->post_content : '');?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-tasks"></i> Waktu dan Tempat</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6" style="margin-bottom: 10px;">
									<label class="control-label" for="waktu">Pukul</label>
									<input id="waktu" name="waktu" value="<?=($query ? $query->waktu : '');?>" autofocus required="true" type="text" class="form-control input-sm" placeholder="07:00 - 16:00 WIB" style="font-size: 12px">
								</div>
								<div class="col-lg-6" style="margin-bottom: 10px;">
									<label class="control-label" for="tanggal">Tanggal</label>
									<?=form_dropdown('tanggal', ['01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'], ($query ? $query->tanggal : ''), 'class="form-control input-sm" id="tanggal"');?>
								</div>
								<div class="col-lg-6" style="margin-bottom: 10px;">
									<label class="control-label" for="bulan">Bulan</label>
									<?=form_dropdown('bulan', ['JAN' => 'Januari', 'FEB' => 'Februari', 'MAR' => 'Maret', 'APR' => 'April', 'MEI' => 'Mei', 'JUN' => 'Juni', 'JUL' => 'Juli', 'AGT' => 'Agustus', 'SEP' => 'September', 'OKT' => 'Oktober', 'NOV' => 'November', 'DES' => 'Desember'], ($query ? $query->bulan : ''), 'class="form-control input-sm" id="bulan"');?>
								</div>
								<div class="col-lg-6" style="margin-bottom: 10px;">
									<label class="control-label" for="tahun">Tahun</label>
									<?=form_dropdown('tahun', ['2018' => '2018', '2019' => '2019', '2020' => '2020', '2021' => '2021'], ($query ? $query->tahun : ''), 'class="form-control input-sm" id="tahun"');?>
								</div>
								<div class="col-lg-12" style="margin-bottom: 10px;">
									<label class="control-label" for="tahun">Tempat</label>
									<input id="tempat" name="tempat" value="<?=($query ? $query->tempat : '');?>" autofocus required="true" type="text" class="form-control input-sm" placeholder="Nama Tempat" style="font-size: 12px">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-send-o"></i> Publikasi</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label class="control-label" for="post_status">Status</label>
									<?=form_dropdown('post_status', ['publish' => 'Diterbitkan', 'draft' => 'Konsep'], ($query ? $query->post_status : ''), 'class="form-control input-sm" id="post_status"');?>
								</div>
								<div class="col-lg-6">
									<label class="control-label" for="post_visibility">Akses</label>
									<?=form_dropdown('post_visibility', ['public' => 'Publik', 'private' => 'Private'], ($query ? $query->post_visibility : ''), 'class="form-control input-sm" id="post_visibility"');?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="post_comment_status">Komentar</label>
							<?=form_dropdown('post_comment_status', ['close' => 'Tidak Diizinkan', 'open' => 'Diizinkan'], ($query ? $query->post_comment_status : ''), 'class="form-control input-sm" id="post_comment_status"');?>
						</div>
						<div class="form-group">
							<label class="control-label">Gambar</label>
							<div class="input-group">
								<input type="file" name="post_image" class="form-control" id="post_image">
								<img <?=(isset($post_image) && $post_image) ? ('src="'.$post_image.'"') : '' ?> id="preview" width="293px" style="margin-top: 50px; <?=(isset($post_image) && $post_image) ? '': 'display:none;'?>" class="img-responsive" title="Double klik untuk menghapus gambar">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="btn-group pull-right">
							<button type="reset" class="btn btn-info btn-sm"><i class="fa fa-retweet"></i> ATUR ULANG</button>
							<button type="submit" id="submit" class="btn btn-primary btn-sm"><i class="fa fa-send-o"></i> SIMPAN</button>
						</div>
					</div>
				</div>
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-tags"></i> Tags</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<input type="text" name="post_tags" id="post_tags" placeholder="Tags" class="form-control tm-input-info tm-tag-mini" value="<?=$query ? $query->post_tags : ''?>"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>