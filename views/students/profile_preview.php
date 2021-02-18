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
               <?php if ($scholarships->num_rows() > 0) { ?>
                  <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">BEASISWA</a></li>
               <?php } ?>
               <?php if ($achievements->num_rows() > 0) { ?>
                  <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false">PRESTASI</a></li>
               <?php } ?>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="tab_1">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Siswa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->identity_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Siswa Nasional / NISN</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nisn?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Sekolah Asal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_sekolah_asal?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Alamat Sekolah Asal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->alamat_sekolah_asal?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Ujian Nasional Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nomor_peserta_ujian?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Apakah Pernah PAUD ?</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->paud?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Apakah Pernah TK ?</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tk?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Seri SKHUN Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->skhun?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Seri Ijazah Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nomor_ijazah_sebelumnya?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Prestasi yang Pernah Diraih</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prestasi?></p>
                           </div>
                        </div>

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



                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Kependudukan / KTP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nik?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tempat_lahir?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tanggal_lahir?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Agama</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->agama?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kebutuhan_khusus?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Alamat Jalan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->alamat_jalan?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">RT</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->rt?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">RW</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->rw?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Dusun</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_dusun?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Kelurahan / Desa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kelurahan?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kecamatan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kecamatan?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kabupaten</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kabupaten?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kode Pos</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kode_pos?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Tinggal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->jenis_tinggal?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Moda Transportasi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->moda_transportasi?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Handphone</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->handphone?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Telepon</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->telp?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Email Pribadi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->email?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Indonesia Sehat (KIS)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kis?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Indonesia Pintar (KIP)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kip?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Pra Sejahtera (KPS)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kps?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Keluarga Sejahtera (KKS)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kks?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Surat Keterangan Tidak Mampu (SKTM)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sktm?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kewarganegaraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kewarganegaraan?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Negara</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_negara?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Hobi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->hobi?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Cita-cita</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->cita_cita?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_3">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_ayah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tahun Lahir Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tahun_lahir_ayah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->pendidikan_ayah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->pekerjaan_ayah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->penghasilan_ayah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kebutuhan_khusus_ayah?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_4">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_ibu?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tahun Lahir Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tahun_lahir_ibu?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->pendidikan_ibu?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->pekerjaan_ibu?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->penghasilan_ibu?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kebutuhan_khusus_ibu?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_5">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nama_wali?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tahun Lahir Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tahun_lahir_wali?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->pendidikan_wali?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->pekerjaan_wali?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->penghasilan_wali?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_6">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tinggi Badan (Cm)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tinggi_badan?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Berat Badan (Kg)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->berat_badan?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jarak Tempat Tinggal ke <?=$this->session->userdata('school_level') >= 5 ? 'Kampus' : 'Sekolah'?></label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->jarak_tempuh_sekolah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Waktu Tempuh ke <?=$this->session->userdata('school_level') >= 5 ? 'Kampus' : 'Sekolah'?></label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->waktu_tempuh_sekolah?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jumlah Saudara Kandung</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->jumlah_saudara_kandung?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <?php if ($scholarships->num_rows() > 0) { ?>
                  <div class="tab-pane" id="tab_7">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th>NAMA BEASISWA</th>
                                 <th>JENIS BEASISWA</th>
                                 <th>TAHUN MULAI</th>
                                 <th>TAHUN SELESAI</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($scholarships->result() as $row) { ?>
                                 <tr>
                                    <td><?=$row->scholarship_description?></td>
                                    <td><?=scholarship_types($row->scholarship_type)?></td>
                                    <td><?=$row->scholarship_start_year?></td>
                                    <td><?=$row->scholarship_end_year?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               <?php } ?>
               <?php if ($achievements->num_rows() > 0) { ?>
                  <div class="tab-pane" id="tab_8">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th>NAMA PRESTASI</th>
                                 <th>JENIS PRESTASI</th>
                                 <th>TINGKAT</th>
                                 <th>TAHUN</th>
                                 <th>PENYELENGGARA</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($achievements->result() as $row) { ?>
                                 <tr>
                                    <td><?=$row->achievement_description?></td>
                                    <td><?=achievement_types($row->achievement_type)?></td>
                                    <td><?=achievement_levels($row->achievement_level)?></td>
                                    <td><?=$row->achievement_year?></td>
                                    <td><?=$row->achievement_organizer?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </section>
