<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
    function remove_tags(input) {
        return input.replace(/(<([^>]+)>)/ig, "");
    }
    var page = 1;
    var total_page = "<?= $total_page; ?>";
    $(document).ready(function() {
        if (parseInt(total_page) == page || parseInt(total_page) == 0) {
            $('button.load-more').remove();
        }
    });

    function load_more() {
        page++;
        var segment_1 = '<?= $this->uri->segment(1) ?>';
        var segment_2 = '<?= $this->uri->segment(2) ?>';
        var segment_3 = '<?= $this->uri->segment(3) ?>';
        var url = '';
        var data = {
            'page_number': page
        };
        if (segment_1 == 'category') {
            data['category_slug'] = segment_2;
            url = _BASE_URL + 'public/post_categories/more_posts';
        } else if (segment_1 == 'tag') {
            data['tag'] = segment_2;
            url = _BASE_URL + 'public/post_tags/more_posts';
        } else if (segment_1 == 'archives') {
            data['year'] = segment_2;
            data['month'] = segment_3;
            url = _BASE_URL + 'public/archives/more_posts';
        }
        if (page <= parseInt(total_page)) {
            $.post(url, data, function(response) {
                var res = H.StrToObject(response);
                var rows = res.rows;
                var html = '';
                var idx = 3;
                for (var z in rows) {
                    var result = rows[z];
                    if (idx % 3 == 0) {
                        html += '<div class="row loop-posts">';
                    }
                    html += '<div class="col-md-4">';
                    html += '<div class="thumbnail no-border">';
                    html += '<img src="' + _BASE_URL + 'media_library/posts/medium/' + result.post_image + '" style="width: 100%; display: block;">';
                    html += '<div class="caption">';
                    html += '<h4><a href="' + _BASE_URL + 'read/' + result.id + '/' + result.post_slug + '">' + result.post_title + '</a></h4>';
                    html += '<p class="by-author">' + result.posted_date + '</p>';
                    html += '<p>' + remove_tags(result.post_content, '').substr(0, 150) + '</p>';
                    html += '<p>';
                    html += '<a href="' + _BASE_URL + 'read/' + result.id + '/' + result.post_slug + '" class="btn btn-primary btn-sm" role="button">Selengkapnya <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>';
                    html += '</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    if (idx % 3 == 2 || (res.result_rows + 2) == idx) {
                        html += '</div>';
                    }
                    idx++;
                }
                var el = $(".loop-posts:last");
                $(html).insertAfter(el);
                if (page == parseInt(total_page)) {
                    $('button.load-more').remove();
                }
            });
        }
    }
</script>
<?php $this->load->view('themes/academics/menu') ?>
<!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_3_image'); ?>');">
    <div class="container">
        <div class="pagination-area">
            <h1><?= strtoupper($title) ?></h1>
            <ul>
                <li><a href="#">Home</a> -</li>
                <li><?= strtoupper($title) ?></li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<!-- News Page Area Start Here -->
<div class="news-page-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="row">
                    <?php $idx = 3;
                    $rows = $query->num_rows();
                    foreach ($query->result() as $row) { ?>
                        <?= ($idx % 3 == 0) ? '' : '' ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="news-box">
                                <div class="news-img-holder">
                                    <img src="<?= base_url('media_library/posts/medium/' . $row->post_image) ?>" class="img-responsive" alt="research">
                                    <ul class="news-date2">
                                        <li><?= substr(strip_tags(indo_date($row->created_at)), 0, 6) ?></li>
                                        <li><?= substr(strip_tags($row->created_at), 0, 4) ?></li>
                                    </ul>
                                </div>
                                <h3 class="title-news-left-bold"><a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                <ul class="title-bar-high news-comments">
                                    <li><a href="#"><i class="fa fa-user" aria-hidden="true"></i><span>By</span> Admin</a></li>
                                    <li><a href="#"><i class="fa fa-tags" aria-hidden="true"></i>Business</a></li>
                                    <li><a href="#"><i class="fa fa-comments-o" aria-hidden="true"></i><span>(03)</span> Comments</a></li>
                                </ul>
                                <p><?= substr(strip_tags($row->post_content), 0, 150) ?></p>
                                <p>
                                    <a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>" class="btn btn-primary btn-sm" role="button">Selengkapnya <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                </p>
                            </div>
                        </div>
                        <?= (($idx % 3 == 2) || ($rows + 2 == $idx)) ? '' : '' ?>
                    <?php $idx++;
                    } ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="pagination-center"><button class="btn btn-success btn-sm btn-block load-more" onclick="load_more()">TULISAN LAINNYA</button>
                        </ul>
                    </div>
                </div>
            </div>
            <?php $this->load->view('themes/academics/sidebar') ?>
        </div>
    </div>
</div>
<!-- News Page Area End Here -->