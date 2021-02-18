<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=602e1249289b2f0011212b9c&product=inline-share-buttons' async='async'>
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
            <h1>News Details</h1>
            <ul>
                <li><a href="#">Home</a> -</li>
                <li>Details</li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<!-- News Details Page Area Start Here -->
<div class="news-details-page-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="row news-details-page-inner">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if ($post_type == 'post' && file_exists('./media_library/posts/large/' . $query->post_image)) { ?>
                            <div class="news-img-holder">
                                <img src="<?= base_url('media_library/posts/large/' . $query->post_image) ?>" style="width: 100%; display: block;" class="img-responsive" alt="research">
                            </div>
                        <?php } ?>
                        <h2 class="title-default-left-bold-lowhight"><a href="javascript:(0)"><?= $query->post_title ?></a></h2>
                        <ul class="title-bar-high news-comments">
                            <li><a href="javascript:(0)"><i class="fa fa-user" aria-hidden="true"></i><span>By</span> <?= $post_author ?></a></li>
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
                        
                        <!-- ShareThis BEGIN -->
                        <div class="sharethis-inline-share-buttons"></div>
                        <!-- ShareThis END -->

                        <?php if ($post_comments->num_rows() > 0) { ?>
                            <div class="course-details-comments">
                                <h3 class="sidebar-title">Komentar</h3>
                                <?php foreach ($post_comments->result() as $row) { ?>
                                    <div class="media">
                                        <a href="javascript:(0)" class="pull-left">
                                            <img alt="Comments" src="<?= base_url('views/themes/academics/img/course/16.jpg'); ?>" class="media-object">
                                        </a>
                                        <div class="media-body">
                                            <h3><a href="javascript:(0)"><?= $row->comment_author ?></a></h3>
                                            <h4><?= day_name(date('N', strtotime($row->created_at))) ?>, <?= indo_date($row->created_at) ?></h4>
                                            <p align="justify"><?= strip_tags($row->comment_content) ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="panel-footer">
                                    <button class="btn btn-sm btn-block btn-inverse load-more" onclick="more_comments()">KOMENTAR LAINNYA</button>
                                </div>
                            </div>

                        <?php } ?>
                        <?php if (
                            ($query->post_comment_status == 'open' &&
                                $this->session->userdata('comment_registration') == 'true' &&
                                $this->auth->is_logged_in()) ||
                            ($query->post_comment_status == 'open' &&
                                $this->session->userdata('comment_registration') == 'false')
                        ) { ?>
                            <div class="leave-comments">
                                <h3 class="sidebar-title">Komentari tulisan ini?</h3>
                                <div class="row">
                                    <div class="contact-form">
                                        <form>
                                            <fieldset>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" id="comment_author" name="comment_author" placeholder="Nama Lengkap" class="form-control" required="true">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="email" id="comment_email" name="comment_email" placeholder="Email" required="true" class="form-control">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" id="comment_url" name="comment_url" placeholder="Website" required="true" class="form-control">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <textarea placeholder="Comment" class="textarea form-control" id="comment_content" name="comment_content" required="true" rows="8" cols="20"></textarea>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <?php if (NULL !== $this->session->userdata('recaptcha_status') && $this->session->userdata('recaptcha_status') == 'enable') { ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label"></label>
                                                        <div class="col-sm-9">
                                                            <div class="g-recaptcha" data-sitekey="<?= $recaptcha_site_key ?>"></div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-12">
                                                    <div class="form-group margin-bottom-none">
                                                        <input type="hidden" name="comment_post_id" id="comment_post_id" value="<?= $this->uri->segment(2) ?>">
                                                        <button type="button" onclick="post_comment(); return false;" class="view-all-accent-btn"><i class="fa fa-send"></i> Kirim Komentar</button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('themes/academics/sidebar') ?>
        </div>
    </div>
</div>
<!-- News Page Area End Here -->
