<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
   <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6">
         <h3 style="margin:0;"><i class="fa fa-sign-out text-green"></i> <span class="table-header"><?=$title?></span> </h3>
      </div>
   </div>
</section>
<section class="content">
   <div class="row">
      <div class="col-md-2">
         <div class="box box-primary">
           <div class="box-body box-profile">
             <img class="profile-user-img img-responsive" <?=(isset($photo) && $photo) ? ('src="'.$photo.'"') : '' ?> alt="User profile picture">
             <h3 class="profile-username text-center"><?=$student->nama_lengkap?></h3>
             <p class="text-muted text-center"><?=$student->alamat_jalan?></p>
           </div>
         </div>
      </div>
      <div class="col-md-10">
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">REGISTRASI <?=strtoupper($this->session->userdata('_student'))?></a></li>
               <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">DATA PRIBADI</a></li>
               <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">DATA AYAH KANDUNG</a></li>
               <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">DATA IBU KANDUNG</a></li>
               <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">DATA WALI</a></li>
               <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">DATA PERIODIK</a></li>
               <?php if ($subjects->num_rows() > 0) { ?>
                  <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">VERIFIKASI NILAI</a></li>
               <?php } ?>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="tab_1">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Pendaftaran</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nomor_pendaftaran?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Pendaftaran</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=date('d M Y', strtotime($student->tanggal_pendaftaran))?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jenis Pendaftaran</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->jenis_pendaftaran?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jalur Pendaftaran</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->jalur_pendaftaran?></p>
                           </div>
                        </div>

                        <?php if (filter_var($this->session->userdata('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pilihan I</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pilihan_1?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pilihan II</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pilihan_2?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Sekolah Asal</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nama_sekolah_asal?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_prev_school_address')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Alamat Sekolah Asal</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->alamat_sekolah_asal?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_prev_exam_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Ujian Nasional Sebelumnya</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nomor_peserta_ujian?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_paud')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Apakah Pernah PAUD ?</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->paud?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_tk')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Apakah Pernah TK ?</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->tk?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_skhun')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Seri SKHUN Sebelumnya</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->skhun?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_prev_diploma_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Seri Ijazah Sebelumnya</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nomor_ijazah_sebelumnya?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_achievement')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Prestasi yang Pernah Diraih</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->prestasi?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_hobby')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Hobi</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->hobi?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_ambition')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Cita-cita</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->cita_cita?></p>
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_2">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Lengkap</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_lengkap?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jenis Kelamin</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->jenis_kelamin?></p>
                           </div>
                        </div>

                        <?php if (filter_var($this->session->userdata('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Induk Siswa Nasional / NISN</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nisn?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Induk Kependudukan / KTP</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nik?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tempat Lahir</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->tempat_lahir?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tanggal_lahir?></p>
                           </div>
                        </div>

                        <?php if (filter_var($this->session->userdata('form_religion_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Agama</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->agama?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kebutuhan Khusus</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kebutuhan_khusus?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_street_address')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Alamat Jalan</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->alamat_jalan?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_rt')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">RT</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->rt?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_rw')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">RW</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->rw?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_sub_village')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Dusun</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nama_dusun?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_village')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Kelurahan / Desa</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kelurahan?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_sub_district')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kecamatan</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kecamatan?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_district')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kabupaten</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kabupaten?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_postal_code')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kode Pos</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kode_pos?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_residence_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tempat Tinggal</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->jenis_tinggal?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_transportation_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Moda Transportasi</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->moda_transportasi?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mobile_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Handphone</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->handphone?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Telepon</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->telp?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_email')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Email Pribadi</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->email?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_kis')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Kartu Indonesia Sehat (KIS)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kis?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_kip')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Kartu Indonesia Pintar (KIP)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kip?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_kps')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Kartu Pra Sejahtera (KPS)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kps?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_kks')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Kartu Keluarga Sejahtera (KKS)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kks?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_sktm')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Surat Keterangan Tidak Mampu (SKTM)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->sktm?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_citizenship')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kewarganegaraan</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kewarganegaraan?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_country')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Negara</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nama_negara?></p>
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_3">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <?php if (filter_var($this->session->userdata('form_father_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Ayah</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nama_ayah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_father_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tahun Lahir Ayah</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->tahun_lahir_ayah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_father_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pendidikan Ayah</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pendidikan_ayah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_father_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pekerjaan Ayah</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pekerjaan_ayah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_father_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Penghasilan Bulanan Ayah</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->penghasilan_ayah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_father_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kebutuhan Khusus Ayah</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kebutuhan_khusus_ayah?></p>
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_4">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <?php if (filter_var($this->session->userdata('form_mother_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Ibu</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nama_ibu?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mother_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tahun Lahir Ibu</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->tahun_lahir_ibu?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mother_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pendidikan Ibu</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pendidikan_ibu?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mother_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pekerjaan Ibu</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pekerjaan_ibu?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mother_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Penghasilan Bulanan Ibu</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->penghasilan_ibu?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mother_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Kebutuhan Khusus Ibu</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->kebutuhan_khusus_ibu?></p>
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_5">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <?php if (filter_var($this->session->userdata('form_guardian_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nama Wali</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->nama_wali?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_guardian_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tahun Lahir Wali</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->tahun_lahir_wali?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_guardian_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pendidikan Wali</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pendidikan_wali?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_guardian_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Pekerjaan Wali</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->pekerjaan_wali?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_guardian_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Penghasilan Bulanan Wali</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->penghasilan_wali?></p>
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_6">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <?php if (filter_var($this->session->userdata('form_height')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tinggi Badan (Cm)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->tinggi_badan?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_weight')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Berat Badan (Kg)</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->berat_badan?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_mileage')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Jarak Tempat Tinggal ke <?=$this->session->userdata('school_level') >= 5 ? 'Kampus' : 'Sekolah'?></label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->jarak_tempuh_sekolah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_traveling_time')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Waktu Tempuh ke <?=$this->session->userdata('school_level') >= 5 ? 'Kampus' : 'Sekolah'?></label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->waktu_tempuh_sekolah?></p>
                              </div>
                           </div>
                        <?php } ?>

                        <?php if (filter_var($this->session->userdata('form_sibling_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Jumlah Saudara Kandung</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->jumlah_saudara_kandung?></p>
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </form>
               </div>
               <?php if ($subjects->num_rows() > 0) { ?>
                  <div class="tab-pane" id="tab_7">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th>KATEGORI NILAI</th>
                                 <th>MATA PELAJARAN</th>
                                 <th>NILAI</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $i = 0; $total = 0; foreach($subjects->result() as $row) { ?>
                                 <tr>
                                    <td><?=subject_desc($row->subject_type)?> </td>
                                    <td><?=$row->subject_name?></td>
                                    <td><?=$row->score?></td>
                                 </tr>
                              <?php $i++; $total += $row->score; } ?>
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th colspan="2">RATA-RATA</th>
                                 <th><?=round($total / $i, 2)?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            <?php } ?>
         </div>
      </div>
   </div>
</section>
