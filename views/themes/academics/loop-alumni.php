<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
	var page = 1;
	var total_pages = "<?= $total_pages; ?>";
	$(document).ready(function() {
		if (parseInt(total_pages) == page || parseInt(total_pages) == 0) {
			$('.panel-footer').remove();
		}
	});

	function load_more_students() {
		page++;
		var data = {
			page_number: page
		};
		if (page <= parseInt(total_pages)) {
			$('body').addClass('loading');
			$.post(_BASE_URL + 'public/alumni_directory/more_alumni', data, function(response) {
				$('body').removeClass('loading');
				var res = H.StrToObject(response);
				var rows = res.rows;
				var html = '';
				var no = parseInt($('.number:last').text()) + 1;
				for (var z in rows) {
					var result = rows[z];
					html += '<tr>';
					html += '<td class="text-center number">' + no + '</td>';
					html += '<td><img width="80px" src="' + result.photo + '" class="img-responsive img-thumbnail" alt="Responsive image"></td>';
					html += '<td>' + result.identity_number + '</td>';
					html += '<td>' + result.full_name + '</td>';
					html += '<td>' + result.gender + '</td>';
					html += '<td>' + result.birth_place + '</td>';
					html += '<td>' + result.birth_date + '</td>';
					html += '<td>' + result.start_date + '</td>';
					html += '<td>' + result.end_date + '</td>';
					html += '</tr>';
					no++;
				}
				var el = $("tbody > tr:last");
				$(html).insertAfter(el);
				if (page == parseInt(total_pages)) {
					$('.panel-footer').remove();
				}
			});
		}
	}
</script>
<div class="col-xs-12 col-sm-12 col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-sign-out"></i> <?= strtoupper($page_title) ?></div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-condensed">
				<thead>
					<tr>
						<th width="20px">NO</th>
						<th>PHOTO</th>
						<th><?= $this->session->userdata('_identity_number') ?></th>
						<th>NAMA LENGKAP</th>
						<th>L/P</th>
						<th>TEMPAT LAHIR</th>
						<th>TANGGAL LAHIR</th>
						<th>TAHUN MASUK</th>
						<th>TAHUN KELUAR</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 1;
					foreach ($query->result() as $row) { ?>
						<tr>
							<td class="text-center number"><?= $no ?>.</td>
							<td>
								<?php
								$photo = 'no-image.jpg';
								if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/media_library/students/' . $row->photo)) {
									$photo = $row->photo;
								}
								echo '<img width="80px" src="' . base_url('media_library/students/' . $photo) . '" class="img-responsive img-thumbnail" alt="Responsive image">';
								?>
							</td>
							<td><?= $row->identity_number ?></td>
							<td><?= $row->full_name ?></td>
							<td><?= $row->gender ?></td>
							<td><?= $row->birth_place ?></td>
							<td><?= indo_date($row->birth_date) ?></td>
							<td><?= $row->start_date ?></td>
							<td><?= $row->end_date ?></td>
						</tr>
					<?php $no++;
					} ?>
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
			<button class="btn btn-block btn-sm btn-inverse load-more" onclick="load_more_students()">TAMPILKAN LEBIH BANYAK</button>
		</div>
	</div>
</div>