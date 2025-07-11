<?php 
$original_id=isset($_GET['cgid']) ? urldecode(base64_decode(urldecode(base64_decode($this->input->get('cgid'))))) : '';
$get_otp=$this->db->get_where("user",['email'=>$original_id],1)->row();
?>
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="row w-100">
    <div class="col-md-6 mx-auto">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-4">Customer Verification</h5>

          <form id="request_otp"  class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="otp" class="form-label">Enter OTP</label>
              <input type="number" value="<?= $get_otp->otp; ?>" class="form-control" id="otp" name="otp" required>
              <input type="hidden" value="<?= $this->input->get('cgid'); ?>>" class="form-control" id="cgid" name="cgid" required>
              <div class="invalid-feedback">Please enter a valid OTP.</div>
            </div>
            <button type="submit" id="loadingbutton" class="btn btn-primary w-100">Verify</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
