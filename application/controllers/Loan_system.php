<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_system extends CI_Controller {

    // ======================== GENERAL ========================
    public function index() {
    try {
        if ($this->session->userdata("uid")) {
            // Optional: Log access
            log_message('info', 'User ID ' . $this->session->userdata("uid") . ' accessed index.');

            $this->load->view('layout/header');
            $this->load->view('index');
            $this->load->view('layout/footer');
        } else {
            log_message('error', 'Unauthorized access attempt to index page.');
            redirect("request_login");
        }
    } catch (Exception $e) {
        log_message('error', 'Exception in index(): ' . $e->getMessage());
        show_error('Something went wrong. Please try again later.', 500);
    }
}


    public function request_logout() {
    $this->session->set_flashdata('logout_msg', 'You have been logged out successfully.');
    $this->session->sess_destroy();
    redirect("request_login");
}


    // ======================== LOAN ========================
    public function post_loan() {
    header('Content-Type: application/json');

    if (!$this->session->userdata("uid")) {
        echo json_encode(['status' => -2, 'message' => 'Unauthorized']);
        return;
    }

    // Validate inputs
    $amount = $this->input->post("amount");
    $reason = trim($this->input->post("reason"));
    $tenure = $this->input->post("tenure");

    if (!is_numeric($amount) || $amount <= 0 || empty($reason) || !is_numeric($tenure)) {
        echo json_encode(['status' => 0, 'message' => 'Invalid input']);
        return;
    }

    $uid = $this->session->userdata("uid");

    // Optional: wrap in DB transaction
    $this->db->trans_start();

    // Delete previous loans (make sure this is really what you want!)
    $this->db->delete('loans', ['uid' => $uid]);

    // Insert new loan
    $insert = $this->db->insert("loans", [
        'amount' => $amount,
        'reason' => $reason,
        'tenure' => $tenure,
        'uid'    => $uid,
        'status' => 2
    ]);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE || !$insert) {
        log_message('error', 'Loan insertion failed for UID: ' . $uid);
        echo json_encode(['status' => 0, 'message' => 'Loan request failed']);
    } else {
        echo json_encode(['status' => 1, 'message' => 'Loan request submitted']);
    }
}


    public function refresh_modal1() {
    $this->check_loan_status(true);
}

public function refresh_modal2() {
    $this->check_loan_status(false);
}

private function check_loan_status($include_amount = false) {
    header('Content-Type: application/json');

    if (!$this->session->userdata("uid")) {
        echo json_encode(['status' => -2, 'message' => 'Unauthorized']);
        return;
    }

    $uid = $this->session->userdata("uid");

    try {
        $loan = $this->db->get_where("loans", ['uid' => $uid])->row();

        if ($loan) {
            switch ($loan->status) {
                case 1:
                    $response = ['status' => 'approve'];
                    if ($include_amount) $response['amount'] = $loan->amount;
                    break;

                case 2:
                    $response = ['status' => 'pending'];
                    break;

                case 0:
                    if (empty($loan->close_status)) {
                        $response = ['status' => 'rejected'];
                    } else {
                        $response = ['status' => 'no_loan'];
                    }
                    break;

                default:
                    $response = ['status' => 'no_loan'];
            }
        } else {
            $response = ['status' => 'no_loan'];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        log_message('error', 'Error in check_loan_status for UID: ' . $uid . ' - ' . $e->getMessage());
        echo json_encode(['status' => 0, 'message' => 'Server error']);
    }
}


    public function repayment() {
    header('Content-Type: application/json');

    if (!$this->session->userdata("uid")) {
        echo json_encode(['status' => -2, 'message' => 'Unauthorized']);
        return;
    }

    $uid = $this->session->userdata("uid");
    $repayment = $this->input->post("repayment");

    if (!is_numeric($repayment) || $repayment <= 0) {
        echo json_encode(['status' => 0, 'message' => 'Invalid repayment amount']);
        return;
    }

    try {
        $loan = $this->db->get_where("loans", ['uid' => $uid])->row();

        if (!$loan) {
            echo json_encode(['status' => 'no_loan', 'message' => 'No loan found']);
            return;
        }

        if ($loan->status == 1) {
            $this->db->trans_start();

            $update = $this->db->update('loans', [
                'status'       => 0,
                'close_status' => 1,
                'repayment'    => $repayment
            ], ['uid' => $uid]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE || !$update) {
                log_message('error', 'Loan repayment update failed for UID: ' . $uid);
                echo json_encode(['status' => 0, 'message' => 'Repayment failed. Please try again.']);
                return;
            }

            echo json_encode(['status' => 'complete', 'message' => 'Loan repaid successfully']);
        } elseif ($loan->status == 2) {
            echo json_encode(['status' => 'pending', 'message' => 'Loan is still under review']);
        } else {
            echo json_encode(['status' => 'rejected', 'message' => 'Loan has already been rejected or repaid']);
        }
    } catch (Exception $e) {
        log_message('error', 'Repayment exception for UID: ' . $uid . ' - ' . $e->getMessage());
        echo json_encode(['status' => 0, 'message' => 'Server error occurred']);
    }
}


    // ======================== USER AUTH ========================
    public function request_login() {
    if ($this->session->userdata("uid")) {
        redirect("index");
    } else {
        $this->load_user_view('request_login');
    }
}



    public function post_login() {
    header('Content-Type: application/json');

    if ($this->session->userdata("uid")) {
        echo json_encode(['status' => -2, 'message' => 'Already logged in']);
        return;
    }

    $email = trim($this->input->post("email"));
    $password = $this->input->post("password");

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 0, 'message' => 'Email and password are required']);
        return;
    }

    $user = $this->db->get_where('user', [
        'email' => $email,
        'status' => 1
    ], 1)->row();

    if ($user) {
        if (password_verify($password, $user->password)) {
            $this->session->set_userdata('uid', $user->uid);

            log_message('info', 'User login success: ' . $email);
            echo json_encode(['status' => 1, 'message' => 'Login successful']);
        } else {
            log_message('error', 'Incorrect password attempt for: ' . $email);
            echo json_encode(['status' => -1, 'message' => 'Incorrect password']);
        }
    } else {
        log_message('error', 'Login failed: user not found or inactive - ' . $email);
        echo json_encode(['status' => -3, 'message' => 'User not found or inactive']);
    }
}


    public function request_signup() {
    if ($this->session->userdata("uid")) {
        redirect("index");
    } else {
        $this->load_user_view('request_signup');
    }
}


    public function post_signup() {
    header('Content-Type: application/json');

    if ($this->session->userdata("uid")) {
        echo json_encode(['status' => -2, 'message' => 'Already logged in']);
        return;
    }

    $email     = trim($this->input->post("email"));
    $password  = $this->input->post("password");
    $full_name = trim($this->input->post("full_name"));
    $phone     = trim($this->input->post("phone"));

    // Basic input validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password) || empty($full_name) || empty($phone)) {
        echo json_encode(['status' => 0, 'message' => 'Invalid or missing input']);
        return;
    }

    $hashed_pw = password_hash($password, PASSWORD_BCRYPT);

    // Check for active user
    $this->db->where(['email' => $email, 'status' => 1]);
    if ($this->db->get('user', 1)->num_rows() > 0) {
        echo json_encode(['status' => -1, 'message' => 'Email already registered']);
        return;
    }

    // Check for inactive user
    $this->db->where(['email' => $email, 'status' => 0]);
    $existing = $this->db->get('user', 1);

    $otp = random_int(100000, 999999); // Secure 6-digit OTP

    if ($existing->num_rows() > 0) {
        // Update existing inactive user
        $this->db->update('user', [
            "name"     => $full_name,
            "email"    => $email,
            "phone"    => $phone,
            "password" => $hashed_pw,
            "otp"      => $otp,
            "status"   => 0
        ], ['email' => $email]);

        log_message('info', 'Signup updated for inactive email: ' . $email);

        echo json_encode(['status' => 1, 'email' => $email, 'message' => 'Signup updated, OTP sent']);
        return;
    }

    // Create unique UID (more secure than base64)
    $max_id = $this->db->query("SELECT MAX(id) AS max_id FROM user")->row()->max_id;
        $uid = uniqid(urlencode(base64_encode($email))) . $max_id;

    // Insert new user
    $this->db->trans_start();

    $insert = $this->db->insert("user", [
        "name"     => $full_name,
        "email"    => $email,
        "phone"    => $phone,
        "password" => $hashed_pw,
        "otp"      => $otp,
        "status"   => 0,
        "uid"      => $uid
    ]);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE || !$insert) {
        log_message('error', 'Signup insert failed for email: ' . $email);
        echo json_encode(['status' => 0, 'message' => 'Signup failed. Please try again.']);
    } else {
        log_message('info', 'New signup created: ' . $email);
        echo json_encode(['status' => 1, 'email' => $email, 'message' => 'Signup successful, OTP sent']);
    }
}

    public function request_otp() {
    if ($this->session->userdata("uid")) {
        log_message('info', 'OTP page accessed while logged in — redirecting to index.');
        redirect("index");
        return;
    }

    $data = [];

    // Optional: check if an email was passed from post_signup or post_login
    if ($this->session->flashdata('email')) {
        $data['email'] = $this->session->flashdata('email');
    }

    if ($this->session->flashdata('otp_msg')) {
        $data['otp_msg'] = $this->session->flashdata('otp_msg');
    }

    $this->load_user_view('request_otp', $data);
}


    public function post_otp() {
    header('Content-Type: application/json');

    if ($this->session->userdata("uid")) {
        echo json_encode(['status' => -2, 'message' => 'Already logged in']);
        return;
    }

    $encoded = $this->input->post('cgid');
    $otp     = trim($this->input->post("otp"));

    // Decode email (still insecure, better to pass plain email or token in production)
    $email = urldecode(base64_decode(urldecode(base64_decode($encoded))));

    // Validate OTP
    if (!is_numeric($otp) || strlen($otp) !== 6) {
        echo json_encode(['status' => 0, 'message' => 'Invalid OTP']);
        return;
    }

    $user = $this->db->get_where("user", ['email' => $email], 1)->row();

    if (!$user) {
        echo json_encode(['status' => -3, 'message' => 'User not found']);
        return;
    }

    if ($user->status == 1) {
        echo json_encode(['status' => -4, 'message' => 'User already verified']);
        return;
    }

    if ($user->otp === $otp) {
        $update = $this->db->update("user", ['status' => 1], ['email' => $email]);

        if ($update) {
            echo json_encode(['status' => 1, 'message' => 'OTP verified. Account activated.']);
        } else {
            log_message('error', 'Failed to update user status for email: ' . $email);
            echo json_encode(['status' => 0, 'message' => 'Activation failed. Try again.']);
        }
    } else {
        echo json_encode(['status' => -1, 'message' => 'Incorrect OTP']);
    }
}


    private function load_user_view($view) {
        $this->load->view('layout/header');
        $this->load->view($view);
        $this->load->view('layout/footer');
    }

    // ======================== ADMIN ========================
    public function admin_login() {
    if ($this->session->userdata("username")) {
        log_message('info', 'Admin already logged in, redirecting to dashboard');
        redirect("admin");
        return;
    }

    $data = [];

    if ($this->session->flashdata('admin_msg')) {
        $data['admin_msg'] = $this->session->flashdata('admin_msg');
    }

    $this->load_admin_view('admin_login', $data);
}


    public function post_admin_login() {
    header('Content-Type: application/json');

    if ($this->session->userdata("username")) {
        echo json_encode(['status' => -2, 'message' => 'Already logged in']);
        return;
    }

    $username = trim($this->input->post("username"));
    $password = $this->input->post("password");

    // Basic validation
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 0, 'message' => 'Username and password are required']);
        return;
    }

    // Fetch admin
    $admin = $this->db->get_where('admin_login', ['username' => $username], 1)->row();

    if ($admin) {
        if (password_verify($password, $admin->password)) {
            $this->session->set_userdata('username', $admin->username);
            log_message('info', 'Admin login success: ' . $username);
            echo json_encode(['status' => 1, 'message' => 'Login successful']);
        } else {
            log_message('error', 'Incorrect admin password attempt: ' . $username);
            echo json_encode(['status' => -1, 'message' => 'Incorrect password']);
        }
    } else {
        log_message('error', 'Admin login failed — user not found: ' . $username);
        echo json_encode(['status' => -3, 'message' => 'Admin not found']);
    }
}


    public function request_admin_logout() {
    if ($this->session->userdata('username')) {
        log_message('info', 'Admin logged out: ' . $this->session->userdata('username'));
    }

    $this->session->unset_userdata('username');  // Remove only admin session
    $this->session->set_flashdata('admin_msg', 'You have been logged out successfully.');

    redirect('admin_login');
}


    public function admin() {
    if ($this->session->userdata("username")) {
        log_message('info', 'Admin dashboard accessed by: ' . $this->session->userdata('username'));
        $this->load_admin_view('admin');
    } else {
        redirect("admin_login");
    }
}


    private function load_admin_view($view) {
        $this->load->view('layout/admin_header');
        $this->load->view($view);
        $this->load->view('layout/admin_footer');
    }

   public function post_admin()
{
    header('Content-Type: application/json');

    if (!$this->session->userdata("username")) {
        echo json_encode(['status' => -2, 'message' => 'Unauthorized']);
        return;
    }

    $request = $_POST;
    $start = $request['start'];
    $length = $request['length'];
    $draw = $request['draw'];
    $searchValue = $request['search']['value'] ?? '';
    $statusFilter = $request['status_filter'] ?? '';

    // Dropdown value to DB mapping
    $statusMap = [
        'Approved' => 1,
        'Rejected' => 0,
        'Pending'  => 2,
        'Closed'   => 'closed' // special handling for closed
    ];
    $statusValue = $statusMap[$statusFilter] ?? null;

    // Count total users
    $this->db->from('user');
    $this->db->where('status', 1);
    $totalRecords = $this->db->count_all_results();

    // ----------------------------
    // 1. Filtered Count Query
    // ----------------------------
    $this->db->select('user.uid');
    $this->db->from('user');
    $this->db->join('loans', 'loans.uid = user.uid', 'inner');
    $this->db->where('user.status', 1);

    if ($statusValue === 'closed') {
        $this->db->where('loans.status', 0);
        $this->db->where('loans.close_status', 1);
    } elseif (is_numeric($statusValue)) {
        $this->db->where('loans.status', $statusValue);
        if ($statusValue == 0) {
            // Exclude Closed from Rejected
            $this->db->group_start();
            $this->db->where('loans.close_status !=', 1);
            $this->db->or_where('loans.close_status IS NULL', null, false);
            $this->db->group_end();
        }
    }

    if (!empty($searchValue)) {
        $this->db->group_start();
        $this->db->like('user.name', $searchValue);
        $this->db->or_like('user.phone', $searchValue);
        $this->db->or_like('user.email', $searchValue);
        $this->db->group_end();
    }

    $filteredCount = $this->db->count_all_results();

    // ----------------------------
    // 2. Data Fetch Query
    // ----------------------------
    $this->db->select('user.uid, user.name, user.phone, user.email, loans.amount, loans.repayment, loans.close_status, loans.status');
    $this->db->from('user');
    $this->db->join('loans', 'loans.uid = user.uid', 'inner');
    $this->db->where('user.status', 1);

    if ($statusValue === 'closed') {
        $this->db->where('loans.status', 0);
        $this->db->where('loans.close_status', 1);
    } elseif (is_numeric($statusValue)) {
        $this->db->where('loans.status', $statusValue);
        if ($statusValue == 0) {
            $this->db->group_start();
            $this->db->where('loans.close_status !=', 1);
            $this->db->or_where('loans.close_status IS NULL', null, false);
            $this->db->group_end();
        }
    }

    if (!empty($searchValue)) {
        $this->db->group_start();
        $this->db->like('user.name', $searchValue);
        $this->db->or_like('user.phone', $searchValue);
        $this->db->or_like('user.email', $searchValue);
        $this->db->group_end();
    }

    $this->db->limit($length, $start);
    $results = $this->db->get()->result();

    // ----------------------------
    // 3. Format Data
    // ----------------------------
    $data = array_map(function ($row) {
        if ($row->status == 0 && $row->close_status == 1) {
            $statusText = 'Closed';
        } elseif ($row->status == 1) {
            $statusText = 'Approved';
        } elseif ($row->status == 0 && empty($row->close_status)) {
            $statusText = 'Rejected';
        } else {
            $statusText = 'Pending';
        }

        return [
            "uid"          => $row->uid,
            "name"         => $row->name,
            "phone"        => $row->phone,
            "email"        => $row->email,
            "amount"       => $row->amount ?? 'N/A',
            "repayment"    => $row->repayment ?? 'N/A',
            "close_status" => $row->close_status ?? 'N/A',
            "status"       => $statusText
        ];
    }, $results);

    // ----------------------------
    // 4. JSON Response
    // ----------------------------
    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredCount,
        "data" => $data
    ]);
}



    public function update_loan_status() {
    header('Content-Type: application/json');

    // ✅ Admin authentication
    if (!$this->session->userdata("username")) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    $uid    = $this->input->post('uid');
    $action = $this->input->post('action');

    if (empty($uid) || !in_array($action, ['approve', 'reject'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        return;
    }

    // ✅ Map action to status
    $status = ($action === 'approve') ? 1 : 0;

    // ✅ Check if loan exists & is not closed
    $loan = $this->db->get_where('loans', ['uid' => $uid])->row();

    if (!$loan) {
        echo json_encode(['status' => 'error', 'message' => 'Loan not found']);
        return;
    }

    if ($loan->status == 0 && $loan->close_status == 1) {
        echo json_encode(['status' => 'error', 'message' => 'Loan already closed']);
        return;
    }

    // ✅ Update loan status
    $this->db->where('uid', $uid);
    $updated = $this->db->update('loans', ['status' => $status]);

    if ($updated) {
        log_message('info', "Loan status for UID {$uid} updated to {$status} by admin: " . $this->session->userdata('username'));
    }

    echo json_encode([
        'status'  => $updated ? 'success' : 'error',
        'message' => $updated ? "Loan has been {$action}d" : 'Failed to update loan status'
    ]);
}

}
