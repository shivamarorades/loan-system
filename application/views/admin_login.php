<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="row w-100">
    <div class="col-md-6 mx-auto">
      <div class="card shadow">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-4">Admin Login</h5>

          <form id="login_admin"  class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="username" class="form-label">username</label>
              <input type="text" class="form-control" id="username" name="username" required>
              <div class="invalid-feedback">Please enter a valid username.</div>
            </div>


            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="invalid-feedback">Please enter a password.</div>
            </div>
            <div class="mb-3">
            <a href="request_login">Customer Login Here</a>
</div>
            <button type="submit" id="loadingbutton" class="btn btn-primary w-100">Submit</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
