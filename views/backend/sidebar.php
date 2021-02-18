<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="sidebar">
	<ul class="sidebar-menu" data-widget="tree">

		<?php if (in_array('dashboard', $this->session->userdata('user_privileges'))) { ?>
			<li <?=isset($dashboard) ? 'class="active"':''?>>
				<a href="<?=site_url('dashboard');?>">
					<i class="fa fa-dashboard"></i> <span>BERANDA</span>
				</a>
			</li>
		<?php } ?>

		<li>
			<a href="<?=base_url();?>" target="_blank">
				<i class="fa fa-rocket"></i> <span>LIHAT SITUS</span>
			</a>
		</li>

		<!-- @namespace Employee Menu -->
		<?php if ($this->session->userdata('user_type') === 'employee' && in_array('employee_profile', $this->session->userdata('user_privileges'))) { ?>
			<li class="<?=isset($employee_profile) ? 'active':'';?>">
				<a href="<?=site_url('employee_profile');?>">
					<i class="fa fa-user"></i> <span>PROFIL</span>
				</a>
			</li>
			<li class="<?=isset($posts) ? 'active':'';?>">
				<a href="<?=site_url('posts');?>">
					<i class="fa fa-edit"></i> <span>TULISAN</span>
				</a>
			</li>
			<!-- Khusus Guru -->
			<?php if (NULL !== $this->session->userdata('employment_type') && strpos(strtolower($this->session->userdata('employment_type')), 'guru') !== FALSE) { ?>
				<li class="<?=isset($academic_schedules) ? 'active':'';?>">
					<a href="<?=site_url('teacher/academic_schedules');?>">
						<i class="fa fa-calendar-check-o"></i> <span>JADWAL MENGAJAR</span>
					</a>
				</li>
				<li class="<?=isset($meeting_attendance_report) ? 'active':'';?>">
					<a href="<?=site_url('teacher/meeting_attendance_report');?>">
						<i class="fa fa-file-excel-o"></i> <span>REKAP PRESENSI</span>
					</a>
				</li>
			<?php } ?>

		<?php } ?>

		<!-- @namespace Student Menu -->
		<?php if ($this->session->userdata('user_type') === 'student' && in_array('student_profile', $this->session->userdata('user_privileges'))) { ?>
			<li class="<?=isset($scholarships) ? 'active':'';?>">
				<a href="<?=site_url('scholarships');?>">
					<i class="fa fa-money"></i> <span>BEASISWA</span>
				</a>
			</li>
			<li class="<?=isset($achievements) ? 'active':'';?>">
				<a href="<?=site_url('achievements');?>">
					<i class="fa fa-trophy"></i> <span>PRESTASI</span>
				</a>
			</li>
			<?php if (NULL !== $this->session->userdata('is_alumni') && !$this->session->userdata('is_alumni')) { ?>
				<li class="<?=isset($student_presence) ? 'active':'';?>">
					<a href="<?=site_url('student_presence');?>">
						<i class="fa fa-calendar-check-o"></i> <span>PRESENSI</span>
					</a>
				</li>
			<?php } ?>
			<li class="<?=isset($student_profile) ? 'active':'';?>">
				<a href="<?=site_url('student_profile');?>">
					<i class="fa fa-edit"></i> <span>PROFIL</span>
				</a>
			</li>
			<li class="<?=isset($posts) ? 'active':'';?>">
				<a href="<?=site_url('posts');?>">
					<i class="fa fa-edit"></i> <span>TULISAN</span>
				</a>
			</li>
		<?php } ?>

		<!-- @namespace Administrator Menu -->
		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('blog', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($blog) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-edit"></i> <span>BLOG</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($image_sliders) ? 'class="active"':'';?>><a href="<?=site_url('blog/image_sliders');?>"><i class="fa fa-sign-out"></i> Gambar Slide</a></li>
						<li <?=isset($lecturers_area) ? 'class="active"':'';?>><a href="<?=site_url('blog/lecturers_area');?>"><i class="fa fa-sign-out"></i> Direktori Guru dan Staf</a></li>
						<li <?=isset($student_say) ? 'class="active"':'';?>><a href="<?=site_url('blog/student_say');?>"><i class="fa fa-sign-out"></i> Testimoni <?=$this->session->userdata('school_level') >= 5 ? 'Mahasiswa' : 'Siswa'?></a></li>
						<li <?=isset($messages) ? 'class="active"':'';?>><a href="<?=site_url('blog/messages');?>"><i class="fa fa-sign-out"></i> Pesan Masuk</a></li>
						<li <?=isset($links) ? 'class="active"':'';?>><a href="<?=site_url('blog/links');?>"><i class="fa fa-sign-out"></i> Tautan</a></li>
						<li <?=isset($pages) ? 'class="active"':'';?>><a href="<?=site_url('blog/pages');?>"><i class="fa fa-sign-out"></i> Halaman</a></li>
						<li class="treeview <?=isset($posts) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Tulisan <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($all_posts) ? 'class="active"':'';?>><a href="<?=site_url('blog/posts');?>"><i class="fa fa-sign-out"></i> Semua Tulisan</a></li>
								<li <?=isset($post_create) ? 'class="active"':'';?>><a href="<?=site_url('blog/posts/create');?>"><i class="fa fa-sign-out"></i> Tambah Baru</a></li>
								<li <?=isset($post_categories) ? 'class="active"':'';?>><a href="<?=site_url('blog/post_categories');?>"><i class="fa fa-sign-out"></i> Kategori Tulisan</a></li>
								<li <?=isset($post_comments) ? 'class="active"':'';?>><a href="<?=site_url('blog/post_comments');?>"><i class="fa fa-sign-out"></i> Komentar</a></li>
								<li <?=isset($tags) ? 'class="active"':'';?>><a href="<?=site_url('blog/tags');?>"><i class="fa fa-sign-out"></i> Tags</a></li>
							</ul>
						</li>
						<li class="treeview <?=isset($event) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Acara Akan Datang <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($all_event) ? 'class="active"':'';?>><a href="<?=site_url('blog/event');?>"><i class="fa fa-sign-out"></i> Semua Tulisan</a></li>
								<li <?=isset($event_create) ? 'class="active"':'';?>><a href="<?=site_url('blog/event/create');?>"><i class="fa fa-sign-out"></i> Tambah Baru</a></li>
								<li <?=isset($post_categories) ? 'class="active"':'';?>><a href="<?=site_url('blog/post_categories');?>"><i class="fa fa-sign-out"></i> Kategori Tulisan</a></li>
								<li <?=isset($post_comments) ? 'class="active"':'';?>><a href="<?=site_url('blog/post_comments');?>"><i class="fa fa-sign-out"></i> Komentar</a></li>
								<li <?=isset($tags) ? 'class="active"':'';?>><a href="<?=site_url('blog/tags');?>"><i class="fa fa-sign-out"></i> Tags</a></li>
							</ul>
						</li>
						<li class="treeview <?=isset($announcement) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Pengumuman <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($all_announcement) ? 'class="active"':'';?>><a href="<?=site_url('blog/announcement');?>"><i class="fa fa-sign-out"></i> Semua Tulisan</a></li>
								<li <?=isset($announcement_create) ? 'class="active"':'';?>><a href="<?=site_url('blog/announcement/create');?>"><i class="fa fa-sign-out"></i> Tambah Baru</a></li>
								<li <?=isset($post_categories) ? 'class="active"':'';?>><a href="<?=site_url('blog/post_categories');?>"><i class="fa fa-sign-out"></i> Kategori Tulisan</a></li>
								<li <?=isset($post_comments) ? 'class="active"':'';?>><a href="<?=site_url('blog/post_comments');?>"><i class="fa fa-sign-out"></i> Komentar</a></li>
								<li <?=isset($tags) ? 'class="active"':'';?>><a href="<?=site_url('blog/tags');?>"><i class="fa fa-sign-out"></i> Tags</a></li>
							</ul>
						</li>
						<li <?=isset($quotes) ? 'class="active"':'';?>><a href="<?=site_url('blog/quotes');?>"><i class="fa fa-sign-out"></i> Kutipan</a></li>
						<li <?=isset($counter_area) ? 'class="active"':'';?>><a href="<?=site_url('blog/counter_area');?>"><i class="fa fa-sign-out"></i> Counter Area</a></li>
						<li <?=isset($service_area) ? 'class="active"':'';?>><a href="<?=site_url('blog/service_area');?>"><i class="fa fa-sign-out"></i> Service Area</a></li>
						<li <?=isset($welcome) ? 'class="active"':'';?>><a href="<?=site_url('blog/welcome');?>"><i class="fa fa-sign-out"></i> Sambutan <?=$this->session->userdata('_headmaster')?></a></li>
						<li <?=isset($subscribers) ? 'class="active"':'';?>><a href="<?=site_url('blog/subscribers');?>"><i class="fa fa-sign-out"></i> Subscribers</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('reference', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($reference) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-list"></i> <span>DATA INDUK</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($buildings) ? 'class="active"':'';?>><a href="<?=site_url('reference/buildings');?>"><i class="fa fa-sign-out"></i> Gedung</a></li>
						<li <?=isset($special_needs) ? 'class="active"':'';?>><a href="<?=site_url('reference/special_needs');?>"><i class="fa fa-sign-out"></i> Kebutuhan Khusus</a></li>
						<li <?=isset($educations) ? 'class="active"':'';?>><a href="<?=site_url('reference/educations');?>"><i class="fa fa-sign-out"></i> Pendidikan</a></li>
						<li <?=isset($rooms) ? 'class="active"':'';?>><a href="<?=site_url('reference/rooms');?>"><i class="fa fa-sign-out"></i> Ruangan</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('academic', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($academic) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-address-book-o"></i> <span>AKADEMIK</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="treeview <?=isset($academic_references) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Data Induk <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($alumni) ? 'class="active"':'';?>><a href="<?=site_url('academic/alumni');?>"><i class="fa fa-sign-out"></i> Alumni</a></li>
								<?php if (in_array($this->session->userdata('school_level'), have_majors())) { ?>
									<li <?=isset($majors) ? 'class="active"':'';?>><a href="<?=site_url('academic/majors');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_major')?></a></li>
								<?php } ?>
								<li <?=isset($class_groups) ? 'class="active"':'';?>><a href="<?=site_url('academic/class_groups');?>"><i class="fa fa-sign-out"></i> Kelas</a></li>
								<li <?=isset($subjects) ? 'class="active"':'';?>><a href="<?=site_url('academic/subjects');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_subject')?></a></li>
								<li <?=isset($transportations) ? 'class="active"':'';?>><a href="<?=site_url('academic/transportations');?>"><i class="fa fa-sign-out"></i> Moda Transportasi</a></li>
								<li <?=isset($monthly_incomes) ? 'class="active"':'';?>><a href="<?=site_url('academic/monthly_incomes');?>"><i class="fa fa-sign-out"></i> Penghasilan Bulanan</a></li>
								<li <?=isset($students) ? 'class="active"':'';?>><a href="<?=site_url('academic/students');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_student')?></a></li>
								<li <?=isset($student_status) ? 'class="active"':'';?>><a href="<?=site_url('academic/student_status');?>"><i class="fa fa-sign-out"></i> Status <?=$this->session->userdata('_student')?></a></li>
								<li <?=isset($academic_years) ? 'class="active"':'';?>><a href="<?=site_url('academic/academic_years');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_academic_year')?></a></li>
								<li <?=isset($residences) ? 'class="active"':'';?>><a href="<?=site_url('academic/residences');?>"><i class="fa fa-sign-out"></i> Tempat Tinggal</a></li>
							</ul>
						</li>
						<li class="treeview <?=isset($academic_chart) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Grafik <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($by_class_groups) ? 'class="active"':'';?>><a href="<?=site_url('academic/by_class_groups');?>"><i class="fa fa-sign-out"></i> Kelas</a></li>
								<li <?=isset($by_student_status) ? 'class="active"':'';?>><a href="<?=site_url('academic/by_student_status');?>"><i class="fa fa-sign-out"></i> Status <?=$this->session->userdata('_student')?></a></li>
								<li <?=isset($by_end_date) ? 'class="active"':'';?>><a href="<?=site_url('academic/by_end_date');?>"><i class="fa fa-sign-out"></i> Tahun Lulus</a></li>
							</ul>
						</li>
						<li class="treeview <?=isset($academic_import) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Import Data <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($import_alumni) ? 'class="active"':'';?>><a href="<?=site_url('academic/import_alumni');?>"><i class="fa fa-sign-out"></i> Import Alumni</a></li>
								<li <?=isset($import_students) ? 'class="active"':'';?>><a href="<?=site_url('academic/import_students');?>"><i class="fa fa-sign-out"></i> Import <?=$this->session->userdata('_student')?></a></li>
							</ul>
						</li>
						<li class="treeview <?=isset($academic_settings) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Pengaturan <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($course_classes) ? 'class="active"':'';?>><a href="<?=site_url('academic/course_classes');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_subject')?></a></li>
								<li <?=isset($subject_teachers) ? 'class="active"':'';?>><a href="<?=site_url('academic/subject_teachers');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('school_level') >= 5 ? 'Dosen Mata Kuliah' : 'Guru Mata Pelajaran' ?></a></li>
								<li <?=isset($class_group_students) ? 'class="active"':'';?>><a href="<?=site_url('academic/class_group_students/create');?>"><i class="fa fa-sign-out"></i> Rombongan Belajar</a></li>
								<li <?=isset($class_group_settings) ? 'class="active"':'';?>><a href="<?=site_url('academic/class_group_settings');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('school_level') >= 5 ? 'Dosen Wali' : 'Wali Kelas' ?> </a></li>
							</ul>
						</li>
						<li class="treeview <?=isset($student_attendance_report) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Rekap Presensi <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($daily_attendance_report) ? 'class="active"':'';?>><a href="<?=site_url('academic/daily_attendance_report');?>"><i class="fa fa-sign-out"></i> Per Hari</a></li>
								<li <?=isset($subject_attendance_report) ? 'class="active"':'';?>><a href="<?=site_url('academic/subject_attendance_report');?>"><i class="fa fa-sign-out"></i> Per <?=$this->session->userdata('_subject')?></a></li>
								<li <?=isset($semester_attendance_report) ? 'class="active"':'';?>><a href="<?=site_url('academic/semester_attendance_report');?>"><i class="fa fa-sign-out"></i> Per Semester</a></li>
								<li <?=isset($all_attendance_report) ? 'class="active"':'';?>><a href="<?=site_url('academic/student_attendance_report');?>"><i class="fa fa-sign-out"></i> Tampilkan Semua</a></li>
							</ul>
						</li>
						<li <?=isset($student_groups) ? 'class="active"':'';?>><a href="<?=site_url('academic/student_groups');?>"><i class="fa fa-sign-out"></i> Rombongan Belajar</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('employees', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($employees) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-address-book-o"></i> <span><?=strtoupper($this->session->userdata('_employee'))?></span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($all_employees) ? 'class="active"':'';?>><a href="<?=site_url('employees/employees');?>"><i class="fa fa-sign-out"></i> Semua <?=$this->session->userdata('_employee')?></a></li>
						<li class="treeview <?=isset($employee_references) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Data Induk <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($employment_types) ? 'class="active"':'';?>><a href="<?=site_url('employees/employment_types');?>"><i class="fa fa-sign-out"></i> Jenis <?=$this->session->userdata('_employee')?></a></li>
								<li <?=isset($laboratory_skills) ? 'class="active"':'';?>><a href="<?=site_url('employees/laboratory_skills');?>"><i class="fa fa-sign-out"></i> Keahlian Laboratorium</a></li>
								<li <?=isset($institution_lifters) ? 'class="active"':'';?>><a href="<?=site_url('employees/institution_lifters');?>"><i class="fa fa-sign-out"></i> Lembaga Pengangkat</a></li>
								<li <?=isset($ranks) ? 'class="active"':'';?>><a href="<?=site_url('employees/ranks');?>"><i class="fa fa-sign-out"></i> Pangkat / Golongan</a></li>
								<li <?=isset($employments) ? 'class="active"':'';?>><a href="<?=site_url('employees/employments');?>"><i class="fa fa-sign-out"></i> Pekerjaan</a></li>
								<li <?=isset($employment_status) ? 'class="active"':'';?>><a href="<?=site_url('employees/employment_status');?>"><i class="fa fa-sign-out"></i> Status Kepegawaian</a></li>
								<li <?=isset($salary_sources) ? 'class="active"':'';?>><a href="<?=site_url('employees/salary_sources');?>"><i class="fa fa-sign-out"></i> Sumber Gaji</a></li>
							</ul>
						</li>
						<li <?=isset($import_employees) ? 'class="active"':'';?>><a href="<?=site_url('employees/import');?>"><i class="fa fa-sign-out"></i> Import <?=$this->session->userdata('_employee')?></a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('admission', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($admission) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-address-book-o"></i> <span><?=$this->session->userdata('_admission')?> <?=$this->session->userdata('admission_year')?> </span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="treeview <?=isset($admission_settings) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Pengaturan <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($form_settings) ? 'class="active"':'';?>><a href="<?=site_url('admission/form_settings');?>"><i class="fa fa-sign-out"></i> Formulir <?=$this->session->userdata('school_level') >= 5 ? 'PMB':'PPDB'?></a></li>
								<li <?=isset($admission_phases) ? 'class="active"':'';?>><a href="<?=site_url('admission/admission_phases');?>"><i class="fa fa-sign-out"></i> Gelombang Pendaftaran</a></li>
								<li <?=isset($admission_types) ? 'class="active"':'';?>><a href="<?=site_url('admission/admission_types');?>"><i class="fa fa-sign-out"></i> Jalur Pendaftaran</a></li>
								<li <?=isset($admission_quotas) ? 'class="active"':'';?>><a href="<?=site_url('admission/admission_quotas');?>"><i class="fa fa-sign-out"></i> Kuota Pendaftaran</a></li>
								<li <?=isset($subject_semester_report_settings) ? 'class="active"':'';?>><a href="<?=site_url('admission/subject_semester_report_settings');?>"><i class="fa fa-sign-out"></i> Nilai Rapor</a></li>
								<li <?=isset($subject_national_exam_settings) ? 'class="active"':'';?>><a href="<?=site_url('admission/subject_national_exam_settings');?>"><i class="fa fa-sign-out"></i> Nilai Ujian Nasional</a></li>
								<li <?=isset($admission_exam_schedules) ? 'class="active"':'';?>><a href="<?=site_url('admission/admission_exam_schedules');?>"><i class="fa fa-sign-out"></i> Ujian Tes Tulis</a></li>
								<li <?=isset($admission_general_settings) ? 'class="active"':'';?>><a href="<?=site_url('admission/general_settings');?>"><i class="fa fa-sign-out"></i> Umum</a></li>
							</ul>
						</li>
						<li <?=isset($registrants) ? 'class="active"':'';?>><a href="<?=site_url('admission/registrants');?>"><i class="fa fa-sign-out"></i> Calon <?=$this->session->userdata('_student')?> Baru</a></li>
						<li <?=isset($semester_report_scores) ? 'class="active"':'';?>><a href="<?=site_url('admission/semester_report_scores');?>"><i class="fa fa-sign-out"></i> Input Nilai Rapor</a></li>
						<li <?=isset($national_exam_scores) ? 'class="active"':'';?>><a href="<?=site_url('admission/national_exam_scores');?>"><i class="fa fa-sign-out"></i> Input Nilai Ujian Nasional</a></li>
						<li <?=isset($admission_exam_scores) ? 'class="active"':'';?>><a href="<?=site_url('admission/admission_exam_scores');?>"><i class="fa fa-sign-out"></i> Input Nilai Ujian Tes Tulis</a></li>
						<li <?=isset($selection_process) ? 'class="active"':'';?>><a href="<?=site_url('admission/selection_process');?>"><i class="fa fa-sign-out"></i> Proses Seleksi</a></li>
						<li <?=isset($registrants_approved) ? 'class="active"':'';?>><a href="<?=site_url('admission/registrants_approved');?>"><i class="fa fa-sign-out"></i> Pendaftar Diterima</a></li>
						<li <?=isset($registrants_unapproved) ? 'class="active"':'';?>><a href="<?=site_url('admission/registrants_unapproved');?>"><i class="fa fa-sign-out"></i> Pendaftar Tidak Diterima</a></li>
						<li <?=isset($verification_subject_scores) ? 'class="active"':'';?>><a href="<?=site_url('admission/verification_subject_scores');?>"><i class="fa fa-sign-out"></i> Verifikasi Nilai</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('plugins', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($plugins) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-plug"></i> <span>PLUGINS</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($banners) ? 'class="active"':'';?>><a href="<?=site_url('plugins/banners');?>"><i class="fa fa-sign-out"></i> Iklan</a></li>
						<li class="treeview <?=isset($pollings) ? 'active':'';?>">
							<a href="#"><i class="fa fa-sign-out"></i> Jajak Pendapat <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li <?=isset($questions) ? 'class="active"':'';?>><a href="<?=site_url('plugins/questions');?>"><i class="fa fa-sign-out"></i> Pertanyaan</a></li>
								<li <?=isset($answers) ? 'class="active"':'';?>><a href="<?=site_url('plugins/answers');?>"><i class="fa fa-sign-out"></i> Jawaban</a></li>
							</ul>
						</li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('media', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($media) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-upload"></i> <span>MEDIA</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($files) ? 'class="active"':'';?>><a href="<?=site_url('media/files');?>"><i class="fa fa-sign-out"></i> File</a></li>
						<li <?=isset($file_categories) ? 'class="active"':'';?>><a href="<?=site_url('media/file_categories');?>"><i class="fa fa-sign-out"></i> Kategori File</a></li>
						<li <?=isset($albums) ? 'class="active"':'';?>><a href="<?=site_url('media/albums');?>"><i class="fa fa-sign-out"></i> Album Photo</a></li>
						<li <?=isset($videos) ? 'class="active"':'';?>><a href="<?=site_url('media/videos');?>"><i class="fa fa-sign-out"></i> Video</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('appearance', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($appearance) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-paint-brush"></i> <span>TAMPILAN</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($menus) ? 'class="active"':'';?>><a href="<?=site_url('appearance/menus');?>"><i class="fa fa-sign-out"></i> Menu</a></li>
						<li <?=isset($themes) ? 'class="active"':'';?>><a href="<?=site_url('appearance/themes');?>"><i class="fa fa-sign-out"></i> Tema</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('acl', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($acl) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-users"></i> <span>PENGGUNA</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if ($this->session->userdata('user_type') == 'super_user') { ?>
							<li <?=isset($administrator) ? 'class="active"':'';?>><a href="<?=site_url('acl/administrator');?>"><i class="fa fa-sign-out"></i> Administrator</a></li>
						<?php } ?>
						<li <?=isset($user_students) ? 'class="active"':'';?>><a href="<?=site_url('acl/students');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_student')?></a></li>
						<li <?=isset($user_employees) ? 'class="active"':'';?>><a href="<?=site_url('acl/employees');?>"><i class="fa fa-sign-out"></i> <?=$this->session->userdata('_employee')?></a></li>
						<li <?=isset($modules) ? 'class="active"':'';?>><a href="<?=site_url('acl/modules');?>"><i class="fa fa-sign-out"></i> Daftar Modul</a></li>
						<li <?=isset($user_groups) ? 'class="active"':'';?>><a href="<?=site_url('acl/user_groups');?>"><i class="fa fa-sign-out"></i> Grup Pengguna</a></li>
						<li <?=isset($user_privileges) ? 'class="active"':'';?>><a href="<?=site_url('acl/user_privileges');?>"><i class="fa fa-sign-out"></i> Hak Akses</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('settings', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview <?=isset($settings) ? 'active':'';?>">
					<a href="#">
						<i class="fa fa-wrench"></i> <span>PENGATURAN</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li <?=isset($discussion_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/discussion');?>"><i class="fa fa-sign-out"></i> Diskusi</a></li>
						<li <?=isset($mail_server_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/mail_server');?>"><i class="fa fa-sign-out"></i> Email Server</a></li>
						<li <?=isset($social_account_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/social_account');?>"><i class="fa fa-sign-out"></i> Jejaring Sosial</a></li>
						<li <?=isset($media_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/media');?>"><i class="fa fa-sign-out"></i> Media</a></li>
						<li <?=isset($writing_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/writing');?>"><i class="fa fa-sign-out"></i> Menulis</a></li>
						<li <?=isset($reading_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/reading');?>"><i class="fa fa-sign-out"></i> Membaca</a></li>
						<li <?=isset($appearance_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/appearance');?>"><i class="fa fa-sign-out"></i> Pengaturan Tema</a></li>
						<li <?=isset($student_join_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/student_join');?>"><i class="fa fa-sign-out"></i> Album <?=$this->session->userdata('school_level') >= 5 ? 'PMB' : 'PPDB'?></a></li>
						<li <?=isset($school_profile_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/school_profile');?>"><i class="fa fa-sign-out"></i> Profil <?=$this->session->userdata('school_level') >= 5 ? 'Perguruan Tinggi' : 'Sekolah'?></a></li>
						<li <?=isset($general_settings) ? 'class="active"':'';?>><a href="<?=site_url('settings/general');?>"><i class="fa fa-sign-out"></i> Umum</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<?php if (in_array('maintenance', $this->session->userdata('user_privileges'))) { ?>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-medkit"></i> <span>PEMELIHARAAN</span> <i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu" style="margin-bottom: 20px">
						<li><a href="<?=site_url('maintenance/backup_apps');?>"><i class="fa fa-download"></i> Backup Apps</a></li>
						<li><a href="<?=site_url('maintenance/backup_database');?>"><i class="fa fa-database"></i> Backup Database</a></li>
					</ul>
				</li>
			<?php } ?>
		<?php } ?>

		<?php if ($this->session->userdata('user_type') === 'super_user' || $this->session->userdata('user_type') === 'administrator') { ?>
			<li class="profile-menu">
				<a href="<?=site_url('profile');?>">
					<i class="fa fa-power-off"></i> <span>UBAH PROFIL</span>
				</a>
			</li>
		<?php } ?>

		<li class="change-password-menu">
			<a href="<?=site_url('change_password');?>">
				<i class="fa fa-power-off"></i> <span>UBAH KATA SANDI</span>
			</a>
		</li>

		<li class="power-off-menu">
			<a href="<?=site_url('logout');?>">
				<i class="fa fa-power-off"></i> <span>KELUAR</span>
			</a>
		</li>
	</ul>
	<br>
</section>
