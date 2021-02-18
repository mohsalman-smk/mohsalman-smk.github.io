<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


				<form role="form">
					<div class="form-content">
						<p id="login-info" <?=$can_logged_in ? '':'class="text-danger"';?>><?=$login_info;?></p>
						<div class="form-group">
							<input <?=$can_logged_in ? '' : 'disabled="disabled"';?> autofocus autocomplete="off" type="text" name="username" placeholder="Username..." class="form-control input-underline input-lg input-error" id="username">
						</div>

						<div class="form-group">
							<input <?=$can_logged_in ? '' : 'disabled="disabled"';?> type="password" name="password" placeholder="Password..."  class="form-control input-underline input-lg input-error" id="password">
						</div>
					</div>
					<button <?=$can_logged_in ? '' : 'disabled="disabled"';?> onclick="login(); return false;" class="btn btn-white btn-outline btn-lg btn-rounded progress-login" >Login</button>
					&nbsp;

							<?php if ($this->uri->segment(1) == 'lost-password') { ?>
								<a href="<?=site_url('login');?>" class="btn btn-white btn-outline btn-lg btn-rounded">Login</a><br>
							<?php } else if ($this->uri->segment(1) == 'login') { ?>
								<a href="<?=site_url('lost-password');?>" class="btn btn-white btn-outline btn-lg btn-rounded">Lupa Password?</a><br>
							<?php } ?>
				</form>