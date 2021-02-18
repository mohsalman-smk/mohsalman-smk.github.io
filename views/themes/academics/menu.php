<?php defined('BASEPATH') or exit('No direct script access allowed'); ?><header>
    <div id="header2" class="header2-area">
        <div class="header-top-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="header-top-left">
                            <ul>
                                <li><i class="fa fa-phone" aria-hidden="true"></i><a href="Tel:<?= $this->session->userdata('phone') ?>"> <?= $this->session->userdata('phone') ?></a></li>
                                <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:<?= $this->session->userdata('email') ?>"><?= $this->session->userdata('email') ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="header-top-right">
                            <ul>
                                <li>
                                    <a class="login-btn-area" href="#" id="login-button"><i class="fa fa-lock" aria-hidden="true"></i> Login</a>
                                    <div class="login-form" id="login-form" style="display: none;">
                                        <div class="title-default-left-bold">Login</div>
                                        <form>
                                            <label>Username or email address *</label>
                                            <input type="text" placeholder="Name or E-mail" />
                                            <label>Password *</label>
                                            <input type="password" placeholder="Password" />
                                            <label class="check">Lost your password?</label>
                                            <span><input type="checkbox" name="remember" />Remember Me</span>
                                            <button class="default-big-btn" type="submit" value="Login">Login</button>
                                            <button class="default-big-btn form-cancel" type="submit" value="">Cancel</button>
                                        </form>
                                    </div>
                                </li>
                                <li>
                                    <div class="apply-btn-area">
                                        <a href="#" class="apply-now-btn">Apply Now</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-menu-area bg-textPrimary" id="sticker">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <div class="logo-area">
                            <a href="<?= base_url() ?>"><img class="img-responsive" src="<?= base_url('media_library/images/') . $this->session->userdata('logo_primary'); ?>" alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <nav id="desktop-nav">
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
                                <input type="text" class="search-form" placeholder="Pencarian" name="keyword" required="">
                                <a href="#" class="search-button" id="search-button"><i class="fa fa-search" aria-hidden="true"></i></a>
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
                    <div>
                        <nav id="dropdown"><a href='<?= base_url() ?>' class='logo-mobile-menu'><img src="<?= base_url('media_library/images/') . $this->session->userdata('logo_primary'); ?>" /></a>
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
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End -->
</header>
<!-- Header Area End Here -->