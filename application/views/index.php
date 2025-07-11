<?php 
        $check_loan=$this->db->get_where("loans",['uid'=>$this->session->userdata("uid")])->row();
        if($check_loan){
          if($check_loan->status==2){
            $check_loan_status="Pending";
          }
          else if($check_loan->status==1){
            $check_loan_status="Approved";
          }
          else if($check_loan->status==0 && $check_loan->close_status<0){
            $check_loan_status="Rejected";
          }
          else if($check_loan->status==0 && $check_loan->close_status>0){
            $check_loan_status="No Loans";
          }
        }
        else{
          $check_loan_status="No Loans Active";
        }
?>
<div class="container" style="height:75vh !important;">
    <div class="row">
        <div class="col-md-6">
            <div class="card text-white" style="background-color:#712cf9">
  <div class="card-body">
    <h2 class="card-title h4 fw-bolder">Get up to ₹ 2,00,000</h2>
    <p class="card-text">Disburse your loan in just 2 minutes in your account</p>
    <button type="button" id="applyloan"  data-bs-toggle="modal" data-bs-target="#apply_loan" class="btn btn-warning fw-bolder">Apply Loan</button>

  </div>
</div>
        </div>
        <div class="col-md-6">
            <div class="card text-white" style="background-color:#712cf9">
  <div class="card-body">
    <h2 class="card-title h4 fw-bolder">Track Your Loan Status Here</h2>
    <button type="button" id="checkstatus" data-bs-toggle="modal" data-bs-target="#check_status"  class="btn btn-warning fw-bolder">Check Status</a>

  </div>
</div>  
        </div>
     
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="apply_loan" tabindex="-1"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header d-none approve">
        <h1 class="modal-title fs-5 " >Please make repayment of current loan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-header d-none pending">
        <h1 class="modal-title fs-5 " >You are on a watchlist</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-header d-none rejected">
        <h1 class="modal-title fs-5 " >Request A Loan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body d-none rejected" >
        <!-- Error message -->
        <div id="errorBox" class="alert alert-danger d-none" role="alert"></div>

        <!-- Loan Request Form -->
        <form id="request_loan" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="loan_amount" class="form-label">Loan Amount</label>
            <input type="number" name="amount" class="form-control" id="loan_amount" required>
            <div class="invalid-feedback">Please enter the loan amount.</div>
          </div>

          <div class="mb-3">
            <label for="tenureSlider" class="form-label">
              Tenure: <span class="tenureLabel">6 Months</span>
            </label>
            <input type="hidden" class="tenureLabel" value="12"  name="tenure">
            <input type="range" class="form-range" min="0" max="3" step="1" id="tenureSlider" required>
            <div class="invalid-feedback">Please select a tenure.</div>
          </div>

          <div class="mb-3">
            <label for="reason" class="form-label">Purpose</label>
            <textarea class="form-control" name="reason" id="reason" required></textarea>
            <div class="invalid-feedback">Please provide a purpose.</div>
          </div>

          <button type="submit" class="btn btn-primary" id="loadingbutton">Submit</button>
        </form>
      </div>

      <div class="modal-body d-none approve" >
        <!-- Error message -->
        <div id="errorBox" class="alert alert-danger d-none" role="alert"></div>

        <!-- Loan Request Form -->
        <form id="repayment" class="needs-validation" novalidate>
          <div class="mb-3">
            <h5>Repayment Loan Amount : ₹ <span class="fw-bolder get_repayment_amount"></span></h5>
            <input type="hidden" name="repayment" value="" class="form-control get_repayment_amount"  required>
            <div class="invalid-feedback">Please enter the repayment loan amount.</div>
          </div>
          <button type="submit" class="btn btn-primary" id="loadingbutton2">Pay Now</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade " id="check_status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <h3 id="checkloanstatus"><?= $check_loan_status; ?></h3>
      </div>
    </div>
  </div>
</div>