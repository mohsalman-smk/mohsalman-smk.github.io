<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
				<form role="form" method="post">
					<div class="form-content"><p>Masukan E-Mail yang terdaftar untuk mereset Password!</p>
						<div class="form-group">
							<input type="email" name="email" placeholder="Email..." class="form-control input-underline input-lg" id="email">
						</div>
					</div>
					<button class="btn btn-white btn-outline btn-lg btn-rounded progress-login" onclick="lost_password(); return false;"><i class="fa fa-send"></i> Kirim Link</button>&nbsp;
					<a href="login" class="btn btn-white btn-outline btn-lg btn-rounded"><i class="fa fa-sign-out"></i> Login Page</a>
				</form>