<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<?php $this->load->view('themes/academics/menu') ?>
<!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_3_image'); ?>');">
    <div class="container">
        <div class="pagination-area">
            <h1>GALLERY VIDEO</h1>
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
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $row->post_content ?>" allowfullscreen></iframe>
                </div>
                <?= (($idx % 3 == 2) || ($rows + 2 == $idx)) ? '</div>' : '' ?>
            <?php $idx++;
            } ?>
        </div>
    </div>
</div>
<!-- Gallery Area 2 End Here -->