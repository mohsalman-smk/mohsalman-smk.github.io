<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
legend > h5 {
    color: grey;
    text-transform: uppercase;
    font-weight: bold;
}
.padding-top-10 {
    padding-top: 10px;
}
</style>
<script type="text/javascript">
$( document ).ready( function() {
    // Birth Date
    $('#birth_date').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: 'linked',
        minDate: '0001-01-01',
        setDate: new Date(),
        todayHighlight: true,
        autoclose: true
    });

    var citizenship = $('#citizenship').val();
    if (citizenship == 'WNI') {
        $('.country').hide();
    }
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
                    <div class="col-lg-12 col-md-9 col-sm-8 col-xs-12">
                        <form class="form-horizontal" role="form">
                            <div class="profile-details tab-content">
                                <div class="tab-pane fade active in" id="Personal">
                                    <h3 class="title-section title-bar-high mb-40">Registrasi Calon <?=$this->session->userdata('_student')?></h3>
                                    <div class="personal-info">

                <div class="form-group">
                    <label for="is_transfer" class="col-sm-4 control-label">Jenis Pendaftaran <?=filter_var($this->session->userdata('form_is_transfer')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                    <div class="col-sm-8">
                        <?=form_dropdown('is_transfer', ['' => 'Pilih :', 'false' => 'Baru', 'true' => 'Pindahan'], set_value('is_transfer'), 'class="form-control input-sm" id="is_transfer"')?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="admission_type_id" class="col-sm-4 control-label">Jalur Pendaftaran <?=filter_var($this->session->userdata('form_admission_type_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                    <div class="col-sm-8">
                        <?=form_dropdown('admission_type_id', $admission_types, set_value('admission_type_id'), 'class="form-control input-sm" id="admission_type_id" onchange="get_subject_settings()" onblur="get_subject_settings()" onmouseup="get_subject_settings()"')?>
                    </div>
                </div>

                <?php if (filter_var($this->session->userdata('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="first_choice_id" class="col-sm-4 control-label">Pilihan I <?=filter_var($this->session->userdata('form_first_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('first_choice_id', $majors, set_value('first_choice_id'), 'class="form-control input-sm" id="first_choice_id" onchange="check_options(1); get_subject_settings();" onblur="check_options(1); get_subject_settings();" onmouseup="check_options(1); get_subject_settings();"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="second_choice_id" class="col-sm-4 control-label">Pilihan II <?=filter_var($this->session->userdata('form_second_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('second_choice_id', $majors, set_value('second_choice_id'), 'class="form-control input-sm" id="second_choice_id" onchange="check_options(2); get_subject_settings();" onblur="check_options(2); get_subject_settings();" onmouseup="check_options(2); get_subject_settings();"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="prev_school_name" class="col-sm-4 control-label">Nama Sekolah Asal <?=filter_var($this->session->userdata('form_prev_school_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('prev_school_name')?>" class="form-control input-sm" id="prev_school_name" name="prev_school_name">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_prev_school_address')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="prev_school_address" class="col-sm-4 control-label">Alamat Sekolah Asal <?=filter_var($this->session->userdata('form_prev_school_address')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('prev_school_address')?>" class="form-control input-sm" id="prev_school_address" name="prev_school_address">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_prev_exam_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="prev_exam_number" class="col-sm-4 control-label">Nomor Ujian Nasional Sebelumnya <?=filter_var($this->session->userdata('form_prev_exam_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('prev_exam_number')?>" class="form-control input-sm" id="prev_exam_number" name="prev_exam_number">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_paud')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="paud" class="col-sm-4 control-label">Apakah pernah PAUD <?=filter_var($this->session->userdata('form_paud')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('paud', ['' => 'Pilih :', 'false' => 'Tidak', 'true' => 'Ya'], set_value('paud'), 'class="form-control input-sm" id="paud"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_tk')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="tk" class="col-sm-4 control-label">Apakah pernah TK <?=filter_var($this->session->userdata('form_tk')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('tk', ['' => 'Pilih :', 'false' => 'Tidak', 'true' => 'Ya'], set_value('tk'), 'class="form-control input-sm" id="tk"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_skhun')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="skhun" class="col-sm-4 control-label">Nomor Seri SKHUN Sebelumnya <?=filter_var($this->session->userdata('form_skhun')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('skhun')?>" class="form-control input-sm" id="skhun" name="skhun" placeholder="Nomor Surat Keterangan Hasil Ujian Nasional">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_prev_diploma_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="prev_diploma_number" class="col-sm-4 control-label">Nomor Seri Ijazah Sebelumnya <?=filter_var($this->session->userdata('form_prev_diploma_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('prev_diploma_number')?>" class="form-control input-sm" id="prev_diploma_number" name="prev_diploma_number" placeholder="Nomor Seri Ijazah Sebelumnya">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_achievement')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="file" class="col-sm-4 control-label">Prestasi yang Pernah Diraih <?=filter_var($this->session->userdata('form_achievement')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <textarea rows="5" name="achievement" id="achievement" class="form-control"></textarea>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_hobby')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="hobby" class="col-sm-4 control-label">Hobi <?=filter_var($this->session->userdata('form_hobby')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('hobby')?>" class="form-control input-sm" id="hobby" name="hobby">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_ambition')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="ambition" class="col-sm-4 control-label">Cita-cita <?=filter_var($this->session->userdata('form_ambition')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('ambition')?>" class="form-control input-sm" id="ambition" name="ambition">
                        </div>
                    </div>
                <?php } ?>

                <legend class="padding-top-10"><h5><i class="fa fa-sign-out"></i> Biodata Calon <?=$this->session->userdata('school_level') >= 5 ? 'Mahasiswa' : 'Peserta Didik' ?></h5></legend>
                <div class="form-group">
                    <label for="full_name" class="col-sm-4 control-label">Nama Lengkap <?=filter_var($this->session->userdata('form_full_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo set_value('full_name')?>" class="form-control input-sm" id="full_name" name="full_name">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender" class="col-sm-4 control-label">Jenis Kelamin <?=filter_var($this->session->userdata('form_gender')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                    <div class="col-sm-8">
                        <?=form_dropdown('gender', ['' => 'Pilih :', 'M' => 'Laki-laki', 'F' => 'Perempuan'], set_value('gender'), 'class="form-control input-sm" id="gender"')?>
                    </div>
                </div>

                <?php if (filter_var($this->session->userdata('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="nisn" class="col-sm-4 control-label">NISN <?=filter_var($this->session->userdata('form_nisn')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('nisn')?>" class="form-control input-sm" id="nisn" name="nisn" placeholder="Nomor Induk Sekolah Nasional">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="nik" class="col-sm-4 control-label">Nomor Induk Kependudukan / KTP <?=filter_var($this->session->userdata('form_nik')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('nik')?>" class="form-control input-sm" id="nik" name="nik">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="birth_place" class="col-sm-4 control-label">Tempat Lahir <?=filter_var($this->session->userdata('form_birth_place')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('birth_place')?>" class="form-control input-sm" id="birth_place" name="birth_place">
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input readonly="true" type="text" value="<?php echo set_value('birth_date')?>" class="form-control input-sm" id="birth_date" name="birth_date">
                            <span class="input-group-addon input-sm"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <?php if (filter_var($this->session->userdata('form_religion_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="religion_id" class="col-sm-4 control-label">Agama <?=filter_var($this->session->userdata('form_religion_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('religion_id', $religions, set_value('religion_id'), 'class="form-control input-sm" id="religion_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="special_need_id" class="col-sm-4 control-label">Kebutuhan Khusus <?=filter_var($this->session->userdata('form_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('special_need_id', $special_needs, set_value('special_need_id'), 'class="form-control input-sm" id="special_need_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_street_address')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="street_address" class="col-sm-4 control-label">Alamat Jalan <?=filter_var($this->session->userdata('form_street_address')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <textarea rows="4" name="street_address" id="street_address" class="form-control input-sm"><?php echo set_value('street_address')?></textarea>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_rt')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="rt" class="col-sm-4 control-label">RT <?=filter_var($this->session->userdata('form_rt')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('rt')?>" class="form-control input-sm" id="rt" name="rt" placeholder="Rukun Tetangga">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_rw')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="rw" class="col-sm-4 control-label">RW <?=filter_var($this->session->userdata('form_rw')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('rw')?>" class="form-control input-sm" id="rw" name="rw" placeholder="Rukun Warga">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_sub_village')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="sub_village" class="col-sm-4 control-label">Nama Dusun <?=filter_var($this->session->userdata('form_sub_village')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('sub_village')?>" class="form-control input-sm" id="sub_village" name="sub_village">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_village')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="village" class="col-sm-4 control-label">Nama Kelurahan/ Desa <?=filter_var($this->session->userdata('form_village')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('village')?>" class="form-control input-sm" id="village" name="village">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_sub_district')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="sub_district" class="col-sm-4 control-label">Kecamatan <?=filter_var($this->session->userdata('form_sub_district')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('sub_district')?>" class="form-control input-sm" id="sub_district" name="sub_district">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_district')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="district" class="col-sm-4 control-label">Kabupaten <?=filter_var($this->session->userdata('form_district')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('district')?>" class="form-control input-sm" id="district" name="district">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_postal_code')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="postal_code" class="col-sm-4 control-label">Kode Pos <?=filter_var($this->session->userdata('form_postal_code')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('postal_code')?>" class="form-control input-sm" id="postal_code" name="postal_code">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_residence_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="residence_id" class="col-sm-4 control-label">Tempat Tinggal <?=filter_var($this->session->userdata('form_residence_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('residence_id', $residences, set_value('residence_id'), 'class="form-control input-sm" id="residence_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_transportation_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="transportation_id" class="col-sm-4 control-label">Moda Transportasi <?=filter_var($this->session->userdata('form_transportation_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('transportation_id', $transportations, set_value('transportation_id'), 'class="form-control input-sm" id="transportation_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mobile_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mobile_phone" class="col-sm-4 control-label">Nomor Handphone <?=filter_var($this->session->userdata('form_mobile_phone')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('mobile_phone')?>" class="form-control input-sm" id="mobile_phone" name="mobile_phone">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="phone" class="col-sm-4 control-label">Nomor Telepon <?=filter_var($this->session->userdata('form_phone')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('phone')?>" class="form-control input-sm" id="phone" name="phone">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_email')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="email" class="col-sm-4 control-label">E-mail Pribadi <?=filter_var($this->session->userdata('form_email')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('email')?>" class="form-control input-sm" id="email" name="email">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_kis')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group kis">
                        <label for="kis" class="col-sm-4 control-label">Nomor Kartu Indonesia Sehat (KIS) <?=filter_var($this->session->userdata('form_kis')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('kis')?>" class="form-control input-sm" id="kis" name="kis">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_kip')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group kip">
                        <label for="kip" class="col-sm-4 control-label">Nomor Kartu Indonesia Pintar (KIP) <?=filter_var($this->session->userdata('form_kip')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('kip')?>" class="form-control input-sm" id="kip" name="kip">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_kps')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group kps">
                        <label for="kps" class="col-sm-4 control-label">Nomor Kartu Pra Sejahtera (KPS) <?=filter_var($this->session->userdata('form_kps')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('kps')?>" class="form-control input-sm" id="kps" name="kps">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_kks')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group kks">
                        <label for="kks" class="col-sm-4 control-label">Nomor Kartu Keluarga Sejahtera (KKS) <?=filter_var($this->session->userdata('form_kks')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('kks')?>" class="form-control input-sm" id="kks" name="kks">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_sktm')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group sktm">
                        <label for="sktm" class="col-sm-4 control-label">Nomor Surat Keterangan Tidak Mampu (SKTM) <?=filter_var($this->session->userdata('form_sktm')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('sktm')?>" class="form-control input-sm" id="sktm" name="sktm">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_citizenship')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="citizenship" class="col-sm-4 control-label">Kewarganegaraan <?=filter_var($this->session->userdata('form_citizenship')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <select name="citizenship" id="citizenship" class="form-control input-sm" onchange="change_country_field()" onblur="change_country_field()" onmouseup="change_country_field()">
                                <option value="">Pilih :</option>
                                <option value="WNI">Warga Negara Indonesia (WNI)</option>
                                <option value="WNA">Warga Negara Asing (WNA)</option>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_country')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group country">
                        <label for="country" class="col-sm-4 control-label">Nama Negara <?=filter_var($this->session->userdata('form_country')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('country')?>" class="form-control input-sm" id="country" name="country" placeholder="Diisi jika warga negara asing">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_photo')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="file" class="col-sm-4 control-label">Foto <?=filter_var($this->session->userdata('form_photo')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="file" id="photo" name="photo">
                            <p class="help-block">Foto harus bertipe JPG dan ukuran foto maksimal 1 MB</p>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_father_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="father_name" class="col-sm-4 control-label">Nama Ayah <?=filter_var($this->session->userdata('form_father_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('father_name')?>" class="form-control input-sm" id="father_name" name="father_name">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_father_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="father_birth_year" class="col-sm-4 control-label">Tahun Lahir Ayah <?=filter_var($this->session->userdata('form_father_birth_year')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('father_birth_year')?>" class="form-control input-sm" id="father_birth_year" name="father_birth_year" placeholder="Tahun Lahir Ayah Kandung. contoh : 1965">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_father_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="father_education_id" class="col-sm-4 control-label">Pendidikan Ayah <?=filter_var($this->session->userdata('form_father_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('father_education_id', $educations, set_value('father_education_id'), 'class="form-control input-sm" id="father_education_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_father_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="father_employment_id" class="col-sm-4 control-label">Pekerjaan Ayah <?=filter_var($this->session->userdata('form_father_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('father_employment_id', $employments, set_value('father_employment_id'), 'class="form-control input-sm" id="father_employment_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_father_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="father_monthly_income_id" class="col-sm-4 control-label">Penghasilan Bulanan Ayah <?=filter_var($this->session->userdata('form_father_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('father_monthly_income_id', $monthly_incomes, set_value('father_monthly_income_id'), 'class="form-control input-sm" id="father_monthly_income_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_father_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="father_special_need_id" class="col-sm-4 control-label">Kebutuhan Khusus Ayah <?=filter_var($this->session->userdata('form_father_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('father_special_need_id', $special_needs, set_value('father_special_need_id'), 'class="form-control input-sm" id="father_special_need_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mother_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mother_name" class="col-sm-4 control-label">Nama Ibu <?=filter_var($this->session->userdata('form_mother_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('mother_name')?>" class="form-control input-sm" id="mother_name" name="mother_name">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mother_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mother_birth_year" class="col-sm-4 control-label">Tahun Lahir Ibu <?=filter_var($this->session->userdata('form_mother_birth_year')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('mother_birth_year')?>" class="form-control input-sm" id="mother_birth_year" name="mother_birth_year" placeholder="Tahun Lahir Ibu Kandung. contoh : 1965">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mother_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mother_education_id" class="col-sm-4 control-label">Pendidikan Ibu <?=filter_var($this->session->userdata('form_mother_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('mother_education_id', $educations, set_value('mother_education_id'), 'class="form-control input-sm" id="mother_education_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mother_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mother_employment_id" class="col-sm-4 control-label">Pekerjaan Ibu <?=filter_var($this->session->userdata('form_mother_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('mother_employment_id', $employments, set_value('mother_employment_id'), 'class="form-control input-sm" id="mother_employment_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mother_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mother_monthly_income_id" class="col-sm-4 control-label">Penghasilan Bulanan Ibu <?=filter_var($this->session->userdata('form_mother_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('mother_monthly_income_id', $monthly_incomes, set_value('mother_monthly_income_id'), 'class="form-control input-sm" id="mother_monthly_income_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mother_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mother_special_need_id" class="col-sm-4 control-label">Kebutuhan Khusus Ibu <?=filter_var($this->session->userdata('form_mother_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('mother_special_need_id', $special_needs, set_value('mother_special_need_id'), 'class="form-control input-sm" id="mother_special_need_id"')?>
                        </div>
                    </div>
                <?php } ?>


                <?php if (filter_var($this->session->userdata('form_guardian_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="guardian_name" class="col-sm-4 control-label">Nama Wali <?=filter_var($this->session->userdata('form_guardian_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('guardian_name')?>" class="form-control input-sm" id="guardian_name" name="guardian_name">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_guardian_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="guardian_birth_year" class="col-sm-4 control-label">Tahun Lahir Wali <?=filter_var($this->session->userdata('form_guardian_birth_year')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('guardian_birth_year')?>" class="form-control input-sm" id="guardian_birth_year" name="guardian_birth_year" placeholder="Tahun Lahir Wali. contoh : 1965">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_guardian_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="guardian_education_id" class="col-sm-4 control-label">Pendidikan Wali <?=filter_var($this->session->userdata('form_guardian_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('guardian_education_id', $educations, set_value('guardian_education_id'), 'class="form-control input-sm" id="guardian_education_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_guardian_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="guardian_employment_id" class="col-sm-4 control-label">Pekerjaan Wali <?=filter_var($this->session->userdata('form_guardian_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('guardian_employment_id', $employments, set_value('guardian_employment_id'), 'class="form-control input-sm" id="guardian_employment_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_guardian_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="guardian_monthly_income_id" class="col-sm-4 control-label">Penghasilan Bulanan Wali <?=filter_var($this->session->userdata('form_guardian_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <?=form_dropdown('guardian_monthly_income_id', $monthly_incomes, set_value('guardian_monthly_income_id'), 'class="form-control input-sm" id="guardian_monthly_income_id"')?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_height')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="height" class="col-sm-4 control-label">Tinggi Badan (Cm) <?=filter_var($this->session->userdata('form_height')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="number" value="<?php echo set_value('height')?>" class="form-control input-sm" id="height" name="height">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_weight')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="weight" class="col-sm-4 control-label">Berat Badan (Kg) <?=filter_var($this->session->userdata('form_weight')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="number" value="<?php echo set_value('weight')?>" class="form-control input-sm" id="weight" name="weight">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_mileage')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="mileage" class="col-sm-4 control-label">Jarak Tempat Tinggal ke Sekolah (Km) <?=filter_var($this->session->userdata('form_mileage')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo set_value('mileage')?>" class="form-control input-sm" id="mileage" name="mileage">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_traveling_time')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="traveling_time" class="col-sm-4 control-label">Waktu Tempuh ke Sekolah (Menit) <?=filter_var($this->session->userdata('form_traveling_time')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="number" value="<?php echo set_value('traveling_time')?>" class="form-control input-sm" id="traveling_time" name="traveling_time">
                        </div>
                    </div>
                <?php } ?>

                <?php if (filter_var($this->session->userdata('form_sibling_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                    <div class="form-group">
                        <label for="sibling_number" class="col-sm-4 control-label">Jumlah Saudara Kandung <?=filter_var($this->session->userdata('form_sibling_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
                        <div class="col-sm-8">
                            <input type="number" value="<?php echo set_value('sibling_number')?>" class="form-control input-sm" id="sibling_number" name="sibling_number">
                        </div>
                    </div>
                <?php } ?>

                <div class="subject_scores"></div>

                <div class="form-group">
                    <label for="declaration" class="col-sm-4 control-label">Pernyataan <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <div class="checkbox" style="top: -7px;">
                            <label>
                                <input type="checkbox" name="declaration" id="declaration"> Saya yang bertandatangan dibawah ini menyatakan bahwa data yang tertera diatas adalah yang sebenarnya.
                            </label>
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
                        <button type="button" onclick="save_registration_form(); return false;" class="btn btn-success"><i class="fa fa-save"></i> SIMPAN FORMULIR PENDAFTARAN</button>
                    </div>
                </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Account Page End Here -->
