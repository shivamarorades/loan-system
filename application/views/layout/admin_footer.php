</div> <!-- end container -->



<script src="<?= base_url("assets/js/bootstrap.bundle.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.min.js"); ?>"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script>
    
  






// ======================== ADMIN LOGIN ========================
const login_admin = document.querySelector("#login_admin");
if (login_admin) {
  login_admin.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!login_admin.checkValidity()) {
      login_admin.classList.add('was-validated');
      return;
    }

    $.ajax({
      url: "post_admin_login",
      method: "post",
      dataType: "json",
      data: $("#login_admin").serialize(),
      beforeSend: () => {
        $("#loadingbutton").prop("disabled", true).text("Please wait...");
      },
      complete: () => {
        $("#loadingbutton").prop("disabled", false).text("Submit");
      },
      success: (response) => {
        switch (response.status) {
          case 1:
          case -2:
            location.href = "admin";
            break;
          case -1:
            alert("Incorrect Password");
            login_admin.reset();
            login_admin.classList.remove('was-validated');
            break;
          case -3:
            alert("Incorrect Username");
            login_admin.reset();
            login_admin.classList.remove('was-validated');
            break;
        }
      },
      error: (xhr) => {
        let msg = "Something went wrong. Please try again.";
        if (xhr.responseJSON?.message) {
          msg = xhr.responseJSON.message;
        }
        $('#errorBox').text(msg).show();
      }
    });
  });
}


$(document).ready(function () {
  const table = $('#myTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "post_admin",
      type: "POST",
      data: (d) => {
        d.status_filter = $('#statusFilter').val();
      }
    },
    columns: [
      { data: "name" },
      { data: "phone" },
      { data: "email" },
      { data: "amount" },
      { data: "repayment" },
      { data: "close_status" },
      {
        data: "status",
        render: (status) => {
          const color = status === 'Approved' ? 'success' :
                        status === 'Pending' ? 'warning' :
                        status === 'Closed' ? 'secondary' : 'danger';
          return `<span class="badge bg-${color}">${status}</span>`;
        }
      },
      {
        data: null,
        render: (data) => {
          return data.status === 'Pending'
            ? `<button class="btn btn-sm btn-success btn-approve" data-uid="${data.uid}">Approve</button>`
            : '-';
        },
        orderable: false
      },
      {
        data: null,
        render: (data) => {
          return data.status === 'Pending'
            ? `<button class="btn btn-sm btn-danger btn-reject" data-uid="${data.uid}">Reject</button>`
            : '-';
        },
        orderable: false
      }
    ]
  });

  $('#statusFilter').on('change', () => {
    table.ajax.reload();
  });

  $('#myTable').on('click', '.btn-approve', function () {
    const uid = $(this).data('uid');
    $.post("update_loan_status", { uid, action: 'approve' }, function (res) {
      alert(res.message);
      table.ajax.reload(null, false);
    }, 'json');
  });

  $('#myTable').on('click', '.btn-reject', function () {
    const uid = $(this).data('uid');
    $.post("update_loan_status", { uid, action: 'reject' }, function (res) {
      alert(res.message);
      table.ajax.reload(null, false);
    }, 'json');
  });
});










</script>
</body>
</html>
