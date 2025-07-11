<!DOCTYPE html>
<html>
<head>
    <title>Admin System</title>
    <link href="<?= base_url("assets/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" rel="stylesheet">
</head>
<body style="height:100vh !important">
<?php if(!empty($this->session->userdata("username"))){?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color:#712cf9 !important">
  <div class="container">
    <a class="navbar-brand fw-bolder" href="#">Admin System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav gap-3 ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-uppercase fw-bolder active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $this->db->get_where("admin_login",['username'=>$this->session->userdata("username")])->result()[0]->username; ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="request_admin_logout">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php } ?>

<div class="container mt-4">
