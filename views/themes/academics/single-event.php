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

    function more_comments() {
        page++;
        var data = {
            page_number: page,
            comment_post_id: '<?= $this->uri->segment(2) ?>'
        };
        if (page <= parseInt(total_pages)) {
            $.post(_BASE_URL + 'public/post_comments/more_comments', data, function(response) {
                var res = H.StrToObject(response);
                var comments = res.comments;
                var html = '';
                for (var z in comments) {
                    var comment = comments[z];
                    html += '<div class="panel panel-inverse" style="margin-bottom: 0px;">';
                    html += '<div class="panel-heading" style="padding-bottom: 0px">';
                    html += '<strong>' + comment.comment_author + '</strong> - <span class="text-muted">' + H.indo_date(comment.created_at) + '</span>';
                    html += '</div>';
                    html += '<div class="panel-body" style="padding-top: 0px">';
                    html += '<p align="justify">' + comment.comment_content + '</p>';
                    html += '</div>';
                    html += '</div>';
                }
                var el = $(".panel-inverse:last");
                $(html).insertAfter(el);
                if (page == parseInt(total_pages)) {
                    $('.panel-footer').remove();
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
            <h1>Event Details</h1>
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
                    <?php if ($post_type == 'post' && file_exists('./media_library/event/large/' . $query->post_image)) { ?>
                        <div class="event-details-img">
                            <div class="countdown-content">
                                <div>
                                    <div class="countdown-section">
                                        <h3><?= $query->tanggal ?></h3>
                                        <p>Date</p>
                                    </div>
                                    <div class="countdown-section">
                                        <h3><?= $query->bulan ?></h3>
                                        <p>Month</p>
                                    </div>
                                    <div class="countdown-section">
                                        <h3><?= $query->tahun ?></h3>
                                        <p>Year</p>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:(0)"><img src="<?= base_url('media_library/event/large/' . $query->post_image) ?>" style="width: 100%; display: block;" class="img-responsive" alt="event"></a>
                        </div>
                    <?php } ?>
                    <h2 class="title-default-left-bold-lowhight"><a href="javascript:(0)"><?= $query->post_title ?></a></h2>
                    <ul class="event-info-inline title-bar-sm-high">
                        <li><i class="fa fa-calendar" aria-hidden="true"></i><?= $query->tanggal ?> <?= $query->bulan ?> <?= $query->tahun ?></li>
                        <li><i class="fa fa-map-marker" aria-hidden="true"></i><?= $query->tempat ?></li>
                        <?php
                        if ($query->post_tags) {
                            $post_tags = explode(',', $query->post_tags);
                            foreach ($post_tags as $tag) {
                                echo '<li> <a href="' . site_url('tag/' . url_title(strtolower(trim($tag)))) . '">';
                                echo '<i class="fa fa-tags" aria-hidden="true"></i> ' . ucwords(strtolower(trim($tag)));
                                echo '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    <?= $query->post_content ?>
                    <div id="share1"></div>
                    <script>
                        $("#share1").jsSocials({
                            shares: ["email", "twitter", "facebook", "googleplus", "whatsapp"]
                        });
                    </script>
                </div>
            </div>
            <?php $this->load->view('themes/academics/sidebar') ?>
        </div>
    </div>
</div>
<!-- Event Details Page Area End Here -->