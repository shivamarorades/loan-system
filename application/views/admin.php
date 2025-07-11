<div class="container">
    <div class="row">
        <div class="mb-3">
  <label for="statusFilter">Filter by Status:</label>
  <select id="statusFilter" class="form-control" style="width: 200px; display: inline-block;">
    <option value="">All</option>
    <option value="Approved">Approved</option>
    <option value="Rejected">Rejected</option>
    <option value="Pending">Pending</option>
    <option value="Closed">Closed</option>
  </select>
</div>
        <div class="col-12">
            <table id="myTable" class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Loan Amount</th>
                        <th>Loan Repayment Amt</th>
                        <th>Close Loan Status</th>
                        <th>Status</th>
                        <th>Approve</th>
                        <th>Reject</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
