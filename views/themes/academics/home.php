<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<!-- Header Area Start Here -->
<header>
    <div id="header1" class="header1-area">
        <div class="main-menu-area bg-primary" id="sticker" style="background-color:#212529;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <div class="logo-area">
                            <a href="<?= base_url() ?>"><img class="img-responsive" src="<?= base_url('media_library/images/') . $this->session->userdata('logo_textprimary'); ?>" alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-9">
                        <nav id="desktop-nav" >
                            <ul>
                                <li><a href="<?= base_url() ?>"><i class="fa fa-home"></i></a></li>
                                <?php
                                foreach ($menus as $menu) {
                                    echo '<li>';
                                    $sub_nav = cosmo_recursive_list($menu['child']);
                                    $url = base_url() . $menu['menu_url'];
                                    if ($menu['menu_type'] == 'link') {
                                        $url = $menu['menu_url'];
                                    }
                                    echo anchor($url, strtoupper($menu['menu_title']) . ($sub_nav ? '' : ''), 'target="' . $menu['menu_target'] . '"');
                                    if ($sub_nav) {
                                        echo '<ul>';
                                        echo cosmo_recursive_list($menu['child']);
                                        echo '</ul>';
                                    }
                                    echo '</li>';
                                } ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-1 col-md-1 hidden-sm">
                        <div class="header-search">
                            <form action="<?= site_url('hasil-pencarian') ?>" method="POST">
                                <input type="text" class="search-form" placeholder="Pencarian" name="keyword" required="" style="background-color:white;">
                                <a href="#" class="search-button" id="search-button"><i class="fa fa-search" aria-hidden="true" style="color:white;"></i></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area Start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mobile-menu">
                        <nav id="dropdown"><a href='<?= base_url() ?>' class='logo-mobile-menu'><img src="<?= base_url('media_library/images/') . $this->session->userdata('logo_primary'); ?>" /></a>
                            <ul>
                                <li><a href="<?= base_url() ?>">Home</a></li>
                                <?php
                                foreach ($menus as $menu) {
                                    echo '<li>';
                                    $sub_nav = cosmo_recursive_list($menu['child']);
                                    $url = base_url() . $menu['menu_url'];
                                    if ($menu['menu_type'] == 'link') {
                                        $url = $menu['menu_url'];
                                    }
                                    echo anchor($url, strtoupper($menu['menu_title']) . ($sub_nav ? '' : ''), 'target="' . $menu['menu_target'] . '"');
                                    if ($sub_nav) {
                                        echo '<ul>';
                                        echo cosmo_recursive_list($menu['child']);
                                        echo '</ul>';
                                    }
                                    echo '</li>';
                                } ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End -->
</header>
<!-- Header Area End Here -->
<!-- Slider 1 Area Start Here -->
<div class="slider1-area overlay-default index1">
    <div class="bend niceties preview-1">
        <div id="ensign-nivoslider-3" class="slides">

            <?php $query = get_image_sliders();
            if ($query->num_rows() > 0) { ?>
                <?php $idx = 0;
                foreach ($query->result() as $row) { ?>
                    <img src="<?= base_url('media_library/image_sliders/' . $row->image); ?>" alt="slider" title="#slider-direction-<?= $row->id; ?>" />

                <?php $idx++;
                } ?>
            <?php } ?>
        </div>
        <?php $query = get_image_sliders();
        if ($query->num_rows() > 0) { ?>
            <?php $idx = 0;
            foreach ($query->result() as $row) { ?>
                <div id="slider-direction-<?= $row->id; ?>" class="t-cn slider-direction">
                    <div class="slider-content s-tb slide-<?= $row->id; ?>">
                        <div class="title-container s-tb-c">
                            <div class="title1"><?= $row->title; ?></div>
                            <p><?= $row->caption; ?></p>
                            <div class="slider-btn-area"> 
                                <a href="<?= $row->url; ?>" target="blank" class="default-big-btn" style="background-color:#7D2AE8;"><?= $row->bottom; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $idx++;
            } ?>
        <?php } ?>
    </div>
</div>
<!-- Slider 1 Area End Here -->
<!-- Service 1 Area Start Here -->
<?php $query = get_service_area();
if ($query->num_rows() > 0) { ?>
    <div class="service1-area" >
        <div class="service1-inner-area">
            <div class="container">
                <div class="row service1-wrapper">
                    <?php foreach ($query->result() as $row) { ?>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1" style="background-color: navy blue;">
                            <div class="service-box-content">
                                <h3><a href="<?= $row->url ?>"><?= $row->title ?></a></h3>
                                <p><?= $row->description ?></p>
                            </div>
                            <div class="service-box-icon">
                                <i class="fa fa-<?= $row->logo ?>" aria-hidden="true"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Service 1 Area End Here -->
<!-- About 1 Area Start Here -->
<div class="about1-area">
    <div class="container">
        <h1 class="about-title wow fadeIn" data-wow-duration="1s" data-wow-delay=".2s"><?= $this->session->userdata('about_title') ?></h1>
        <p class="about-sub-title wow fadeIn" data-wow-duration="1s" data-wow-delay=".2s"><?= $this->session->userdata('about_description') ?></p>
        <div class="about-img-holder wow fadeIn" data-wow-duration="2s" data-wow-delay=".2s">
            <img src="<?= base_url('media_library/images/') . $this->session->userdata('about_image'); ?>" alt="about" class="img-responsive" />
        </div>
    </div>
</div>
<!-- About 1 Area End Here -->
<!-- Courses 1 Area Start Here -->
<?php
$query = get_recent_announcement(5);
if ($query->num_rows() > 0) {
    $posts = [];
    foreach ($query->result() as $post) {
        array_push($posts, $post);
    }
?>
    <div class="courses1-area">
        <div class="container">
            <h2 class="title-default-left">Program Keahlian</h2>
        </div>
        <div id="shadow-carousel" class="container">
            <div class="rc-carousel" data-loop="true" data-items="4" data-margin="20" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="2" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
                
                <?php if (count(array_slice($posts, 0, 1)) > 0) { ?>
                    <?php foreach (array_slice($posts, 0, 1) as $row) { ?>

                        <div class="courses-box1">
                            <div class="single-item-wrapper">
                                <div class="courses-img-wrapper hvr-bounce-to-bottom">
                                    <img class="img-responsive" src="<?= base_url('media_library/announcement/medium/' . $row->post_image) ?>" alt="courses">
                                    <a href="<?= site_url('announcement/' . $row->id . '/' . $row->post_slug) ?>"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                                <div class="courses-content-wrapper">
                                    <h3 class="item-title"><a href="<?= site_url('announcement/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                    <p class="item-content"><?= substr(strip_tags($row->post_content), 0, 165) ?></p>
                                    <ul class="courses-info">
                                        <li>6
                                            <br><span> majors</span></li>
                                        <li>5
                                            <br><span> laboratory</span></li>
                                        <li>07.40 - 12.40
                                            <br><span> Classes</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (count(array_slice($posts, 1)) > 0) { ?>
                    <?php foreach (array_slice($posts, 1) as $row) { ?>
                        <div class="courses-box1">
                            <div class="single-item-wrapper">
                                <div class="courses-img-wrapper hvr-bounce-to-bottom">
                                    <img class="img-responsive" src="<?= base_url('media_library/announcement/medium/' . $row->post_image) ?>" alt="courses">
                                    <a href="<?= site_url('announcement/' . $row->id . '/' . $row->post_slug) ?>"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                                <div class="courses-content-wrapper">
                                    <h3 class="item-title"><a href="<?= site_url('announcement/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                    <p class="item-content"><?= substr(strip_tags($row->post_content), 0, 165) ?></p>
                                    <ul class="courses-info">
                                        <li>6
                                            <br><span> majors</span></li>
                                        <li>5
                                            <br><span> laboratory</span></li>
                                        <li>07.40 - 12.40
                                            <br><span> Classes</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            
            </div>
        </div>
    </div>
<?php } ?>
<!-- Courses 1 Area End Here -->
<!-- Video Area Start Here -->
<div class="video-area overlay-video bg-common-style" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_1_image'); ?>');?>');">
    <div class="container">
        <div class="video-content">
            <h2 class="video-title"><?= $this->session->userdata('video_tour_title') ?></h2>
            <p class="video-sub-title"><?= $this->session->userdata('video_tour_description') ?></p>
            <a class="play-btn popup-youtube wow bounceInUp" data-wow-duration="2s" data-wow-delay=".1s" href="<?= $this->session->userdata('video_tour_url') ?>"><i class="fa fa-play" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
<!-- Video Area End Here -->
<!-- Lecturers Area Start Here -->
<div class="lecturers-area">
    <div class="container">
        <h2 class="title-default-left">Direktori Guru dan Staf</h2>
    </div>
    <div class="container">
        <div class="rc-carousel" data-loop="true" data-items="4" data-margin="30" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
            <?php $query = get_lecturers_area();
            if ($query->num_rows() > 0) { ?>
                <?php $idx = 0;
                foreach ($query->result() as $row) { ?>
                    <div class="single-item">
                        <div class="lecturers1-item-wrapper">
                            <div class="lecturers-img-wrapper">
                                <a href="javascript:(0)"><img class="img-responsive" src="<?= base_url('media_library/lecturers_area/' . $row->image); ?>" alt="team"></a>
                            </div>
                            <div class="lecturers-content-wrapper">
                                <h3 class="item-title"><a href="<?= $row->url; ?>"><?= $row->title; ?></a></h3>
                                <span class="item-designation"><?= $row->caption; ?></span>
                                <ul class="lecturers-social">
                                    <li><a href="mailto:<?= $row->email; ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                                    <li><a href="https://instagram.com/<?= $row->instagram; ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                    <li><a href="https://twitter.com/<?= $row->twitter; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                    <li><a href="https://facebook.com/<?= $row->facebook; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php $idx++;
                } ?>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Lecturers Area End Here -->
<!-- News and Event Area Start Here -->
<div class="news-event-area">
    <div class="container">
        <div class="row">
            <?php
            $query = get_recent_posts(3);
            if ($query->num_rows() > 0) {
                $posts = [];
                foreach ($query->result() as $post) {
                    array_push($posts, $post);
                }
            ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 news-inner-area">
                    <h2 class="title-default-left">Post & Berita Terbaru</h2>
                    <ul class="news-wrapper">
                        <?php if (count(array_slice($posts, 0, 1)) > 0) { ?>
                            <?php foreach (array_slice($posts, 0, 1) as $row) { ?>
                                <li>
                                    <div class="news-img-holder">
                                        <a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><img src="<?= base_url('media_library/posts/thumbnail/' . $row->post_image) ?>" class="img-responsive" alt="news"></a>
                                    </div>
                                    <div class="news-content-holder">
                                        <h3><a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                        <div class="post-date"><?= day_name(date('N', strtotime($row->created_at))) ?>, <?= indo_date($row->created_at) ?></div>
                                        <p align="justify"><?= substr(strip_tags($row->post_content), 0, 90) ?></p>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <?php if (count(array_slice($posts, 1)) > 0) { ?>
                            <?php foreach (array_slice($posts, 1) as $row) { ?>
                                <li>
                                    <div class="news-img-holder">
                                        <a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><img src="<?= base_url('media_library/posts/thumbnail/' . $row->post_image) ?>" class="img-responsive" alt="news"></a>
                                    </div>
                                    <div class="news-content-holder">
                                        <h3><a href="<?= site_url('read/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                        <div class="post-date"><?= day_name(date('N', strtotime($row->created_at))) ?>, <?= indo_date($row->created_at) ?></div>
                                        <p align="justify"><?= substr(strip_tags($row->post_content), 0, 90) ?></p>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <div class="news-btn-holder">
                        <a href="category/post" class="view-all-accent-btn">View All</a>
                    </div>
                </div>
            <?php } ?>
            <?php
            $query = get_recent_event(2);
            if ($query->num_rows() > 0) {
                $event = [];
                foreach ($query->result() as $post) {
                    array_push($event, $post);
                }
            ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 event-inner-area">
                    <h2 class="title-default-left">Acara akan datang</h2>
                    <ul class="event-wrapper">
                        <?php if (count(array_slice($event, 0, 1)) > 0) { ?>
                            <?php foreach (array_slice($event, 0, 1) as $row) { ?>
                                <li class="wow bounceInUp" data-wow-duration="2s" data-wow-delay=".1s">
                                    <div class="event-calender-wrapper">
                                        <div class="event-calender-holder">
                                            <h3><?= substr(strip_tags($row->tanggal), 0, 2) ?></h3>
                                            <p><?= $row->bulan ?></p>
                                            <span><?= $row->tahun ?></span>
                                        </div>
                                    </div>
                                    <div class="event-content-holder">
                                        <h3><a href="<?= site_url('event/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                        <p align="justify"><?= substr(strip_tags($row->post_content), 0, 205) ?></p>
                                        <ul>
                                            <li><?= $row->waktu ?></li>
                                            <li><?= $row->tempat ?></li>
                                        </ul>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <?php if (count(array_slice($event, 1)) > 0) { ?>
                            <?php foreach (array_slice($event, 1) as $row) { ?>
                                <li class="wow bounceInUp" data-wow-duration="2s" data-wow-delay=".3s">
                                    <div class="event-calender-wrapper">
                                        <div class="event-calender-holder">
                                            <h3><?= $row->tanggal ?></h3>
                                            <p><?= $row->bulan ?></p>
                                            <span><?= $row->tahun ?></span>
                                        </div>
                                    </div>
                                    <div class="event-content-holder">
                                        <h3><a href="<?= site_url('event/' . $row->id . '/' . $row->post_slug) ?>"><?= $row->post_title ?></a></h3>
                                        <p align="justify"><?= substr(strip_tags($row->post_content), 0, 205) ?></p>
                                        <ul>
                                            <li><?= $row->waktu ?></li>
                                            <li><?= $row->tempat ?></li>
                                        </ul>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <div class="event-btn-holder">
                        <a href="#" class="view-all-primary-btn">View All</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- News and Event Area End Here -->
<!-- Counter Area Start Here -->
<?php $query = get_counter_area();
if ($query->num_rows() > 0) { ?>
    <div class="counter-area bg-primary-deep" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_2_image'); ?>');?>');">
        <div class="container">
            <div class="row">
                <?php foreach ($query->result() as $row) { ?>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 counter1-box wow fadeInUp" data-wow-duration=".5s" data-wow-delay="<?= $row->delay ?>">
                        <h2 class="about-counter title-bar-counter" data-num="<?= $row->counter ?>"><?= $row->counter ?></h2>
                        <p><?= $row->title ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Counter Area End Here -->
<!-- Courses 1 Area Start Here -->
<div class="courses1-area">
    <div class="container">
        <h2 class="title-default-left"><a href="<?= site_url('gallery-photo') ?>">Photos</a> dan <a href="<?= site_url('gallery-video') ?>">Video</a> Terbaru</h2>
    </div>
    <?php $query = get_albums(5);
    if ($query->num_rows() > 0) { ?>
        <div id="shadow-carousel" class="container">
            <div class="rc-carousel" data-loop="true" data-items="4" data-margin="20" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="2" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
                <?php foreach ($query->result() as $row) { ?>
                    <div class="courses-box1">
                        <div class="single-item-wrapper">
                            <div class="courses-img-wrapper hvr-bounce-to-bottom">
                                <img class="img-responsive" onclick="photo_preview(<?= $row->id ?>)" src="<?= base_url('media_library/albums/' . $row->album_cover) ?>">
                                <a href="javascript:(0)" onclick="photo_preview(<?= $row->id ?>)"><i class="fa fa-search" aria-hidden="true"></i></a>
                            </div>
                            <div class="courses-content-wrapper">
                                <h3 class="item-title"><a href="javascript:(0)" onclick="photo_preview(<?= $row->id ?>)"><?= $row->album_title ?></a></h3>
                                <p class="item-content" onclick="photo_preview(<?= $row->id ?>)"><?= substr(strip_tags($row->album_description), 0, 67) ?>...</p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <?php $query = get_videos(2);
    if ($query->num_rows() > 0) { ?>
        <div id="shadow-carousel" class="container">
            <div class="rc-carousel" data-loop="true" data-items="4" data-margin="20" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="2" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
                <?php foreach ($query->result() as $row) { ?>
                    <div class="courses-box1">
                        <div class="single-item-wrapper">
                            <div class="courses-img-wrapper">
                                <iframe frameborder="0" allowfullscreen class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $row->post_content ?>"></iframe>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<!-- Courses 1 Area End Here -->
<!-- Students Say Area Start Here -->
<?php $query = get_student_say();
if ($query->num_rows() > 0) { ?>
    <div class="students-say-area">
        <h2 class="title-default-center">Apa Kata Siswa Kami?</h2>
        <div class="container">
            <div class="rc-carousel" data-loop="true" data-items="2" data-margin="30" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="true" data-nav="false" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="false" data-r-x-small-dots="true" data-r-x-medium="2" data-r-x-medium-nav="false" data-r-x-medium-dots="true" data-r-small="2" data-r-small-nav="false" data-r-small-dots="true" data-r-medium="2" data-r-medium-nav="false" data-r-medium-dots="true" data-r-large="2" data-r-large-nav="false" data-r-large-dots="true">
                <?php $idx = 0;
                foreach ($query->result() as $row) { ?>
                    <div class="single-item">
                        <div class="single-item-wrapper">
                            <div class="profile-img-wrapper">
                                <a href="#" class="profile-img"><img class="profile-img-responsive img-circle" src="<?= base_url('media_library/student_say/' . $row->image); ?>" alt="Testimonial"></a>
                            </div>
                            <div class="tlp-tm-content-wrapper">
                                <h3 class="item-title"><a href="<?= $row->url; ?>" target="blank"><?= $row->title; ?></a></h3>
                                <span class="item-designation"><?= $row->bottom; ?></span>
                                <ul class="rating-wrapper">
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                </ul>
                                <div class="item-content"><?= $row->caption; ?></div>
                            </div>
                        </div>
                    </div>
                <?php $idx++;
                } ?>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Students Say Area End Here -->
<!-- Students Join 1 Area Start Here -->
<div class="students-join1-area">
    <div class="container">
        <div class="students-join1-wrapper">
            <div class="students-join1-left">
                <div id="ri-grid" class="author-banner-bg ri-grid header text-center">
                    <ul class="ri-grid-list">
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_1'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_2'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_3'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_4'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_5'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_6'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_7'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_8'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_9'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_10'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_11'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_12'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_13'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_14'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_15'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_16'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_17'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_18'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_19'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_20'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_21'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_22'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_23'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_24'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_25'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_26'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_27'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_28'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_29'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_30'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_31'); ?>" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="<?= base_url('media_library/student_join/') . $this->session->userdata('student_join_32'); ?>" alt=""></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="students-join1-right">
                <div>
                    <h2>PPDB<span> <?= $this->session->userdata('admission_year') ?></span><br><?= $this->session->userdata('admission_status') == 'open' ? 'Telah Dibuka' : 'Telah Ditutup' ?></h2>
                    <a href="<?= $this->session->userdata('admission_status') == 'open' ? 'formulir-penerimaan-peserta-didik-baru' : 'javascript:(0)' ?>" class="join-now-btn"><?= $this->session->userdata('admission_status') == 'open' ? 'Daftar Sekarang' : 'Closed' ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Students Join 1 Area End Here -->
<!-- Brand Area Start Here -->
<div class="brand-area">
    <div class="container">
        <div class="rc-carousel" data-loop="true" data-items="4" data-margin="30" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="2000" data-dots="false" data-nav="false" data-nav-speed="false" data-r-x-small="2" data-r-x-small-nav="false" data-r-x-small-dots="false" data-r-x-medium="3" data-r-x-medium-nav="false" data-r-x-medium-dots="false" data-r-small="4" data-r-small-nav="false" data-r-small-dots="false" data-r-medium="4" data-r-medium-nav="false" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="false" data-r-large-dots="false">
            <?php $query = get_banners();
            if ($query->num_rows() > 0) { ?>
                <?php foreach ($query->result() as $row) { ?>
                    <div class="brand-area-box">
                        <a href="<?= $row->link_url ?>" title="<?= $row->link_title ?>"><img src="<?= base_url('media_library/banners/' . $row->link_image) ?>" alt="brand"></a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Brand Area End Here -->