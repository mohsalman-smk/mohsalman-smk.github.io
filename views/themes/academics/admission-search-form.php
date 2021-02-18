<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
$( document ).ready( function() {
    $( document ).find( 'input.datepicker:enabled' ).datepicker({
        format: 'yyyy-mm-dd',
        startView: 1,
        todayBtn: true,
        minDate: '0001-01-01',
        setDate: new Date(),
        todayHighlight: true,
        autoclose: true
    });
});
</script>
<?php $this->load->view('themes/academics/menu')?>
        <!-- Inner Page Banner Area Start Here -->
        <div class="inner-page-banner-area" style="background-image: url('<?=base_url('media_library/images/').$this->session->userdata('header_3_image');?>');">
            <div class="container">
                <div class="pagination-area">
                    <h1><?=strtoupper($page_title)?></h1>
                    <ul>
                        <li><a href="#">Home</a> -</li>
                        <li>Account</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Inner Page Banner Area End Here -->
        <!-- Account Page Start Here -->
        <div class="section-space accent-bg">
            <div class="container">
                <div class="row">

                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                        <form class="form-horizontal" role="form" action="<?=$action?>">
                            <div class="profile-details tab-content">
                                <div class="tab-pane fade active in" id="Personal">
                                    <h3 class="title-section title-bar-high mb-40"><?=strtoupper($page_title)?></h3>
                                    <div class="personal-info">




                                        
                <div class="form-group">
                    <label for="registration_number" class="col-sm-4 control-label">Nomor Pendaftaran <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="registration_number" name="registration_number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="birth_date" name="birth_date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <?php if (NULL !== $this->session->userdata('recaptcha_status') && $this->session->userdata('recaptcha_status') == 'enable') { ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <div class="g-recaptcha" data-sitekey="<?=$recaptcha_site_key?>"></div>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button type="button" onclick="<?=$onclick?>; return false;" class="btn btn-success"><?=$button?></button>
                    </div>
                </div>

                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <?php $this->load->view('themes/academics/sidebar')?>
                </div>
            </div>
        </div>
        <!-- Account Page End Here -->