<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<?php $this->load->view('themes/academics/menu') ?>
<!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_3_image'); ?>');">
    <div class="container">
        <div class="pagination-area">
            <h1><?= $title ?></h1>
            <ul>
                <li><a href="#">Home</a> -</li>
                <li>Details</li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<!-- Event Details Page Area Start Here -->
<div class="event-details-page-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="event-details-inner">
                    <?php if ($query && $query->num_rows() > 0) { ?>
                        <?php foreach ($query->result() as $row) { ?>
                            <h2 class="title-default-left-bold-lowhight"><a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h2>
                            <p style="text-align: justify;"><?= word_limiter(strip_tags($row->post_content), 30) ?></p>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($pages && $pages->num_rows() > 0) { ?>
                        <?php foreach ($pages->result() as $row) { ?>
                            <h2 class="title-default-left-bold-lowhight"><a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h2>
                            <p style="text-align: justify;"><?= word_limiter(strip_tags($row->post_content), 30) ?></p>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php $this->load->view('themes/academics/sidebar') ?>
        </div>
    </div>
</div>
<!-- Event Details Page Area End Here -->