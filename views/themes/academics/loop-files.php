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

	function load_more_files() {
		page++;
		var data = {
			page_number: page,
			slug: '<?= $this->uri->segment(2) ?>'
		};
		if (page <= parseInt(total_pages)) {
			$.post(_BASE_URL + 'public/download/more_files', data, function(response) {
				var res = H.StrToObject(response);
				var rows = res.rows;
				var html = '';
				var no = parseInt($('.number:last').text()) + 1;
				for (var z in rows) {
					var result = rows[z];
					html += '<tr>';
					html += '<td class="text-center number">' + no + '</td>';
					html += '<td>' + result.file_title + '</td>';
					html += '<td>' + (H.FormatBytes(result.file_size * 1024)) + '</td>';
					html += '<td>' + result.file_ext + '</td>';
					html += '<td>' + result.file_counter + '</td>';
					html += '<td class="text-center">';
					html += '<a href="' + _BASE_URL + 'public/download/force_download/' + result.id + '"><i class="fa fa-download"></i></a>';
					html += '</td>';
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
<div class="col-xs-12 col-md-9">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-download"></i> <?= strtoupper($page_title) ?></div>
		<table class="table table-hover table-striped table-condensed">
			<thead>
				<tr>
					<th width="20px">NO</th>
					<th>NAMA FILE</th>
					<th>UKURAN</th>
					<th>TIPE</th>
					<th>DIUNDUH</th>
					<th width="40px" class="text-center"><i class="fa fa-download"></i></th>
				</tr>
			</thead>
			<tbody>
				<?php $no = 1;
				foreach ($query->result() as $row) { ?>
					<tr>
						<td class="text-center number"><?= $no ?></td>
						<td><?= $row->file_title ?></td>
						<td><?= filesize_formatted($row->file_size * 1024) ?></td>
						<td><?= $row->file_ext ?></td>
						<td><?= $row->file_counter ?> Kali</td>
						<td class="text-center">
							<a href="<?= site_url('public/download/force_download/' . $row->id) ?>"><i class="fa fa-download"></i></a>
						</td>
					</tr>
				<?php $no++;
				} ?>
			</tbody>
		</table>
		<div class="panel-footer">
			<button class="btn btn-block btn-sm btn-inverse load-more" onclick="load_more_files()">FILE LAINNYA</button>
		</div>
	</div>
</div>
<?php $this->load->view('themes/academics/sidebar') ?>