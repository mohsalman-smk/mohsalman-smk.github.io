<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
				<form role="form">
					<div class="form-content">
						<div class="form-group">
							<input autofocus type="password" name="password" placeholder="New Password" class="form-control input-underline input-lg input-error" id="password">
						</div>
						<div class="form-group">
							<input type="password" name="password" placeholder="Re-Type New Password" class="form-control input-underline input-lg input-error" id="c_password">
						</div>
					</div>
					<input type="hidden" id="activation_key" name="activation_key" value="<?=$this->uri->segment(2)?>">
					<button onclick="reset_password(); return false;" class="btn btn-white btn-outline btn-lg btn-rounded progress-login"><i class="fa fa-sign-out"></i> Simpan</button>
				</form>