 <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
 <script src="<?= base_url('views/themes/academics/js/jquery-2.2.4.min.js'); ?>" type="text/javascript"></script>
 <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
     <div class="sidebar">
         <?php if ($this->uri->segment(1) != 'sambutan-kepala-sekolah') { ?>
             <div class="thumbnail">
                 <img src="<?= base_url('media_library/images/') . $this->session->userdata('headmaster_photo'); ?>" alt="..." style="width: 100%">
                 <div class="caption">
                     <h3><?= $this->session->userdata('headmaster') ?></h3>
                     <p align="justify"><?= word_limiter(strip_tags(get_welcome()), 13); ?></p>
                     <p>
                         <a href="<?= site_url('sambutan-kepala-sekolah'); ?>" class="sidebar-search-btn-full disabled" role="button">Selengkapnya <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                     </p>
                 </div>
             </div>
         <?php } ?>
         <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-602e06e32c2aa886"></script>
        <!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="addthis_inline_share_toolbox"></div>
         <div class="sidebar-box">
             <div class="sidebar-box-inner">
                 <h3 class="sidebar-title">Pencarian</h3>
                 <div class="sidebar-find-course">
                     <form id="checkout-form" action="<?= site_url('hasil-pencarian') ?>" method="POST">
                         <div class="form-group course-name">
                             <input placeholder="Pencarian" name="keyword" class="form-control" type="text" />
                         </div>
                         <div class="form-group">
                             <button class="sidebar-search-btn-full disabled" type="submit">Pencarian</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
         <?php $query = get_post_categories(10);
            if ($query->num_rows() > 0) { ?>
             <div class="sidebar-box">
                 <div class="sidebar-box-inner">
                     <h3 class="sidebar-title">Categories</h3>
                     <ul class="sidebar-categories">
                         <?php foreach ($query->result() as $row) { ?>
                             <li><a href="<?= site_url('category/' . $row->category_slug); ?>" title="<?= $row->category_description; ?>"><?= $row->category_name; ?></a></li>
                         <?php } ?>
                     </ul>
                 </div>
             </div>
         <?php } ?>
         <div class="form-group has-feedback">
             <input onkeydown="if (event.keyCode == 13) { subscriber(); return false; }" type="text" class="form-control" id="subscriber" placeholder="Berlangganan" autocomplete="off">
             <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
         </div>
         <?php $query = get_archive_year();
            if ($query->num_rows() > 0) { ?>
             <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                 <?php $idx = 0;
                    foreach ($query->result() as $row) { ?>
                     <div class="panel panel-default">
                         <div class="panel-heading" role="tab" id="heading_<?= $row->year ?>">
                             <h4 class="panel-title">
                                 <a role="button" data-toggle="collapse" data-parent="#accordion" href="#archive_<?= $row->year ?>" aria-expanded="true" aria-controls="archive_<?= $row->year ?>">ARSIP <?= $row->year ?></a>
                             </h4>
                         </div>
                         <div id="archive_<?= $row->year ?>" class="panel-collapse collapse <?= $idx == 0 ? 'in' : '' ?>" role="tabpanel" aria-labelledby="heading_<?= $row->year ?>">
                             <div class="list-group">
                                 <?php $archives = get_archives($row->year);
                                    if ($archives->num_rows() > 0) { ?>
                                     <?php foreach ($archives->result() as $archive) { ?>
                                         <a href="<?= site_url('archives/' . $row->year . '/' . $archive->code) ?>" class="list-group-item"><?= bulan($archive->code) ?> (<?= $archive->count ?>)</a>
                                     <?php } ?>
                                 <?php } ?>
                             </div>
                         </div>
                     </div>
                 <?php $idx++;
                    } ?>
             </div>
         <?php } ?>
         <?php $query = get_links();
            if ($query->num_rows() > 0) { ?>
             <div class="sidebar-box">
                 <div class="sidebar-box-inner">
                     <h3 class="sidebar-title">Tautan</h3>
                     <ul class="product-tags">
                         <?php foreach ($query->result() as $row) { ?>
                             <li><a href="<?= $row->link_url; ?>" title="<?= $row->link_title; ?>" target="<?= $row->link_target; ?>"><?= $row->link_title; ?></a></li>
                         <?php } ?>
                     </ul>
                 </div>
             </div>
         <?php } ?>
         <?php $query = get_active_question();
            if ($query) { ?>
             <div class="panel panel-default">
                 <div class="panel-heading">
                     <h3 class="panel-title">JAJAK PENDAPAT</h3>
                 </div>
                 <div class="panel-body">
                     <p><?= $query->question ?></p>
                     <?php $options = get_answers($query->id);
                        foreach ($options->result() as $option) { ?>
                         <div class="radio">
                             <label>
                                 <input type="radio" name="answer_id" id="answer_id_<?= $option->id ?>" value="<?= $option->id ?>">
                                 <?= $option->answer ?>
                             </label>
                         </div>
                     <?php } ?>
                     <div class="btn-group">
                         <button type="submit" onclick="polling(); return false;" class="btn btn-success btn-sm"><i class="fa fa-send"></i> SUBMIT</button>
                         <a href="<?= site_url('hasil-jajak-pendapat') ?>" class="btn btn-sm btn-warning"><i class="fa fa-bar-chart"></i> LIHAT HASIL</a>
                     </div>
                 </div>
             </div>
         <?php } ?>
     </div>
 </div>