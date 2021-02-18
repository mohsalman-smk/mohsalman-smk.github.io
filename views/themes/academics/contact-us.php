<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
<?php $this->load->view('themes/academics/menu') ?>
<!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('<?= base_url('media_library/images/') . $this->session->userdata('header_3_image'); ?>');?>');">
    <div class="container">
        <div class="pagination-area">
            <h1><?= strtoupper($page_title) ?></h1>
            <ul>
                <li><a href="<?= base_url() ?>">Home</a> -</li>
                <li><?= strtoupper($page_title) ?></li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<!-- Contact Us Page 1 Area Start Here -->
<div class="contact-us-page1-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="contact-us-info1">
                    <ul>
                        <li>
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <h3>Telepon</h3>
                            <p><?= $this->session->userdata('phone') ?></p>
                        </li>
                        <li>
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <h3>Alamat</h3>
                            <p><?= $this->session->userdata('street_address') ?></p>
                        </li>
                        <li>
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                            <h3>E-mail</h3>
                            <p><?= $this->session->userdata('email') ?></p>
                        </li>
                        <li>
                            <h3>Follow Us</h3>
                            <ul class="contact-social">
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
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 class="title-default-left title-bar-high"><?= strtoupper($page_title) ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="contact-form1">
                        <form id="contact-form">
                            <fieldset>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="Nama Lengkap*" class="form-control" id="comment_author" name="comment_author" data-error="Nama Lengkap field is required" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="email" placeholder="Email*" class="form-control" id="comment_email" name="comment_email" data-error="Email field is required" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="email" placeholder="URL*" class="form-control" id="comment_url" name="comment_url" data-error="URL field is required" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea placeholder="Pesan*" class="textarea form-control" id="comment_content" name="comment_content" rows="8" cols="20" data-error="Message field is required" required></textarea>
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
                                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12">
                                    <div class="form-group margin-bottom-none">
                                        <button type="submit" class="default-big-btn" onclick="contact_us(); return false;">Kirim Pesan</button>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-6 col-sm-12">
                                    <div class='form-response'></div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="google-map-area">
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $api_key ?>&callback=initMap"></script>
                <div id="map" style="width:100%; height:395px; background-color: grey;"></div>
                <script>
                    var latitude = <?= $latitude ?>;
                    var longitude = <?= $longitude ?>;

                    function initMap() {
                        var coordinate = {
                            lat: latitude,
                            lng: longitude
                        };
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 15,
                            center: coordinate,
                            mapTypeId: 'roadmap'
                        });
                        var marker = new google.maps.Marker({
                            position: coordinate,
                            map: map
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<!-- Contact Us Page 1 Area End Here -->