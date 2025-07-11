<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="row w-100">
    <div class="col-md-6 mx-auto">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-4">Customer Registration</h5>

          <form id="register_user"  class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="fullname" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullname" name="full_name" required>
              <div class="invalid-feedback">Please enter your full name.</div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
              <div class="invalid-feedback">Please enter a valid email.</div>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="tel" class="form-control" id="phone" name="phone" required>
              <div class="invalid-feedback">Please enter your phone number.</div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="invalid-feedback">Please enter a password.</div>
            </div>
<div class="mb-3">
            <a href="request_login">Login Here</a>
</div>
            <button type="submit" id="loadingbutton" class="btn btn-primary w-100">Submit</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
