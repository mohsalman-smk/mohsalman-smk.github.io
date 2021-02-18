<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
    var page = 1;
    var total_pages = "<?= $total_pages; ?>";
    $(document).ready(function() {
        if (parseInt(total_pages) == page || parseInt(total_pages) == 0) {
            $('button.load-more').remove();
        }
    });

    function load_more() {
        page++;
        var data = {
            page_number: page
        };

        if (page <= parseInt(total_pages)) {
            $.post(_BASE_URL + 'public/gallery_photos/more_photos', data, function(response) {
                var res = H.StrToObject(response);
                var rows = res.rows;
                var total_rows = res.total_rows;
                var idx = 3,
                    html = '';
                for (var z in rows) {
                    var result = rows[z];
                    html += (idx % 3 == 0) ? '<div class="row loop-album">' : '';
                    html += '<div class="col-md-4 col-xs-12">';
                    html += '<div class="thumbnail">';
                    html += '<img style="cursor: pointer; width: 100%; height: 250px;" onclick="photo_preview(' + result.id + ')" src="' + _BASE_URL + 'media_library/albums/' + result.album_cover + '">';
                    html += '<div class="caption">';
                    html += '<h4>' + result.album_title + '</h4>';
                    html += '<p>' + result.album_description + '</p>';
                    html += '<button onclick="photo_preview(' + result.id + ')" class="btn btn-success btn-sm"><i class="fa fa-search"></i></button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += ((idx % 3 == 2) || total_rows + 2 == idx) ? '</div>' : '';
                    idx++;
                }
                var el = $("div.loop-album:last");
                $(html).insertAfter(el);
                if (page == parseInt(total_pages)) {
                    $('button.load-more').remove();
                }
            });
        }
    }
</script>
<?php $this->load->view('themes/academics/menu') ?>
<!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_3_image'); ?>');?>');">
    <div class="container">
        <div class="pagination-area">
            <h1>GALLERY PHOTO</h1>
            <ul>
                <li><a href="#">Home</a> -</li>
                <li>Gallery</li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<!-- Gallery Area 2 Start Here -->
<div class="gallery-area2">
    <div class="container" id="inner-isotope">
        <div class="row featuredContainer gallery-wrapper">
            <?php $idx = 3;
            $rows = $query->num_rows();
            foreach ($query->result() as $row) { ?>
                <?= ($idx % 3 == 0) ? '<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 library auditoriam">' : '' ?>

                <div class="gallery-box">
                    <img onclick="photo_preview(<?= $row->id ?>)" src="<?= base_url('media_library/albums/' . $row->album_cover) ?>" class="img-responsive" alt="gallery">
                    <div class="gallery-content">
                        <a href="javascript:(0)" onclick="photo_preview(<?= $row->id ?>)" class="zoom"><i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3><a href="#"><?= $row->album_title ?></a></h3>
                        <p><?= $row->album_description ?></p>
                    </div>
                </div>
                <?= (($idx % 3 == 2) || ($rows + 2 == $idx)) ? '</div>' : '' ?>
            <?php $idx++;
            } ?>

            <button class="btn btn-success btn-sm btn-block load-more" onclick="load_more()">ALBUM LAINNYA</button>
        </div>
    </div>
</div>

<!-- Gallery Area 2 End Here -->