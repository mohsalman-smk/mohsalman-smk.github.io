<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= isset($page_title) ? $page_title . ' | ' : '' ?><?= $this->session->userdata('school_name') ?></title>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="keywords" content="<?= $this->session->userdata('meta_keywords'); ?>" />
    <meta name="description" content="<?= $this->session->userdata('meta_description'); ?>" />
    <meta name="subject" content="Pendidikan Indonesia">
    <meta name="copyright" content="<?= $this->session->userdata('school_name') ?>">
    <meta name="language" content="Indonesia">
    <meta name="robots" content="index,follow" />
    <meta name="revised" content="Senin, 05 November 2018" />
    <meta name="Classification" content="Pendidikan, Kurikulum 2013">
    <meta name="identifier-URL" content="https://www.radiustheme.com/">
    <meta name="author" content="Radiustheme, support@radiustheme.com">
    <meta name="designer" content="Radiustheme, https://www.radiustheme.com/">
    <meta name="reply-to" content="support@radiustheme.com">
    <meta name="owner" content="Radiustheme">
    <meta name="url" content="https://www.radiustheme.com/">
    <meta name="category" content="PPDB, SIMAK">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    <meta name="revisit-after" content="7 days">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Copyright" content="<?= $this->session->userdata('school_name'); ?>" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta name="revisit-after" content="7" />
    <meta name="webcrawlers" content="all" />
    <meta name="rating" content="general" />
    <meta name="spiders" content="all" />
    <meta itemprop="name" content="<?= $this->session->userdata('school_name'); ?>" />
    <meta itemprop="description" content="<?= $this->session->userdata('meta_description'); ?>" />
    <meta itemprop="image" content="<?= base_url('media_library/images/' . $this->session->userdata('logo')); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?= $this->session->userdata('csrf_token') ?>">
    <link rel="icon" href="<?= base_url('media_library/images/' . $this->session->userdata('favicon')); ?>">
    <link rel="alternate" type="application/rss+xml" title="<?= $this->session->userdata('school_name'); ?> Feed" href="<?= base_url('feed') ?>" />
    <?= link_tag('views/themes/academics/css/normalize.css'); ?>
    <!-- Main CSS -->
    <?= link_tag('views/themes/academics/css/main.css'); ?>
    <!-- Bootstrap CSS -->
    <?= link_tag('views/themes/academics/css/bootstrap.min.css'); ?>
    <!-- Animate CSS -->
    <?= link_tag('views/themes/academics/css/animate.min.css'); ?>
    <!-- Font-awesome CSS-->
    <?= link_tag('views/themes/academics/css/font-awesome.min.css'); ?>
    <!-- Owl Caousel CSS -->
    <?= link_tag('views/themes/academics/vendor/OwlCarousel/owl.carousel.min.css'); ?>
    <?= link_tag('views/themes/academics/vendor/OwlCarousel/owl.theme.default.min.css'); ?>
    <!-- Main Menu CSS -->
    <?= link_tag('views/themes/academics/css/meanmenu.min.css'); ?>
    <!-- nivo slider CSS -->
    <?= link_tag('views/themes/academics/vendor/slider/css/nivo-slider.css'); ?>
    <?= link_tag('views/themes/academics/vendor/slider/css/preview.css'); ?>
    <!-- Datetime Picker Style CSS -->
    <?= link_tag('views/themes/academics/css/jquery.datetimepicker.css'); ?>
    <!-- Magic popup CSS -->
    <?= link_tag('views/themes/academics/css/magnific-popup.css'); ?>
    <!-- Switch Style CSS -->
    <?= link_tag('views/themes/academics/css/hover-min.css'); ?>
    <!-- ReImageGrid CSS -->
    <?= link_tag('views/themes/academics/css/reImageGrid.css'); ?>
    <!-- Custom CSS -->
    <?= link_tag('views/themes/academics/style.css'); ?>
    <!-- Modernizr Js -->
    <?= link_tag('assets/css/font-awesome.css'); ?>
    <?= link_tag('assets/css/magnific-popup.css'); ?>
    <?= link_tag('assets/css/toastr.css'); ?>
    <?= link_tag('assets/css/jssocials.css'); ?>
    <?= link_tag('assets/css/jssocials-theme-flat.css'); ?>
    <?= link_tag('assets/css/bootstrap-datepicker.css'); ?>
    <?= link_tag('assets/css/loading.css'); ?>
    <script type="text/javascript">
        const _BASE_URL = '<?= base_url(); ?>';
        const _CURRENT_URL = '<?= current_url(); ?>';
        const _SCHOOL_LEVEL = '<?= $this->session->userdata('school_level'); ?>';
        const _ACADEMIC_YEAR = '<?= $this->session->userdata('_academic_year'); ?>';
        const _STUDENT = '<?= $this->session->userdata('_student'); ?>';
        const _EMPLOYEE = '<?= $this->session->userdata('_employee'); ?>';
        const _HEADMASTER = '<?= $this->session->userdata('_headmaster'); ?>';
        const _MAJOR = '<?= $this->session->userdata('_major'); ?>';
        const _SUBJECT = '<?= $this->session->userdata('_subject'); ?>';
        const _RECAPTCHA_STATUS = '<?= (NULL !== $this->session->userdata('recaptcha_status') && $this->session->userdata('recaptcha_status') == 'enable') ? 'true' : 'false'; ?>' == 'true';
    </script>
    <?php if (NULL !== $this->session->userdata('recaptcha_status') && $this->session->userdata('recaptcha_status') == 'enable') { ?>
        <script src="https://www.google.com/recaptcha/api.js?hl=id" async defer></script>
    <?php } ?>
    <script src="<?= base_url('assets/js/cosmo-template.min.js'); ?>"></script>
    <script src="<?= base_url('views/themes/academics/js/modernizr-2.8.3.min.js'); ?>"></script>
</head>

<body>
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <!-- Main Body Area Start Here -->
    <div id="wrapper">
        <?php $this->load->view($content) ?>
        <!-- Footer Area Start Here -->
        <footer>
            <div class="footer-area-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="footer-box">
                                <a href="<?= base_url() ?>"><img class="img-responsive" src="<?= base_url('media_library/images/') . $this->session->userdata('logo_footer'); ?>" alt="logo"></a>
                                <div class="footer-about">
                                    <p><?= $this->session->userdata('description_footer') ?></p>
                                </div>
                                <ul class="footer-social">
                                    <?php if (NULL !== $this->session->userdata('facebook') && $this->session->userdata('facebook')) { ?>
                                        <li><a href="<?= $this->session->userdata('facebook') ?>" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                    <?php } ?>
                                    <?php if (NULL !== $this->session->userdata('twitter') && $this->session->userdata('twitter')) { ?>
                                        <li><a href="<?= $this->session->userdata('twitter') ?>" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                    <?php } ?>
                                    <?php if (NULL !== $this->session->userdata('linkedin') && $this->session->userdata('linkedin')) { ?>
                                        <li><a href="<?= $this->session->userdata('linked_in') ?>" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
                                    <?php } ?>
                                    <?php if (NULL !== $this->session->userdata('google-plus') && $this->session->userdata('google-plus')) { ?>
                                        <li><a href="<?= $this->session->userdata('google_plus') ?>" title="Google +"><i class="fa fa-google-plus"></i></a></li>
                                    <?php } ?>
                                    <?php if (NULL !== $this->session->userdata('youtube') && $this->session->userdata('youtube')) { ?>
                                        <li><a href="<?= $this->session->userdata('youtube') ?>" title="Youtube"><i class="fa fa-youtube"></i></a></li>
                                    <?php } ?>
                                    <?php if (NULL !== $this->session->userdata('instagram') && $this->session->userdata('instagram')) { ?>
                                        <li><a href="<?= $this->session->userdata('instagram') ?>" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <div class="footer-box">
                                <h3>Tautan</h3>
                                <ul class="featured-links">
                                    <li>
                                        <ul>
                                            <?php
                                            $links = get_links();
                                            if ($links->num_rows() > 0) {
                                                foreach ($links->result() as $row) {
                                                    echo '<li>' . anchor($row->link_url, $row->link_title, ['target' => $row->link_target]) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="footer-box">
                                <h3>Kategori</h3>
                                <ul class="featured-links">
                                    <li>
                                        <ul>
                                            <?php
                                            $query = get_post_categories(10);
                                            if ($query->num_rows() > 0) {
                                                foreach ($query->result() as $row) {
                                                    echo '<li>' . anchor(site_url('category/' . $row->category_slug), $row->category_name, ['title' => $row->category_description]) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="footer-box">
                                <h3>Tags</h3>
                                <ul class="featured-links">
                                    <li>
                                        <ul>
                                            <?php
                                            $query = get_tags();
                                            if ($query->num_rows() > 0) {
                                                foreach ($query->result() as $row) {
                                                    echo '<li>' . anchor(site_url('tag/' . $row->slug), $row->tag) . '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="footer-box">
                                <h3>Information</h3>
                                <ul class="corporate-address">
                                    <li><i class="fa fa-map-marker" aria-hidden="true"></i><?= $this->session->userdata('street_address') ?></li>
                                    <li><i class="fa fa-phone" aria-hidden="true"></i><a href="Phone(01)800433633.html"> <?= $this->session->userdata('phone') ?> </a></li>
                                    <li><i class="fa fa-envelope-o" aria-hidden="true"></i><?= $this->session->userdata('email') ?></li>
                                </ul>
                                <div class="newsletter-area">
                                    <div class="input-group stylish-input-group">
                                        <input onkeydown="if (event.keyCode == 13) { subscriber(); return false; }" type="text" id="subscriber" placeholder="Berlangganan" autocomplete="off" class="form-control">
                                        <span class="input-group-addon">
                                            <button type="submit" onkeydown="if (event.keyCode == 13) { subscriber(); return false; }">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-area-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <p><?= copyright(2021, base_url(), $this->session->userdata('school_name')) ?>&nbsp; Designed by<a target="_blank" href="http://www.smknj.sch.id/"> Anshori & Salman</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Area End Here -->
    </div>
    <!-- Main Body Area End Here -->
    <!-- jquery-->
    <!-- Plugins js -->
    <script src="<?= base_url('views/themes/academics/js/plugins.js'); ?>" type="text/javascript"></script>
    <!-- Bootstrap js -->
    <script src="<?= base_url('views/themes/academics/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
    <!-- WOW JS -->
    <script src="<?= base_url('views/themes/academics/js/wow.min.js'); ?>"></script>
    <!-- Nivo slider js -->
    <script src="<?= base_url('views/themes/academics/vendor/slider/js/jquery.nivo.slider.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('views/themes/academics/vendor/slider/home.js'); ?>" type="text/javascript"></script>
    <!-- Owl Cauosel JS -->
    <script src="<?= base_url('views/themes/academics/vendor/OwlCarousel/owl.carousel.min.js'); ?>" type="text/javascript"></script>
    <!-- Meanmenu Js -->
    <script src="<?= base_url('views/themes/academics/js/jquery.meanmenu.min.js'); ?>" type="text/javascript"></script>
    <!-- Srollup js -->
    <script src="<?= base_url('views/themes/academics/js/jquery.scrollUp.min.js'); ?>" type="text/javascript"></script>
    <!-- jquery.counterup js -->
    <script src="<?= base_url('views/themes/academics/js/jquery.counterup.min.js'); ?>"></script>
    <script src="<?= base_url('views/themes/academics/js/waypoints.min.js'); ?>"></script>
    <!-- Countdown js -->
    <script src="<?= base_url('views/themes/academics/js/jquery.countdown.min.js'); ?>" type="text/javascript"></script>
    <!-- Isotope js -->
    <script src="<?= base_url('views/themes/academics/js/isotope.pkgd.min.js'); ?>" type="text/javascript"></script>
    <!-- Magic Popup js -->
    <script src="<?= base_url('views/themes/academics/js/jquery.magnific-popup.min.js'); ?>" type="text/javascript"></script>
    <!-- Gridrotator js -->
    <script src="<?= base_url('views/themes/academics/js/jquery.gridrotator.js'); ?>" type="text/javascript"></script>
    <!-- Custom Js -->
    <script src="<?= base_url('views/themes/academics/js/main.js'); ?>" type="text/javascript"></script>
</body>

</html>