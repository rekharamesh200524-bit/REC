<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
    	$this->load->database();
		$this->load->library('form_validation');
		$this->load->library('pagination');
		// $this->load->library('pdf');
		$check_session = $this->session->userdata('logged_in');
		$this->load->helper('cookie');
		$this->load->helper('string');
		$this->load->library('encrypt');
		$this->load->library('user_agent');
		date_default_timezone_set("Asia/Kolkata");
	 	$this->load->model('admin/admin_model');



	 	// echo "<pre>"; print_r($check_session); exit;
		$roleId = (!empty($check_session) && isset($check_session['EmpRoleId'])) ? $check_session['EmpRoleId'] : null;
		$menus = !empty($roleId) ? $this->admin_model->getMenusByRole($roleId) : [];
	    //  echo "<pre>currentUrl"; print_r($data['breadcrumb']); exit;
		$menuTree = [];

			foreach ($menus as $menu) {
			    if ($menu['ParentId'] === NULL) {
			        $menuTree[$menu['IHMid']] = $menu;
			        $menuTree[$menu['IHMid']]['children'] = [];
			    }
			}

			foreach ($menus as $menu) {
			    if ($menu['ParentId'] !== NULL && isset($menuTree[$menu['ParentId']])) {
			        $menuTree[$menu['ParentId']]['children'][] = $menu;
			    }
			}
		$this->load->vars('menuTree', $menuTree);


	}


	public function index()
	{
 		
		$this->template->set_master_template('../../themes/'.$this->config->item("active_template").'/landing_template_login.php');
		$this->template->write_view('content', 'admin/index');
		$this->template->render();
	}

	public function ForgotPassword()
	{
		$inps = $this->input->post();
		if (!empty($inps)) {
			$email = trim($inps['EmailInput']);
			
			
			$user = $this->db->select('IUid, EmpName, EmpEmail')
							 ->where('EmpEmail', $email)
							 ->where('UStatus', 1)
							 ->get('IHUsers')
							 ->row();

			if (empty($user)) {
				$this->session->set_flashdata('error', 'Email address not found.');
				redirect($this->config->item('base_url').'admin/ForgotPassword');
			} else {
				
				$token = bin2hex(random_bytes(32));

				
				$this->db->where('IUid', $user->IUid)
						 ->update('IHUsers', [
							 'ResetToken'          => $token,
							 'ResetTokenCreatedAt' => date('Y-m-d H:i:s')
						 ]);

				
				$objs = new InetMailer();
				$mail = $objs->load();
				$mail->setFrom('inet@inetcsc.com', 'I-NET Secure Labs');
				$mail->addAddress(trim($email));
// 				$mail->isHTML(true);
// 				$mail->Subject = 'Reset Your Password';
				
// 				$resetLink = base_url('admin/reset-password/' . $token);
// 				$mail->Body = "Hello " . htmlspecialchars($user->EmpName) . ",<br><br>" .
// 							  "We received a request to reset the password for your account.<br><br>" .
// 							  "To create a new password, please click the link below:<br><br>" .
// 							  "<a href=\"" . $resetLink . "\">" . $resetLink . "</a><br><br>" .
// 							  "For security reasons, this password reset link is valid for a limited time.<br><br>";
// 							  "If you did not raise this password reset request, please contact the Support Team immediately to secure your account.<br><br> 
// If you require any assistance, please reach out to the Support Team.";
					$mail->isHTML(true);
					$mail->Subject = "Password Reset Request - Recruitment";
					$resetLink = $this->config->item('base_url').'admin/ResetPassword/' . $token;
					$mail->Body = '
					<!DOCTYPE html>
					<html>
					<head>
					<meta charset="UTF-8">
					<style>
					    body{
					        margin:0;
					        padding:0;
					        background:#f4f6f9;
					        font-family:Arial, Helvetica, sans-serif;
					    }
					    .container{
					        max-width:600px;
					        margin:30px auto;
					        background:#ffffff;
					        border-radius:8px;
					        overflow:hidden;
					        box-shadow:0 2px 8px rgba(0,0,0,.08);
					    }
					    .header{
					        background:#0d6efd;
					        color:#ffffff;
					        padding:20px;
					        text-align:center;
					        font-size:24px;
					        font-weight:bold;
					    }
					    .content{
					        padding:30px;
					        color:#333333;
					        font-size:15px;
					        line-height:1.7;
					    }
					    .button{
					        display:inline-block;
					        background:#0d6efd;
					        color:#ffffff !important;
					        text-decoration:none;
					        padding:12px 28px;
					        border-radius:5px;
					        font-weight:bold;
					        margin:20px 0;
					    }
					    .note{
					        background:#fff8e5;
					        border-left:4px solid #ffc107;
					        padding:15px;
					        margin-top:20px;
					        color:#555;
					    }
					    .footer{
					        background:#f8f9fa;
					        padding:15px;
					        text-align:center;
					        font-size:13px;
					        color:#777;
					    }
					</style>
					</head>

					<body>

					<div class="container">

					    <div class="header">
					        Password Reset Request
					    </div>

					    <div class="content">

					        <p>Hello <strong>'.htmlspecialchars($user->EmpName).'</strong>,</p>

					        <p>
					            We received a request to reset the password for your account.
					        </p>

					        <p>
					            Click the button below to create a new password:
					        </p>

					        <p style="text-align:center;">
					            <a href="' . $resetLink . '" class="button" target="_blank">
					                Reset Password
					            </a>
					        </p>

					        <p>
					            If the button above does not work, copy and paste the following link into your browser:
					        </p>

					        <p>
					            <a href="'.$resetLink.'">'.$resetLink.'</a>
					        </p>

					        <div class="note">
					            <strong>Security Notice</strong><br><br>
					            This password reset link is valid for a limited time.<br><br>

					            If you did not raise this password reset request, please contact the Support Team immediately to secure your account.<br><br>

					            If you require any assistance, please reach out to the Support Team.
					        </div>

					        <p>
					            Thank you,<br>
					            <strong>REC Support Team</strong>
					        </p>

					    </div>

					    <div class="footer">
					        � '.date('Y').' REC. All Rights Reserved.
					    </div>

					</div>

					</body>
					</html>';

				if ($mail->send()) {
					$this->session->set_flashdata('true', 'A password reset link has been sent to your email address.');
				} else {
					$this->session->set_flashdata('error', 'Failed to send reset link email: ' . $mail->ErrorInfo);
				}
				redirect($this->config->item('base_url').'admin/ForgotPassword');
			}
		} else {
			$this->template->set_master_template('../../themes/'.$this->config->item("active_template").'/landing_template_login.php');
			$this->template->write_view('content', 'admin/ForgotPassword');
			$this->template->render();
		}
	}

	public function ResetPassword($token)
	{
		
		$user = $this->db->select('IUid, ResetTokenCreatedAt')
						 ->where('ResetToken', $token)
						 ->get('IHUsers')
						 ->row();

		if (empty($user)) {
			$this->session->set_flashdata('error', 'Invalid or expired reset token.');
			redirect($this->config->item('base_url')."admin/index");
		}

		
		$expiry = strtotime($user->ResetTokenCreatedAt . ' +1 hour');
		if (time() > $expiry) {
			
			$this->db->where('IUid', $user->IUid)
					 ->update('IHUsers', [
						 'ResetToken'          => NULL,
						 'ResetTokenCreatedAt' => NULL
					 ]);
			$this->session->set_flashdata('error', 'Invalid or expired reset token.');
			redirect($this->config->item('base_url')."admin/index");
		}

		$inps = $this->input->post();
		if (!empty($inps)) {
			$newPassword = $inps['NewPassword'];
			$confirmPassword = $inps['ConfirmPassword'];

			
			if (empty($newPassword) || empty($confirmPassword)) {
				$this->session->set_flashdata('error', 'All fields are required.');
				redirect($this->config->item('base_url').'admin/ResetPassword/' . $token);
			}

			if (strlen($newPassword) < 6) {
				$this->session->set_flashdata('error', 'Password must be at least 6 characters long.');
				redirect($this->config->item('base_url').'admin/ResetPassword/' . $token);
			}

			if ($newPassword !== $confirmPassword) {
				$this->session->set_flashdata('error', 'Passwords do not match.');
				redirect($this->config->item('base_url').'admin/ResetPassword/' . $token);
			}

			
			$this->db->where('IUid', $user->IUid)
					 ->update('IHUsers', [
						 'EmpPass'             => md5($newPassword),
						 'ResetToken'          => NULL,
						 'ResetTokenCreatedAt' => NULL,
						 'LastPasswordResetAt' => date('Y-m-d H:i:s'),
						 'LastResetTokenUsedAt'=> date('Y-m-d H:i:s')
					 ]);

			$this->session->set_flashdata('true', 'Password updated successfully. Please login with your new password.');
			redirect($this->config->item('base_url').'admin/index');
		} else {
			$data['token'] = $token;
			$this->template->set_master_template('../../themes/'.$this->config->item("active_template").'/landing_template_login.php');
			$this->template->write_view('content', 'admin/ResetPassword', $data);
			$this->template->render();
		}
	}

	public function CheckLoginData(){

		   $inps = $this->input->post();
		   $Username = isset($inps['EmailInput']) ? trim($inps['EmailInput']) : '';
		   $LogPassword = isset($inps['PassInput']) ? trim($inps['PassInput']) : '';
		 
		  if($Username!="")
			{ 

			$this->db->select('ihu.*');
			$this->db->where('ihu.EmpEmail',$Username);
			$this->db->where('ihu.EmpPass',md5($LogPassword));
			$this->db->where('ihu.UStatus',1); 

			$IUidquery = $this->db->get('IHUsers as ihu')->result_array();
			
			// echo"<pre>";print_r($userquery);
			 	 // echo $this->db->last_query(); exit;
				
				if(!empty($IUidquery)){
 

					$sess_array = array('IUid'=>$IUidquery[0]['IUid'],'EmpCode'=>$IUidquery[0]['EmpCode'],'EmpName'=>$IUidquery[0]['EmpName'],'EmpEmail'=>$IUidquery[0]['EmpEmail'],'EmpPhone'=>$IUidquery[0]['EmpPhone'],'EmpDOB'=>$IUidquery[0]['EmpDOB'],'EmpGender'=>$IUidquery[0]['EmpGender'],'EmpRoleId'=>$IUidquery[0]['Erid'],'DepDid'=>$IUidquery[0]['Did'],'UStatus'=>$IUidquery[0]['UStatus']);
                    //echo "<pre>HELLO"; print_r($sess_array);
                    $this->session->set_userdata('logged_in',$sess_array);
                    $IHRMS_Data = $this->session->userdata('logged_in');
                    //echo"<pre>sess";print_r($employee_det);
                    $data['Logdescription'] = "User ".$IHRMS_Data['IUid']." Logged in Successfully on ".date("Y M d H i s");
                    $ip_address = $this->input->ip_address();
                    $data['IUid'] = $IHRMS_Data['IUid'];
                    $data['EmpRole'] = $IHRMS_Data['EmpRoleId'];
                    $data['Ipaddress'] = $ip_address;
                    // $data['location'] = $Bid_Data['work_loc'];
                    $curr_time=time();
                    $login_time = date("Y-m-d H:i:s",$curr_time);
                    //echo"<pre> Login Time";print_r($login_time);
                    $data['LogInTime'] = $login_time;
                    $data['LogOutTime'] ='';
                     // exit;
                     $data['Status'] ='1';
                     $this->db->insert('IHrmsLogin_Log',$data);
                   
                     redirect($this->config->item('base_url').'admin/dashboard');
 
                 } else {

                 	$this->session->set_flashdata('error', 'Invalid Credentials or User Inactive.!');
	   			    redirect($this->config->item('base_url').'admin/index');


                 }
				
			}
		  	$this->template->set_master_template('../../themes/'.$this->config->item("active_template").'/landing_template_login.php');
			$this->template->write_view('content', 'admin/index');
			$this->template->render();

	}

 
public function dashboard()
{
    $Hrms_Session = $this->session->userdata("logged_in");

    if (empty($Hrms_Session)) {
        redirect($this->config->item("base_url") . "admin/index");
        return;
    }

    $roleId = $Hrms_Session["EmpRoleId"];
    $uid    = $Hrms_Session["IUid"];

    $currentUrl = strtolower(uri_string());
    $data["currentUrlArray"] = $this->admin_model->getBreadcrumb($currentUrl);

    // 1. Recruitment Stages
    $stages = $this->db
        ->order_by("StageOrder", "ASC")
        ->get("RecruitmentStages")
        ->result_array();

    foreach ($stages as &$stage) {
        $stage["count"] = $this->db
            ->group_start()
                ->where("CurrentStage", $stage["StageName"])
                ->or_where("CurrentStage", $stage["StageId"])
            ->group_end()
            ->count_all_results("JobApplications");
    }
    $data["recruitment_stages"] = $stages;

    // 2. Job Statistics
    $data["total_vacancies"]  = $this->db->count_all_results("IHRJobsList");
    $data["onhold_vacancies"] = $this->db->where("JobStatus", "On-Hold")->count_all_results("IHRJobsList");
    $data["open_vacancies"]   = $this->db->where("JobStatus", "Open")->count_all_results("IHRJobsList");

    // 3. Resource Requests Statistics
    $data["total_resource_requests"]   = $this->db->count_all_results("resource_requests");
    $data["pending_resource_requests"] = $this->db->where("Status", "PENDING APPROVAL")->count_all_results("resource_requests");
    $data["accepted_resource_requests"]= $this->db->where("Status", "ACCEPTED")->count_all_results("resource_requests");

    // 4. Candidate Statistics
    $rejected = $this->db->like("CurrentStatus", "Rejected")->count_all_results("JobApplications");
    $screenedStage = $this->db
        ->group_start()
            ->where("StageGroup", "Application")
            ->like("StageName", "Screened")
        ->group_end()
        ->get("RecruitmentStages")
        ->row();
    $screenedStageId = $screenedStage ? $screenedStage->StageId : 2;

    $screened = $this->db
        ->group_start()
            ->where("CurrentStage", $screenedStageId)
            ->or_where("CurrentStage", "Screened")
            ->or_where("CurrentStage", " Screened")
        ->group_end()
        ->count_all_results("JobApplications");

    // Donut Charts Data
    $data["donut_labels"] = json_encode(["Total Vacancies", "On Hold", "Rejected", "Screened"]);
    $data["donut_values"] = json_encode([$data["total_vacancies"], $data["onhold_vacancies"], $rejected, $screened]);

    // Monthly Applications Analytics
    $monthly_apps = array_fill(1, 12, 0);
    $monthly_selected = array_fill(1, 12, 0);
    $monthly_rejected = array_fill(1, 12, 0);

    $this->db->select("MONTH(AppliedOn) as month, COUNT(ApplicationId) as total");
    $this->db->from("JobApplications");
    $this->db->where("YEAR(AppliedOn)", date("Y"));
    $this->db->group_by("MONTH(AppliedOn)");
    $apps_res = $this->db->get()->result_array();
    foreach ($apps_res as $row) {
        $m = (int)$row["month"];
        if ($m >= 1 && $m <= 12) $monthly_apps[$m] = (int)$row["total"];
    }

    $this->db->select("MONTH(AppliedOn) as month, COUNT(ApplicationId) as total");
    $this->db->from("JobApplications");
    $this->db->where("YEAR(AppliedOn)", date("Y"));
    $this->db->group_start()
        ->like("CurrentStatus", "Selected")
        ->or_like("CurrentStatus", "Accepted")
        ->or_like("CurrentStatus", "Released")
        ->or_like("CurrentStatus", "Boarding")
    ->group_end();
    $this->db->group_by("MONTH(AppliedOn)");
    $sel_res = $this->db->get()->result_array();
    foreach ($sel_res as $row) {
        $m = (int)$row["month"];
        if ($m >= 1 && $m <= 12) $monthly_selected[$m] = (int)$row["total"];
    }

    $this->db->select("MONTH(AppliedOn) as month, COUNT(ApplicationId) as total");
    $this->db->from("JobApplications");
    $this->db->where("YEAR(AppliedOn)", date("Y"));
    $this->db->like("CurrentStatus", "Rejected");
    $this->db->group_by("MONTH(AppliedOn)");
    $rej_res = $this->db->get()->result_array();
    foreach ($rej_res as $row) {
        $m = (int)$row["month"];
        if ($m >= 1 && $m <= 12) $monthly_rejected[$m] = (int)$row["total"];
    }

    $data["monthly_labels"] = json_encode(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]);
    $data["monthly_values"] = json_encode(array_values($monthly_apps));
    $data["area_posted"]    = json_encode(array_values($monthly_selected));
    $data["area_rejected"]  = json_encode(array_values($monthly_rejected));

    // User Roles Count Data
    $this->db->select("r.RoleName, COUNT(u.IUid) as total_users");
    $this->db->from("IHUsers u");
    $this->db->join("emproles r", "u.Erid = r.Erid", "left");
    $this->db->group_by("u.Erid");
    $result = $this->db->get()->result_array();

    $userLabels = [];
    $userCounts = [];
    foreach ($result as $row) {
        $userLabels[] = !empty($row["RoleName"]) ? $row["RoleName"] : "Unassigned";
        $userCounts[] = (int)$row["total_users"];
    }
    $data["user_labels"] = json_encode($userLabels);
    $data["user_counts"] = json_encode($userCounts);

    // Jobs & Candidates List
    $this->db->select("jl.*, d.Departmentname");
    $this->db->from("IHRJobsList jl");
    $this->db->join("Departments d", "d.Did = jl.Did", "left");
    $this->db->order_by("jl.PostedOn", "DESC");
    $data["all_jobs"] = $this->db->get()->result_array();

    $this->db->select("ja.ApplicationId, ja.CurrentStage, ja.CurrentStatus, ja.AppliedOn, c.CandidateId, c.Fullname, c.Email, c.ATS_Status, jl.JobTitle, jl.Did, d.Departmentname");
    $this->db->from("JobApplications ja");
    $this->db->join("IHrCandidates c", "c.CandidateId = ja.CandidateId", "inner");
    $this->db->join("IHRJobsList jl", "jl.Jid = ja.Jid", "inner");
    $this->db->join("Departments d", "d.Did = jl.Did", "left");
    $this->db->order_by("ja.AppliedOn", "DESC");
    $data["all_candidates"] = $this->db->get()->result_array();

    $data["departments"] = $this->admin_model->getDepartments();

    // Render Master Dashboard for all roles
    $this->template->set_master_template("../../themes/" . $this->config->item("active_template") . "/bo_template.php");
    $this->template->write_view("content", "admin/Dashboard", $data);
    $this->template->render();
}

public function ManageUsers(){

		$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{	
			
        $currentUrl = strtolower(uri_string());

      
       	 $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);
       	 

				 // echo"<pre>";print_r( $data['currentUrlArray']); exit;
        
	            $data['users'] = $this->admin_model->getUsers(); 
	            $data['department'] = $this->admin_model->getUserDepartments();
	            $data['role']       = $this->admin_model->getUserRoles();
				$this->template->write_view('content', 'admin/ManageUsers', $data);
				$this->template->render();
       
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
 
public function Candidatelist($Jid){

		$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{	
			//Get current URL (bid/CreateBids)
        $currentUrl = strtolower(uri_string());

        
       	 $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);
       	 

				  // echo"<pre>";print_r( $data['currentUrlArray']); exit;
        
	            // $data['users'] = $this->admin_model->getUsers(); 
	            // $data['department'] = $this->admin_model->getUserDepartments();
	            $data['Candidatelist']       = $this->admin_model->getCandidatesList($Jid);
                $data['jobdetails'] = $this->db
    ->where('Jid',$Jid)
    ->get('IHRJobsList')
    ->row_array();
				$this->template->write_view('content', 'admin/Candidatelist', $data);
				$this->template->render();
       
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
 


public function getCandidateIdDetails()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $candidate_id = $this->input->post('candidate_id');
         // echo "<pre>candidate_id"; print_r($candidate_id); exit;
        if (!$candidate_id) {
            echo json_encode(['status' => 'error', 'message' => 'Candidate ID missing']);
            return;
        }

      

        $this->db->select('
            c.*,
            j.JobTitle,
            j.JobLocation,
            j.EmploymentType,
            j.ExpMin,
            j.ExpMax,
            ja.ApplicationId,
            ja.CurrentStage,
            ja.CurrentStatus,
            ja.AppliedOn
        ');

        $this->db->from('IHrCandidates c');
        $this->db->join('IHRJobsList j', 'j.Jid = c.Jid', 'left');
        $this->db->join('JobApplications ja', 'ja.CandidateId = c.CandidateId AND ja.Jid = c.Jid', 'left');
        $this->db->where('c.CandidateId', $candidate_id);

        $candidate = $this->db->get()->row_array();
    

        if (!$candidate) {
            echo json_encode(['status' => 'error', 'message' => 'Candidate not found']);
            return;
        }
    if (!empty($candidate['ExperienceDetails'])) {
        $candidate['experience_details'] = json_decode($candidate['ExperienceDetails'], true);
    }
        $applicationId = $candidate['ApplicationId'];

      
    	$this->db->select('
    	    t.*,
    	    rs.StageName,
    	    u.IUid,
    	    u.EmpName as ActionByName
    	');

    	$this->db->from('CandidateStageTracking t'); 
    	$this->db->join('RecruitmentStages rs', 'rs.StageId = t.StageId', 'left'); 
    	$this->db->join('IHUsers u', 'u.IUid = t.ActionBy', 'left'); 
    	$this->db->where('t.ApplicationId', $applicationId); 
    	$this->db->order_by('t.ActionAt', 'ASC'); 
    	$stages = $this->db->get()->result_array();

    

        $interviews = $this->db
            ->where('ApplicationId', $applicationId)
            ->order_by('InterviewId', 'ASC')
            ->get('CandidateInterviews')
            ->result_array();

        foreach ($interviews as $idx => &$iv) {
            $iv['InterviewRound'] = $idx + 1;
        }
        unset($iv);

      

        $offers = $this->db
            ->where('ApplicationId', $applicationId)
            ->get('CandidateOffers')
            ->result_array();

   

        $this->db->select('f.*, u.IUid,u.EmpName as CreatedByName');
        $this->db->from('CandidateFollowUps f');
        $this->db->join('IHUsers u', 'u.IUid = f.CreatedBy', 'left');
        $this->db->where('f.ApplicationId', $applicationId);
        $this->db->order_by('f.CreatedAt', 'DESC');

        $followups = $this->db->get()->result_array();

        

        echo json_encode([
            'status' => 'success',
            'data' => [
                'candidate'  => $candidate,
                'stages'     => $stages,
                'interviews' => $interviews,
                'offers'     => $offers,
                'followups'  => $followups
            ]
        ]);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function ManageDepartments(){

		$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{	
			
        $currentUrl = strtolower(uri_string());

       
       	 $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);
       	 

				 // echo"<pre>";print_r( $data['currentUrlArray']); exit;
        
 	            $data['department'] = $this->admin_model->getDepartments();
 				$this->template->write_view('content', 'admin/ManageDepartments', $data);
				$this->template->render();
       
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
 
   public function SaveUser()
    {
    // echo "<pre>";print_r($this->input->post());exit;
    
    $Hrms_Session = $this->session->userdata('logged_in');
    if(isset($Hrms_Session) && !empty($Hrms_Session))
            {	
                $inps = $this->input->post();

                $EmpCode        = trim($inps['val-empid']);
                $EmpName        = trim($inps['val-username']);
                $EmpEmail       = trim($inps['val-email']);
                $EmpPhone       = trim($inps['val-phoneus']);
                $EmpDOB         = $inps['val-dob'];
                $EmpGender      = $inps['val-gender'];
                $EmpDesignation = trim($inps['val-designation']);
                $EmpRole        = $inps['val-Role'];
                $EmpDept        = $inps['val-department'];

               
                $formValues = [
                    'val-empid'       => $EmpCode,
                    'val-username'    => $EmpName,
                    'val-email'       => $EmpEmail,
                    'val-phoneus'     => $EmpPhone,
                    'val-dob'         => $EmpDOB,
                    'val-gender'      => $EmpGender,
                    'val-designation' => $EmpDesignation,
                    'val-Role'        => $EmpRole,
                    'val-department'  => $EmpDept,
                ];

                
                $existsCode = $this->db
                    ->where('EmpCode', $EmpCode)
                    ->count_all_results('IHUsers');
                if ($existsCode > 0) {
                    $this->session->set_flashdata('error', 'Employee Code already exists. Please use a different Employee Code.');
                    $this->session->set_flashdata('form_values', $formValues);
                    redirect($this->config->item('base_url').'admin/ManageUsers');
                    return;
                }

               
                $existsEmail = $this->db
                    ->where('EmpEmail', $EmpEmail)
                    ->count_all_results('IHUsers');
                if ($existsEmail > 0) {
                    $this->session->set_flashdata('error', 'Email Address already exists. Please use a different email.');
                    $this->session->set_flashdata('form_values', $formValues);
                    redirect($this->config->item('base_url').'admin/ManageUsers');
                    return;
                }

              
                $existsPhone = $this->db
                    ->where('EmpPhone', $EmpPhone)
                    ->count_all_results('IHUsers');
                if ($existsPhone > 0) {
                    $this->session->set_flashdata('error', 'Mobile Number already exists. Please use a different mobile number.');
                    $this->session->set_flashdata('form_values', $formValues);
                    redirect($this->config->item('base_url').'admin/ManageUsers');
                    return;
                }

                $insertData = [
                    'EmpCode'        => $EmpCode,
                    'EmpName'        => $EmpName,
                    'EmpEmail'       => $EmpEmail,
                    'EmpPass'        => md5($EmpPhone),
                    'EmpPhone'       => $EmpPhone,
                    'EmpDOB'         => $EmpDOB,
                    'EmpGender'      => $EmpGender,
                    'EmpDesignation' => $EmpDesignation,
                    'Erid'           => $EmpRole,
                    'Did'            => $EmpDept,
                    'UStatus'        => 1
                ];

                try {
                    $this->db->insert('IHUsers', $insertData);
                    $UsrId = $this->db->insert_id();

                    if ($UsrId > 0) {
                        $this->session->set_flashdata('success', 'User added successfully.');
                        redirect($this->config->item('base_url').'admin/ManageUsers');
                    } else {
                        $this->session->set_flashdata('error', 'User creation failed. Please try again.');
                        $this->session->set_flashdata('form_values', $formValues);
                        redirect($this->config->item('base_url').'admin/ManageUsers');
                    }
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'An unexpected error occurred while saving the user. Please try again.');
                    $this->session->set_flashdata('form_values', $formValues);
                    redirect($this->config->item('base_url').'admin/ManageUsers');
                }

            }else
            {
            $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
                redirect($this->config->item('base_url')."admin/index");
            }

    }
 
 
public function VaccancyList(){

		$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{	
			//Get current URL (bid/CreateBids)
        $currentUrl = strtolower(uri_string());

       			 //Fetch breadcrumb data
       			 $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);
       	 
       	 		  $data['vaclist'] = $this->admin_model->get_VaccancyList();	
       			  $data['department'] = $this->admin_model->getUserDepartments();
				 // echo"<pre>";print_r( $data['vaclist']); exit;
				$this->template->write_view('content', 'admin/VaccancyList', $data);
				$this->template->render();
       
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
 
public function searchLocation()
{
	$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{
			    $q = $this->input->get('q');

			    if (strlen($q) < 3) {
			        echo json_encode([]);
			        return;
			    }

			 	 $this->db->distinct();
				$this->db->select('JobLocation');
				$this->db->like('JobLocation', $q);
				$this->db->where('JobLocation !=', '');
				$this->db->limit(10);

				$result = $this->db->get('IHRJobsList')->result_array();

 			     // echo "<pre>result"; print_r($result); //exit;
			     // echo $this->db->last_query(); exit;
			    echo json_encode($result);
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
public function searchEducation()
{
	$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{
			    $q = $this->input->get('q');

			    if (strlen($q) < 3) {
			        echo json_encode([]);
			        return;
			    }

			 	 $this->db->distinct();
				$this->db->select('EducationRequired');
				$this->db->like('EducationRequired', $q);
				$this->db->where('EducationRequired !=', '');
				$this->db->limit(10);

				$result = $this->db->get('IHRJobsList')->result_array();

 			     // echo "<pre>result"; print_r($result); //exit;
			     // echo $this->db->last_query(); exit;
			    echo json_encode($result);
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
public function searchLanguage()
{
	$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{
			    $q = $this->input->get('q');

			    if (strlen($q) < 3) {
			        echo json_encode([]);
			        return;
			    }

			 	 $this->db->distinct();
				$this->db->select('CommunicationLang');
				$this->db->like('CommunicationLang', $q);
				$this->db->where('CommunicationLang !=', '');
				$this->db->limit(10);

				$result = $this->db->get('IHRJobsList')->result_array();

 			     // echo "<pre>result"; print_r($result); //exit;
			     // echo $this->db->last_query(); exit;
			    echo json_encode($result);
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
public function searchSkills()
{
	$Hrms_Session=$this->session->userdata('logged_in');  
 		// echo "<pre>"; print_r($Hrms_Session); exit;
 		if(isset($Hrms_Session) && !empty($Hrms_Session))
		{
			    $q = $this->input->get('q');

			    if (strlen($q) < 3) {
			        echo json_encode([]);
			        return;
			    }

			 	 $this->db->distinct();
				$this->db->select('SkillName');
				$this->db->like('SkillName', $q);
				$this->db->where('SkillName !=', '');
				$this->db->limit(10);

				$result = $this->db->get('IHSkills')->result_array();

 			     // echo "<pre>result"; print_r($result); //exit;
			     // echo $this->db->last_query(); exit;
			    echo json_encode($result);
		} else
		{
		$this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
			redirect($this->config->item('base_url')."admin/index");
		}

}
 
public function saveVacancy(){

    $Hrms_Session = $this->session->userdata('logged_in');

    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

        $jobTitle   = trim($this->input->post('jobTitle'));
        $department = $this->input->post('department');

       
        $exists = $this->db
            ->where('JobTitle', $jobTitle)
            ->where('Did', $department)
            ->get('IHRJobsList')
            ->row();

        if($exists){
            $this->session->set_flashdata('job_exists', 'This job title already exists in this department');
            redirect($this->config->item('base_url')."admin/VaccancyList");
            return;
        }

        $jobCode = $this->generateJobCode();

        $jobData = [
            'JobCode'           => $jobCode,
            'JobTitle'          => $this->input->post('jobTitle'),
            'RoleSummary'       => $this->input->post('role'),
            'Did'               => $this->input->post('department'),
            'EmploymentType'    => $this->input->post('employmentType'),
            'WorkMode'          => $this->input->post('workMode'),
            'EducationRequired' => $this->input->post('education'),
            'ExpMin'            => $this->input->post('expMin'),
            'ExpMax'            => $this->input->post('expMax'),
            'SalMin'            => $this->input->post('salaryMin'),
            'SalMax'            => $this->input->post('salaryMax'),
            'Currency'          => 'INR',
            'NoofOpenings'      => $this->input->post('positions'),
            'JobStatus'         => 'Open',
            'JobDescription'    => $this->input->post('JD'),
            'Responsibilities'  => $this->input->post('RR'),
            'Qualifications'    => $this->input->post('education'),
            'JobLocation'       => $this->input->post('jobLocation'),
            'CommunicationLang' => $this->input->post('comLanguage'),
            'PostedBy'          => $Hrms_Session['IUid'],
            'ExpiryDate' => date('Y-m-d', strtotime($this->input->post('ExpiryDate'))),
            'SkillScore'           => ($this->input->post('SkillScore') !== null && $this->input->post('SkillScore') !== '') ? $this->input->post('SkillScore') : 50,
            'EducationScore'       => ($this->input->post('EducationScore') !== null && $this->input->post('EducationScore') !== '') ? $this->input->post('EducationScore') : 20,
            'ExperienceScore'      => ($this->input->post('ExperienceScore') !== null && $this->input->post('ExperienceScore') !== '') ? $this->input->post('ExperienceScore') : 20,
            'ProjectScore'         => ($this->input->post('ProjectScore') !== null && $this->input->post('ProjectScore') !== '') ? $this->input->post('ProjectScore') : 5,
            'CertificationScore'   => ($this->input->post('CertificationScore') !== null && $this->input->post('CertificationScore') !== '') ? $this->input->post('CertificationScore') : 10,
            'ResumeQualityScore'   => ($this->input->post('ResumeQualityScore') !== null && $this->input->post('ResumeQualityScore') !== '') ? $this->input->post('ResumeQualityScore') : 5,
            'DomainKnowledgeScore' => ($this->input->post('DomainKnowledgeScore') !== null && $this->input->post('DomainKnowledgeScore') !== '') ? $this->input->post('DomainKnowledgeScore') : 5
        ];

        $this->db->insert('IHRJobsList', $jobData);
        $jobId = $this->db->insert_id();

        
        $this->saveSkills($this->input->post('skills'));
        $skills = $this->input->post('skills');
        $this->saveJobSkills($jobId, $skills);

        if($jobId > 0){

            $this->session->set_flashdata('success','Job Vacancy Added Successfully');
            redirect($this->config->item('base_url')."admin/VaccancyList");

        } else {

            $this->session->set_flashdata('error','Failed to Save');
            redirect($this->config->item('base_url')."admin/VaccancyList");

        }

    } else {

        $this->session->set_flashdata('error','Invalid Session.Please Login Again');
        redirect($this->config->item('base_url')."admin/index");

    }
}
private function generateJobCode()
{
    $prefix = '#IHRMS-' . date('Ym') . '-';

    $this->db->like('JobCode', $prefix);
    $this->db->order_by('JobCode', 'DESC');
    $last = $this->db->get('IHRJobsList')->row();

    if ($last) {
        $lastNo = (int) substr($last->JobCode, -4);
        $newNo  = str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);

    } else {
        $newNo = '0001';
    }

    return $prefix . $newNo;
}

private function saveSkills($skillsCsv)
{
    if (!$skillsCsv) return;

    $skills = array_unique(array_map('trim', explode(',', $skillsCsv)));

    foreach ($skills as $skill) {
        if ($skill === '') continue;

        $exists = $this->db
            ->where('SkillName', $skill)
            ->get('IHSkills')
            ->row();

        if (!$exists) {
            $this->db->insert('IHSkills', [
                'SkillName' => $skill
            ]);
        }
    }
}

public function saveJobSkills($jobId, $skills)
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        if (empty($skills)) return;

        $skillsArr = array_unique(array_map('trim', explode(',', $skills)));

        foreach ($skillsArr as $skillName) {

            
            $skill = $this->db->get_where('IHSkills', [
                'SkillName' => $skillName
            ])->row();

            if ($skill) {
                $skillId = $skill->SkillId;
            } else {
               
                $this->db->insert('IHSkills', [
                    'SkillName' => $skillName
                ]);
                $skillId = $this->db->insert_id();
            }

            
            $exists = $this->db->get_where('JobSkills', [
                'Jid'     => $jobId,
                'SkillId'=> $skillId
            ])->num_rows();

            if ($exists == 0) {
                $this->db->insert('JobSkills', [
                    'Jid'     => $jobId,
                    'SkillId'=> $skillId
                ]);
            }
        }

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
public function updateJobStatus(){

 $Hrms_Session = $this->session->userdata('logged_in');

    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

        $inps = $this->input->post();

         $jid = $this->input->post('jid');
	     $status = $this->input->post('status');

	    $this->db->where('Jid', $jid)
	             ->update('IHRJobsList', [
	                 'JobStatus' => $status,
	                 'UpdatedOn' => date('Y-m-d H:i:s')
	             ]);

			    echo json_encode([
		   		 'status'  => 'success',
		   		 'message' => 'Job status updated successfully'
				]);

    } else {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
public function UpdateUser()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

        $inps = $this->input->post();

        
       

        $this->db->where('IUid', $inps['IUid']);
        $this->db->update('IHUsers', [
            'EmpName'        => $inps['val-username'],
            'EmpEmail'       => $inps['val-email'],
            'EmpPhone'       => $inps['val-phoneus'],
            'EmpDOB'         => $inps['val-dob'],
            'EmpGender'      => $inps['val-gender'],
            'EmpDesignation' => $inps['val-designation'],
            'Erid'           => $inps['val-Role'],
            'Did'            => $inps['val-department'],
        
        ]);

    
        

        $this->session->set_flashdata('success', 'User updated successfully');
        redirect($this->config->item('base_url').'admin/ManageUsers');

    } else {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
    }
}

public function SaveDepartment()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

        $deptName = trim($this->input->post('val-depname'));

       
        $exists = $this->db
                       ->where('Departmentname', $deptName)
                       ->get('Departments')
                       ->row();

        if ($exists) {
            $this->session->set_flashdata('error', 'Department already exists');
            redirect($this->config->item('base_url') . 'admin/ManageDepartments');
            return;
        }

        $data = [
            'Departmentname' => $deptName,
            'Status'         => 1,
            'CreatedDate'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('Departments', $data);

        $this->session->set_flashdata('success', 'Department added successfully');
        redirect($this->config->item('base_url') . 'admin/ManageDepartments');

    } else {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function UpdateDepartment()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

        $inps = $this->input->post();

        
        

        $this->db->where('Did', $inps['Did']);
        $this->db->update('Departments', [
            'Departmentname'        => $inps['val-username'] 
        
        ]);

    
       

        $this->session->set_flashdata('success', 'Department updated successfully');
        redirect($this->config->item('base_url').'admin/ManageDepartments');

    } else {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
 

public function ActivateDepartment($id)
{
	    $Hrms_Session = $this->session->userdata('logged_in');

	    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

	    	$this->db->where('Did', $id);
            $this->db->update('Departments', [
                'Status' => 1
            ]);

		   
		   $this->session->set_flashdata('success', 'Department activated successfully');
		   redirect($this->config->item('base_url').'admin/ManageDepartments');

	    } else {

        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
} 

public function ActivateUser($id)
{
	    $Hrms_Session = $this->session->userdata('logged_in');

	    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

	    	$this->db->where('IUid', $id);
            $this->db->update('IHUsers', [
                'UStatus' => 1
            ]);

		    
		   $this->session->set_flashdata('success', 'User activated successfully');
		   redirect($this->config->item('base_url').'admin/ManageUsers');

	    } else {

        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function DeactivateUser($id)
{
    $Hrms_Session = $this->session->userdata('logged_in');
    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

    		
   			$this->db->where('IUid', $id);
            $this->db->update('IHUsers', [
                'UStatus'   => 0
            ]);

            
             
            $this->session->set_flashdata('success', 'User deactivated successfully');
            redirect($this->config->item('base_url').'admin/ManageUsers');

	   } else {

        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }

}
public function DeactivateDepartment($id)
{
    $Hrms_Session = $this->session->userdata('logged_in');
    if (isset($Hrms_Session) && !empty($Hrms_Session)) {

    		
   			$this->db->where('Did', $id);
            $this->db->update('Departments', [
                'Status'   => 0
            ]);

            
           
            $this->session->set_flashdata('success', 'Department deactivated successfully');
            redirect($this->config->item('base_url').'admin/ManageDepartments');

	   } else {

        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }

 } 

public function getJobDetails(){

    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

     $jid = $this->input->post('jid');

     $this->db->select("
        jl.*,
    	d.Departmentname,
    	  jl.RoleSummary,
    	  u.EmpName AS PostedByName,
        GROUP_CONCAT(s.SkillName SEPARATOR ',') AS Skills
     ");

     $this->db->from('IHRJobsList jl');
     //////
    $this->db->join('Departments d','d.Did = jl.Did','left');
     /////////
     $this->db->join('JobSkills js','js.Jid = jl.Jid','left');
     $this->db->join('IHSkills s','s.SkillId = js.SkillId','left');
     $this->db->where('jl.Jid',$jid);
     $this->db->group_by('jl.Jid');
     $this->db->join('IHUsers u','u.IUid = jl.PostedBy','left');

     $row = $this->db->get()->row_array();

     echo json_encode($row);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
public function updateVacancy()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $jid = $this->input->post('jid');
        if(empty($jid)){
            echo json_encode(['status'=>'error','msg'=>'JID missing']);
            return;
        }

        $data = array_filter([
            'EmploymentType'     => $this->input->post('employmentType'),
            'WorkMode'           => $this->input->post('workMode'),
            'EducationRequired' => $this->input->post('education'),
            'NoofOpenings'       => $this->input->post('positions'),
            'ExpMin'             => $this->input->post('expMin'),
            'ExpMax'             => $this->input->post('expMax'),
            'SalMin'             => $this->input->post('salaryMin'),
            'SalMax'             => $this->input->post('salaryMax'),
            'JobLocation'        => $this->input->post('jobLocation'),
            'CommunicationLang'  => $this->input->post('comLanguage'),
            'JobDescription'     => $this->input->post('JD'),
            'Responsibilities'   => $this->input->post('RR'),
    		 'ExpiryDate'         => date('Y-m-d', strtotime($this->input->post('ExpiryDate'))),
            'UpdatedOn'          => date('Y-m-d H:i:s')
        ]);

        
        $scoreFields = ['SkillScore', 'EducationScore', 'ExperienceScore', 'ProjectScore', 'CertificationScore', 'ResumeQualityScore', 'DomainKnowledgeScore'];
        foreach ($scoreFields as $field) {
            $val = $this->input->post($field);
            if ($val !== null && $val !== '') {
                $data[$field] = $val;
            }
        }

        $this->db->where('Jid',$jid)->update('IHRJobsList',$data);

       

        $skills = $this->input->post('skills'); 

        $this->db->where('Jid',$jid)->delete('JobSkills');

        if(!empty($skills) && is_array($skills)){

           foreach($skills as $skillName){

     $skillName = trim($skillName);
     if($skillName=='') continue;

  
     $row = $this->db->where('SkillName',$skillName)
                     ->get('IHSkills')
                     ->row();

    
     if(!$row){

       $this->db->insert('IHSkills',[
         'SkillName'=>$skillName
       ]);

       $skillId = $this->db->insert_id();

     }else{

       $skillId = $row->SkillId;
     }

    
     $this->db->insert('jobskills',[
       'Jid'=>$jid,
       'SkillId'=>$skillId
     ]);
    }
        }

        echo json_encode(['status'=>'success']);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}



public function getNextStages()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $currentOrder = (int)$this->input->post('currentOrder');

       
        $currentStage = $this->db
            ->where('StageOrder', $currentOrder)
            ->get('RecruitmentStages')
            ->row();

        $currentGroup = $currentStage ? $currentStage->StageGroup : 'Application';

       
        $this->db->group_start()
            ->group_start()
                ->where('StageGroup', $currentGroup)
                ->where('StageOrder >', $currentOrder)
            ->group_end()
            ->or_where('StageGroup', 'Rejection')
        ->group_end();

        $this->db->where('StageStatus', 1);
        $this->db->order_by('StageOrder','ASC');

        echo json_encode($this->db->get('RecruitmentStages')->result());

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}




public function saveCandidateStage()
{

    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $candidateId   = $this->input->post('candidateId');
        $stageId       = $this->input->post('stageId');
        $action        = $this->input->post('action');
        $remarks       = $this->input->post('remarks');
        $followupType  = $this->input->post('followupType');
        $nextDate      = $this->input->post('nextFollowupDate');
        $interviewDate = $this->input->post('interviewDate');

       
        if (!empty($interviewDate)) {
           
            $interviewDate = str_replace('T', ' ', $interviewDate);
            
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $interviewDate)) {
                $interviewDate .= ':00';
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $interviewDate)) {
              
                $interviewDate .= ' 00:00:00';
            }
        }
        $level         = $this->input->post('interviewLevel');
        $interviewType = $this->input->post('interviewType');
        $interviewerId = $this->input->post('interviewerId');

        $actionLower = strtolower(trim($action));

        if ($actionLower == 'shortlisted' || $actionLower == 'reschedule') {
            if (empty($interviewDate)) {
                echo json_encode(['status' => 'error', 'msg' => 'Interview schedule date is required.']);
                return;
            }
        }

       
        $debugLog  = date('Y-m-d H:i:s') . " saveCandidateStage POST:\n";
        $debugLog .= "  action        = " . $this->input->post('action')        . "\n";
        $debugLog .= "  interviewDate = " . $this->input->post('interviewDate')  . "\n";
        $debugLog .= "  interviewDate_normalized = " . $interviewDate . "\n";
        $debugLog .= "  interviewType = " . $interviewType . "\n";
        $debugLog .= "  interviewerId = " . $interviewerId . "\n";
        $debugLog .= "  interviewLevel= " . $level . "\n";
        $debugLog .= "---\n";
        file_put_contents(FCPATH . 'interview_debug.log', $debugLog, FILE_APPEND);
       

        $actionLower = strtolower(trim($action));

        if(($actionLower == 'shortlisted' || $actionLower == 'reschedule') && !empty($level)){
            $stageId = $level;
        }

        if($actionLower == 'rejected' && empty($stageId)){
           
            $fallback = $this->db
                ->where('StageGroup', 'Rejection')
                ->where('StageStatus', 1)
                ->order_by('StageOrder', 'ASC')
                ->get('RecruitmentStages')
                ->row();
            if($fallback){
                $stageId = $fallback->StageId;
            }
        }

     
        $app = $this->db->select('ApplicationId')->from('JobApplications')->where('CandidateId',$candidateId)
                        ->order_by('ApplicationId','DESC')->limit(1)->get()->row();

        if(!$app){
            echo json_encode(['status'=>'error','msg'=>'Application not found']);
            return;
        }

        $applicationId = $app->ApplicationId;

      
        $this->db->insert('CandidateStageTracking',[
            'ApplicationId' => $applicationId,
            'StageId'       => $stageId,
            'Action'        => $action,
            'ActionBy'      => $Hrms_Session['IUid'],
            'ActionAt'      => date('Y-m-d H:i:s'),
            'Remarks'       => $remarks
        ]);

  
    $currentStatus = 'In Progress';


    if(strtolower($action) == 'rejected'){
        $currentStatus = 'Rejected';
    }

    // If on hold
    elseif(strtolower($action) == 'on hold'){
        $currentStatus = 'On Hold';
    }

    // If reschedule
    elseif(strtolower($action) == 'reschedule'){
        $currentStatus = 'Rescheduled';
    }

  
    elseif(strtolower($action) == 'shortlisted' && !empty($level)){

        $stageRow = $this->db->where('StageId', $level)->get('recruitmentstages')->row();

        if($stageRow){
            $currentStatus = $stageRow->StageName;   
        }
    }

        $this->db->where('ApplicationId',$applicationId)->update('JobApplications',['CurrentStage'  => $stageId,'CurrentStatus' => $currentStatus
                 ]);

                


        
        $isFollowupStage = false;
        if(!empty($stageId)){
            $stageRow = $this->db->where('StageId', $stageId)->get('RecruitmentStages')->row();
            if($stageRow){
                $stageNameLower = strtolower(trim($stageRow->StageName));
                if($stageNameLower === 'switch off' || $stageNameLower === 'rnr'){
                    $isFollowupStage = true;
                }
            }
        }

        if($isFollowupStage && !empty($followupType)){
            $this->db->insert('CandidateFollowUps',[
                'ApplicationId'     => $applicationId,
                'FollowUpType'      => $followupType,
                'FollowUpNotes'     => $remarks,
                'NextFollowUpDate'  => $nextDate,
                'CreatedBy'         => $Hrms_Session['IUid'],
                'CreatedAt'         => date('Y-m-d H:i:s')
            ]);
        }

      
    if((strtolower($action) == 'shortlisted' || strtolower($action) == 'reschedule') && empty($interviewerId)){

        $this->db->where('ApplicationId',$applicationId)
                 ->update('JobApplications', [
                     'CurrentStatus' => 'Rejected'
                 ]);

        $data['candidatelist'] = $this->db
            ->where('CandidateId',$candidateId)
            ->get('IHRCandidates')
            ->row();

        if(empty($data['candidatelist']) || empty($data['candidatelist']->Email)){
            echo json_encode(['status'=>'error','msg'=>'Candidate email missing']);
            return;
        }

        $data['action'] = 'rejected';

        try {

            $to = $data['candidatelist']->Email;

            $subject = "Application Status - I-Net Secure Labs Pvt Ltd.";

            require(APPPATH.'libraries/InetMailer.php');
            $objs = new InetMailer();
            $mail = $objs->load();

            $mail->setFrom('info@inetcsc.com', 'I-NET CSC');
            $mail->addAddress(trim($to));

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $this->load->view('admin/CandisateEmail',$data,TRUE);

            if(!$mail->send()){
                echo json_encode(['status'=>'error','msg'=>$mail->ErrorInfo]);
            } else {
                echo json_encode(['status'=>'rejected','msg'=>'Rejected mail sent']);
            }

        } catch (\Exception $e) {
            echo json_encode(['status'=>'error','msg'=>$e->getMessage()]);
        }

        return;
    }
            
            $existingCount = $this->db->where('ApplicationId', $applicationId)->count_all_results('CandidateInterviews');
            $isReschedule = (strtolower($action) == 'reschedule');
            if ($isReschedule && $existingCount > 0) {
                $targetRound = $existingCount;
            } else {
                $targetRound = $existingCount + 1;
            }

            
            $existingInterview = $this->db
                ->where('ApplicationId', $applicationId)
                ->where('InterviewRound', $targetRound)
                ->get('CandidateInterviews')
                ->row();

            $insertOrUpdateLog = "";
            $interviewDataToSave = [
                'ApplicationId'  => $applicationId,
                'InterviewRound' => $targetRound,
                'InterviewType'  => $interviewType,
                'InterviewerId'  => $interviewerId,
                'Result'         => 'Assigned'
            ];

            
            if (!empty($interviewDate) && $interviewDate !== '0000-00-00 00:00:00') {
                $interviewDataToSave['ScheduledAt'] = $interviewDate;
            }

            if ($existingInterview) {
               
                $this->db->where('InterviewId', $existingInterview->InterviewId)
                         ->update('CandidateInterviews', $interviewDataToSave);
                $interviewId = $existingInterview->InterviewId;
                $insertOrUpdateLog = "UPDATE CandidateInterviews (ID: $interviewId)";
            } else {
                $this->db->insert('CandidateInterviews', $interviewDataToSave);
                $interviewId = $this->db->insert_id();
                $insertOrUpdateLog = "INSERT CandidateInterviews (ID: $interviewId)";
            }

            
            $debugLog  = date('Y-m-d H:i:s') . " saveCandidateStage DB Save Details:\n";
            $debugLog .= "  Operation        = " . $insertOrUpdateLog . "\n";
            $debugLog .= "  ApplicationId    = " . $applicationId . "\n";
            $debugLog .= "  InterviewRound   = " . $targetRound . "\n";
            $debugLog .= "  InterviewType    = " . $interviewType . "\n";
            $debugLog .= "  ScheduledAt      = " . ($interviewDataToSave['ScheduledAt'] ?? 'OMITTED/NULL') . "\n";
            $debugLog .= "  InterviewerId    = " . $interviewerId . "\n";
            $debugLog .= "---\n";
            file_put_contents(FCPATH . 'interview_debug.log', $debugLog, FILE_APPEND);

           
            $this->load->model('Notification_model');
            $notifTitle = ($isReschedule) ? 'Interview Rescheduled' : 'New Interview Scheduled';
            $notifMsg = "An interview ($interviewType) has been scheduled/rescheduled on " . ($interviewDataToSave['ScheduledAt'] ?? '-') . ".";
            $this->Notification_model->addNotification($notifTitle, $notifMsg, 'info', $interviewerId, null);
         

           
            $this->db->where('ApplicationId',$applicationId)->update('JobApplications',['CurrentStage' => $level]);
            


                $data['candidatelist'] = $this->db->select('*')->from('IHRCandidates')->where('CandidateId',$candidateId)
                        ->get()->row();
                        $data['action'] = strtolower($action);

                      $jobRow = $this->db
        ->select('j.JobTitle')
        ->from('IHRCandidates c')
        ->join('IHRJobsList j', 'j.Jid = c.Jid', 'left')
        ->where('c.CandidateId', $candidateId)
        ->get()
        ->row();

    $data['jobTitle'] = $jobRow ? $jobRow->JobTitle : 'Job Position';
    $data['interviewDate']  = $interviewDate;   
    $data['interviewTime']  = $this->input->post('interviewTime');
    $data['interviewMode']  = $interviewType;  



                   try {
    		           
    		          		$to = $data['candidatelist']->Email;
                           
    	   
    			           
                            if(strtolower($action) == 'rejected'){
        $subject = "Application Status - I-Net Secure Labs Pvt Ltd.";
    } elseif(strtolower($action) == 'reschedule'){
        $subject = "Interview Rescheduled - I-Net Secure Labs Pvt Ltd.";
    } else {
        $subject = "Congratulations You are Shortlisted - I-Net Secure Labs Pvt Ltd.";
    }
			             
    			            require(APPPATH.'libraries/InetMailer.php');
    			            $objs = new InetMailer();
    			            $mail = $objs->load(); 
    			            $mail->setFrom('info@inetcsc.com', 'I-NET CSC');
    			            $mail->addAddress(trim($to)); 
			 				 
    			          

    			            $mail->isHTML(true); 
    			            $mail->Subject = $subject; 
    			            $mail->Body = $this->load->view('admin/CandisateEmail',$data,TRUE); 
    			           if($mail->send()){            
			           
    	           				 echo json_encode(['status'=>'success']);

    			           }else{ 
			            
    			            	 echo json_encode(['status'=>'failed']);

    			          }
    		          } catch (\Exception $e) {
		            
    		          }       
                     

   


    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
} 

    public function getInterviewLevels()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $this->db->where('StageGroup', 'Interview');
        $this->db->where('StageStatus', 1);
        $this->db->order_by('StageOrder','ASC');

        echo json_encode($this->db->get('RecruitmentStages')->result_array());

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function MyInterviews()
{



    $Hrms_Session = $this->session->userdata('logged_in');
    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $uid = $Hrms_Session['IUid'];

        $data['Candidatelist'] = $this->db
            ->select('
                c.CandidateId,
                c.CandidateCode,
                c.Fullname,
                c.Email,
                c.PhoneNo,
                c.ProfileMatchPer,
                ja.CurrentStage,
                ja.CurrentStatus,
                ja.AppliedOn,
                rs.StageOrder as CurrentStageOrder,
                j.Jid,
                ci.InterviewId,
                ci.Result,
                ci.ScheduledAt,
                (
                   SELECT Action
                   FROM CandidateStageTracking
                   WHERE ApplicationId = ja.ApplicationId
                   ORDER BY ActionAt DESC
                   LIMIT 1
                ) as LastAction
            ')
            ->from('CandidateInterviews ci')
            ->join('JobApplications ja','ja.ApplicationId = ci.ApplicationId')
            ->join('IHrCandidates c','c.CandidateId = ja.CandidateId')
            ->join('IHRJobsList j','j.Jid = ja.Jid')
            ->join('RecruitmentStages rs','rs.StageId = ja.CurrentStage','left')
            ->where('ci.InterviewerId',$uid)
            ->where('ci.ScheduledAt IS NOT NULL', null, false)
            ->group_by('ci.InterviewId')
            ->order_by('ci.ScheduledAt', 'ASC')
            ->get()
            ->result_array();

        $currentUrl = strtolower(uri_string());
        $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);

        $this->template->write_view('content','admin/my_interviews',$data);
        $this->template->render();

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function interviewCalendar()
{
    $Hrms_Session = $this->session->userdata('logged_in');
    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $uid = $Hrms_Session['IUid'];

        $interviews = $this->db
            ->select('
                c.CandidateId,
                c.CandidateCode,
                c.Fullname,
                c.Email,
                c.PhoneNo,
                c.ProfileMatchPer,
                ja.CurrentStage,
                ja.CurrentStatus,
                ja.AppliedOn,
                j.Jid,
                j.JobTitle,
                ci.InterviewId,
                ci.Result,
                ci.ScheduledAt,
                (SELECT COUNT(*) FROM CandidateInterviews ci2 WHERE ci2.ApplicationId = ci.ApplicationId AND ci2.InterviewId <= ci.InterviewId) AS InterviewRound
            ')
            ->from('CandidateInterviews ci')
            ->join('JobApplications ja','ja.ApplicationId = ci.ApplicationId')
            ->join('IHrCandidates c','c.CandidateId = ja.CandidateId')
            ->join('IHRJobsList j','j.Jid = ja.Jid')
            ->where('ci.InterviewerId', $uid)
            ->where('ci.ScheduledAt IS NOT NULL', null, false)
            ->group_by('ci.InterviewId')
            ->order_by('ci.ScheduledAt', 'ASC')
            ->get()
            ->result_array();

        
        $events = [];
        foreach($interviews as $iv) {
            if(empty($iv['ScheduledAt'])) continue;

            $result = strtolower(trim($iv['Result'] ?? ''));
            if($result == 'selected') {
                $color = '#28a745';
            } elseif($result == 'rejected') {
                $color = '#dc3545';
            } elseif($result == 'on hold') {
                $color = '#fd7e14';
            } else {
                $color = '#007bff';
            }

            $events[] = [
                'id'             => $iv['InterviewId'],
                'title'          => $iv['Fullname'] . ' (' . $iv['CandidateCode'] . ')',
                'start'          => $iv['ScheduledAt'],
                'end'            => date('Y-m-d H:i:s', strtotime($iv['ScheduledAt']) + 3600),
                'color'          => $color,
                'extendedProps'  => [
                    'candidateId'   => $iv['CandidateId'],
                    'email'         => $iv['Email'],
                    'phone'         => $iv['PhoneNo'],
                    'jobTitle'      => $iv['JobTitle'],
                    'result'        => $iv['Result'] ?? 'Assigned',
                    'round'         => $iv['InterviewRound'] ?? 1,
                    'interviewId'   => $iv['InterviewId'],
                    'status'        => $iv['CurrentStatus'],
                ]
            ];
        }

        $data['calendarEvents']  = json_encode($events);
        $data['Candidatelist']   = $interviews;

        $currentUrl = strtolower(uri_string());
        $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);

        $this->template->write_view('content','admin/interview_calendar',$data);
        $this->template->render();

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

///// 26.02.2026



public function updateInterviewResult()
{
   
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $interviewId = trim($this->input->post('interviewId'));
        $result      = trim($this->input->post('result'));
        $feedback    = $this->input->post('feedback');

        if(empty($interviewId) || empty($result)){
            echo json_encode(['status'=>'error','msg'=>'Missing data']);
            return;
        }

    
        $interview = $this->db
            ->where('InterviewId', $interviewId)
            ->get('CandidateInterviews')  
            ->row();

        if(!$interview){
            echo json_encode(['status'=>'error','msg'=>'Interview not found']);
            return;
        }

        $applicationId = $interview->ApplicationId;

        if(empty($applicationId)){
            echo json_encode(['status'=>'error','msg'=>'ApplicationId missing']);
            return;
        }

     
        $this->db->where('InterviewId',$interviewId)
                 ->update('CandidateInterviews',[
                    'Result'   => $result,
                    'Feedback' => $feedback
                 ]);


    $app = $this->db
        ->select('CurrentStage, StageId')
        ->where('ApplicationId', $applicationId)
        ->get('jobapplications')
        ->row();

    $stageIdToUse = '';

    if($app){
        if(!empty($app->CurrentStage)){
            $stageIdToUse = $app->CurrentStage;
        } elseif(!empty($app->StageId)){
            $stageIdToUse = $app->StageId;
        }
    }

    $stageName = '';

    if(!empty($stageIdToUse)){
        $stageRow = $this->db
            ->where('StageId', $stageIdToUse)
            ->get('RecruitmentStages')
            ->row();

        if($stageRow){
            $stageName = $stageRow->StageName;
        }
    }

    if(empty($stageName)){
        $statusToSave = ucwords(strtolower($result));
    } else {
        $statusToSave = $stageName . ' Round Completed - ' . ucwords(strtolower($result));
    }

       
        $this->db->where('ApplicationId',$applicationId)
                 ->update('JobApplications',[   
                    'CurrentStatus' => $statusToSave
                 ]);

       
        $this->load->model('Notification_model');
        $notifTitle = 'Interview Result Submitted';
        $notifMsg = "Interview result for Application ID $applicationId has been submitted: $result.";
        
        $this->Notification_model->addNotification($notifTitle, $notifMsg, 'success', null, 1);
        $this->Notification_model->addNotification($notifTitle, $notifMsg, 'success', null, 2);
       

        echo json_encode([
            'status' => 'success',
            'saved_status' => $statusToSave
        ]);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function vacancies()
{
   
    $Hrms_Session = $this->session->userdata('logged_in');

	if (isset($Hrms_Session) && !empty($Hrms_Session)) {
 
    $this->db->select('jl.*, d.Departmentname');
    $this->db->from('IHRJobsList jl');
    $this->db->join('Departments d','d.Did = jl.Did','left');
 
    $dateRange  = $this->input->post('dateRange', TRUE) ?: $this->input->get('dateRange', TRUE);
    $department = $this->input->post('department', TRUE) ?: $this->input->get('department', TRUE);
    $status     = $this->input->post('status', TRUE) ?: $this->input->get('status', TRUE);
 
   
    if (!empty($dateRange)) {
 
        $dates = explode(' - ', $dateRange);
 
        if (count($dates) == 2) {
            $start = $dates[0];
            $end   = $dates[1];
 
            $this->db->where('DATE(jl.PostedOn) >=', $start);
            $this->db->where('DATE(jl.PostedOn) <=', $end);
        }
    }
 
    
    if (!empty($department)) {
        $this->db->where('d.Departmentname', $department);
    }
 
    
    if (!empty($status)) {
        $this->db->where('jl.JobStatus', $status);
    }
 
    $data['vaclist'] = $this->db->get()->result_array();
    $data['department'] = $this->admin_model->getUserDepartments();
 
    $currentUrl = strtolower(uri_string());
    $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);
 
    $this->template->write_view('content', 'admin/VaccancyList', $data);
    $this->template->render();}
    else{
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function filterAssignedInterviews()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $status = $this->input->post('status');

       $this->db->select('
        ci.InterviewId,
        ci.Result,
        ci.ScheduledAt,
        ja.AppliedOn,
        c.CandidateId,
        c.Fullname,
        c.PhoneNo,
        c.Email,
        c.ProfileMatchPer,
        c.CandidateCode

        ');
        $this->db->from('CandidateInterviews ci');
        $this->db->join('JobApplications ja', 'ja.ApplicationId = ci.ApplicationId');
        $this->db->join('IHrCandidates c', 'c.CandidateId = ja.CandidateId');

        
        $session = $this->session->userdata('logged_in');
        $this->db->where('ci.InterviewerId', $session['IUid']);

    $status = $this->input->post('status');

    if($status == 'Assigned')
    {
        $this->db->group_start();
        $this->db->where('ci.Result', 'Assigned');
        $this->db->or_where('ci.Result IS NULL', null, false);
        $this->db->or_where('ci.Result', '');
        $this->db->group_end();
    }
    elseif($status == 'Selected')
    {
        $this->db->where('ci.Result', 'Selected');
    }
    elseif($status == 'Rejected')
    {
        $this->db->where('ci.Result', 'Rejected');
    }
    else
    {
        
    }


        $data = $this->db->get()->result_array();

        if(empty($data)){
            echo "<tr><td colspan='10' class='text-center'>No Data Found</td></tr>";
            return;
        }

        $i = 1;
        foreach($data as $cl)
        {
            $result = strtolower(trim($cl['Result'] ?? ''));

            echo "<tr>";
            echo "<td>".$i++."</td>";
           echo "<td>
    <a href='".base_url('admin/viewResume/'.$cl['CandidateId'])."'
    target='_blank'
    class='text-warning font-weight-bold'>
    ".$cl['CandidateCode']."
    </a>
    </td>";
    echo "<td>
    <a href='javascript:void(0);'
    class='viewCandidateDetails text-primary font-weight-bold'
    data-id='".$cl['CandidateId']."'>
    ".$cl['Fullname']."
    </a>
    </td>";
            echo "<td>".$cl['PhoneNo']."</td>";
            echo "<td>".$cl['Email']."</td>";
            echo "<td>".$cl['ProfileMatchPer']."</td>";
            $sAt = $cl['ScheduledAt'] ?? '';
            $sTs = (!empty($sAt) && $sAt !== '0000-00-00 00:00:00') ? strtotime($sAt) : 0;
            echo "<td>".($sTs > 0 ? date('d M Y, h:i A', $sTs) : 'Not Scheduled')."</td>";
            echo "<td>".(!empty($cl['Result']) ? $cl['Result'] : 'Assigned')."</td>";
            echo "<td>".$cl['AppliedOn']."</td>";
            echo "<td>";

          if($result == '' || $result == 'assigned' || $result == 'on hold'){

        echo "<button class='btn btn-sm btn-warning openInterviewUpdate'
                data-interview='".$cl['InterviewId']."'>
                <i class='fas fa-edit'></i> Update Status
              </button>";

    } else {

        if($result == 'selected'){
            $badge = 'badge-success';
        } elseif($result == 'rejected'){
            $badge = 'badge-danger';
        } elseif($result == 'on hold'){
            $badge = 'badge-warning';
        } else {
            $badge = 'badge-secondary';
        }

        echo "<span class='badge ".$badge."'>".$cl['Result']."</span>";
    }

            echo "</td>";
            echo "</tr>";
        }

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function filterCandidates()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $status = $this->input->post('status');
        $jid    = $this->input->post('jid');

        $this->db->select('
            c.CandidateId, c.CandidateCode, c.Fullname, c.PhoneNo, c.Email,
            c.ProfileMatchPer, c.ResumePath,
            ja.CurrentStage, ja.CurrentStatus, ja.AppliedOn, ja.Jid,
            rs.StageOrder as CurrentStageOrder,
            (SELECT Action FROM CandidateStageTracking
             WHERE ApplicationId = ja.ApplicationId
             ORDER BY ActionAt DESC LIMIT 1) as LastAction
        ');
        $this->db->from('IHrCandidates c');
        $this->db->join('JobApplications ja', 'ja.CandidateId = c.CandidateId');
        $this->db->join('RecruitmentStages rs', 'rs.StageId = ja.CurrentStage', 'left');
        $this->db->where('ja.Jid', $jid);

        if (!empty($status)) {
        if ($status == 'Selected') {
            $this->db->like('ja.CurrentStatus', 'Selected');
        } elseif ($status == 'Rejected') {
            $this->db->like('ja.CurrentStatus', 'Rejected');
        } elseif ($status == 'On Hold') {
            $this->db->where('ja.CurrentStatus', 'On Hold');
        } elseif ($status == 'In Progress') {
            $this->db->where('ja.CurrentStatus', 'In Progress');
        }
    }

        $data = $this->db->get()->result_array();

        if (empty($data)) {
            echo "<tr><td colspan='9' class='text-center'>No Data Found</td></tr>";
            return;
        }

       $i = 1;
    foreach ($data as $cl) {

        echo "<tr>";

        echo "<td>".$i++."</td>";
      echo "<td>
    <a href='".base_url($cl['ResumePath'])."'
       target='_blank'
       class='text-warning font-weight-bold'>
       ".$cl['CandidateCode']."
    </a>
    </td>";

    echo "<td>
    <a href='javascript:void(0);'
       class='viewCandidateSimple text-primary font-weight-bold'
       data-id='".$cl['CandidateId']."'>
       ".$cl['Fullname']."
    </a>
    </td>";
        echo "<td>".$cl['PhoneNo']."</td>";
        echo "<td>".$cl['Email']."</td>";
        echo "<td>".$cl['ProfileMatchPer']."</td>";
        echo "<td>".$cl['CurrentStatus']."</td>";
        echo "<td>".$cl['AppliedOn']."</td>";

        echo "<td>";
        echo "<div class='btn-group'>";

        echo "<button type='button' class='btn btn-sm btn-success viewCandidateDetails' 
                data-id='".$cl['CandidateId']."'>
                <i class='fas fa-eye'></i>
              </button>";

        echo "<button class='btn btn-sm btn-primary openCandidateStage'
                data-id='".$cl['CandidateId']."'
                data-stage='".($cl['CurrentStageOrder'] ?? 1)."'>
                <i class='fas fa-edit'></i>
              </button>";

       
    $currentStatusLower = strtolower(trim($cl['CurrentStatus'] ?? ''));
    $showOffer = false;
    $showOnboarding = false;
    $showHiring = false;
    $isHired = false;

    if (strpos($currentStatusLower, 'selected') !== false || 
        $currentStatusLower == 'offer pending' || 
        $currentStatusLower == 'offer accepted' || 
        $currentStatusLower == 'offer rejected' ||
        $currentStatusLower == 'offer released') {
        $showOffer = true;
    }

    if ($currentStatusLower == 'offer accepted') {
        $showOnboarding = true;
    }

    if ($currentStatusLower == 'on boarding') {
        $showHiring = true;
    }

    if (strpos($currentStatusLower, 'hired') !== false) {
        $isHired = true;
    }

    if ($showOffer) {
        // 📞 Offer Button
        echo "<button class='btn btn-sm btn-warning openOfferModal'
                data-id='".$cl['CandidateId']."'>
                <i class='fas fa-phone'></i>
              </button>";
    }

    if ($showOnboarding) {
      
        echo "<button class='btn btn-sm btn-info openOnboardingModal'
                data-id='".$cl['CandidateId']."'>
                <i class='fas fa-user-check'></i>
              </button>";
    }

    if ($showHiring) {
       
        echo "<button class='btn btn-sm btn-success openHiringModal'
                data-id='".$cl['CandidateId']."'>
                <i class='fas fa-briefcase'></i>
              </button>";
    }

    if ($isHired) {
        echo "<span class='badge badge-success p-2'>HIRED</span>";
    }

        echo "</div>";
        echo "</td>";

        echo "</tr>";
    }

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}


public function saveOnboarding()
{
   

    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $candidateId = $this->input->post('candidateId');
        $documents   = $this->input->post('documentsSubmitted');
        $remarks     = $this->input->post('remarks');

       
        $app = $this->db->select('ApplicationId')
                        ->from('JobApplications')
                        ->where('CandidateId', $candidateId)
                        ->order_by('ApplicationId','DESC')
                        ->limit(1)
                        ->get()
                        ->row();

        if(!$app){
            echo json_encode(['status'=>'error','msg'=>'Application not found']);
            return;
        }

        $applicationId = $app->ApplicationId;

        $onboardingStage = $this->db
            ->where('StageGroup', 'Hiring')
            ->like('StageName', 'On Boarding')
            ->get('RecruitmentStages')
            ->row();
        $onboardingStageId = $onboardingStage ? $onboardingStage->StageId : 11;

       
        $this->db->insert('CandidateStageTracking', [
            'ApplicationId' => $applicationId,
            'StageId'       => $onboardingStageId,
            'Action'        => 'On Boarding',
            'ActionBy'      => $Hrms_Session['IUid'],
            'ActionAt'      => date('Y-m-d H:i:s'),
            'Remarks'       => "Documents Submitted: " . $documents . " | " . $remarks
        ]);

        
        $this->db->where('ApplicationId', $applicationId)
             ->update('JobApplications', [
                 'CurrentStage'  => $onboardingStageId,
                 'CurrentStatus' => 'On Boarding'
             ]);

        echo json_encode(['status'=>'success']);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function RecruitmentStages()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

       
        $data['stages'] = $this->db
            ->order_by('StageGroup', 'ASC')
            ->order_by('StageOrder', 'ASC')
            ->get('RecruitmentStages')
            ->result_array();

      
        $currentUrl = strtolower(uri_string());
        $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);

   
        $this->template->write_view('content', 'admin/RecruitmentStages', $data);
        $this->template->render();

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function SaveStage()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $stageGroup = $this->input->post('StageGroup');
        $stageName  = $this->input->post('StageName');
        $stageOrder = $this->input->post('StageOrder');

        // Validation
        if (!is_numeric($stageOrder) || (int)$stageOrder <= 0) {
            $this->session->set_flashdata('error', 'Stage Order must be a number greater than 0.');
            redirect('admin/RecruitmentStages');
        }
        $stageOrder = (int)$stageOrder;

      
        $existing = $this->db->where('StageGroup', $stageGroup)
                             ->where('StageOrder', $stageOrder)
                             ->get('RecruitmentStages')
                             ->num_rows();
        if ($existing > 0) {
            $this->db->set('StageOrder', 'StageOrder + 1', FALSE)
                     ->where('StageGroup', $stageGroup)
                     ->where('StageOrder >=', $stageOrder)
                     ->update('RecruitmentStages');
        }

        $data = [
            'StageGroup'  => $stageGroup,
            'StageName'   => $stageName,
            'StageOrder'  => $stageOrder,
            'StageStatus' => 1,
            'IsFinal'     => 0,
            'CreatedAT'   => date('Y-m-d H:i:s')
        ];

        $this->db->insert('RecruitmentStages',$data);

        $this->session->set_flashdata('success', 'Recruitment stage added successfully.');
        redirect($this->config->item('base_url').'admin/RecruitmentStages');

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function UpdateStage()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $stageId    = $this->input->post('StageId');
        $stageGroup = $this->input->post('StageGroup');
        $stageName  = $this->input->post('StageName');
        $stageOrder = $this->input->post('StageOrder');

        // Validation
        if (!is_numeric($stageOrder) || (int)$stageOrder <= 0) {
            $this->session->set_flashdata('error', 'Stage Order must be a number greater than 0.');
            redirect('admin/RecruitmentStages');
        }
        $stageOrder = (int)$stageOrder;

        
        $existing = $this->db->where('StageGroup', $stageGroup)
                             ->where('StageOrder', $stageOrder)
                             ->where('StageId !=', $stageId)
                             ->get('RecruitmentStages')
                             ->num_rows();
        if ($existing > 0) {
            $this->db->set('StageOrder', 'StageOrder + 1', FALSE)
                     ->where('StageGroup', $stageGroup)
                     ->where('StageOrder >=', $stageOrder)
                     ->where('StageId !=', $stageId)
                     ->update('RecruitmentStages');
        }

        $data = [
            'StageGroup' => $stageGroup,
            'StageName'  => $stageName,
            'StageOrder' => $stageOrder
        ];

        $this->db->where('StageId',$stageId)
                 ->update('RecruitmentStages',$data);

        $this->session->set_flashdata('success', 'Recruitment stage updated successfully.');
        redirect('admin/RecruitmentStages');

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function getNextStageOrder()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $group = $this->input->post('StageGroup');
        $row = $this->db->select_max('StageOrder')
                        ->where('StageGroup', $group)
                        ->get('RecruitmentStages')
                        ->row();
        
        $maxOrder = $row ? (int)$row->StageOrder : 0;
        echo json_encode(['status' => 'success', 'nextOrder' => $maxOrder + 1]);
    }
    else
    {
        echo json_encode(['status' => 'error', 'msg' => 'Invalid session']);
    }
}

public function ChangeStageStatus($id,$action)
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $status = ($action == 'activate') ? 1 : 0;

        $this->db->where('StageId',$id)
                 ->update('RecruitmentStages',[
                    'StageStatus' => $status
                 ]);

        $msg = ($action == 'activate')
            ? 'Recruitment stage activated successfully.'
            : 'Recruitment stage deactivated successfully.';
        $this->session->set_flashdata('success', $msg);
        redirect($this->config->item('base_url').'admin/RecruitmentStages');

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function getRolePermissions()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $roleId = $this->input->post('roleId');

        $menus = $this->db
            ->select('IHMid')
            ->where('Erid',   $roleId)
            ->where('Status', 1)
            ->get('IHRolePermissions')
            ->result_array();

        $menuIds = array_column($menus, 'IHMid');

        echo json_encode($menuIds);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}


public function RolePermissions()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $data['roles'] = $this->db
            ->where('Status',1)
            ->get('EmpRoles')
            ->result_array();

        $data['menus'] = $this->db
            ->where('MenuStatus',1)
            ->get('IHMenus')
            ->result_array();

        // 🔥 Get selected role from URL
        $data['selectedRole'] = $this->input->get('role');

        $this->template->write_view('content', 'admin/role_permissions', $data);
        $this->template->render();
    }

    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function saveRolePermissions()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $roleId = $this->input->post('roleId');
        $menus  = $this->input->post('menus');

        if(empty($roleId)){
            echo json_encode(['status' => 'error', 'msg' => 'Role ID missing']);
            return;
        }

        // GET ALL MENUS
        $allMenus = $this->db->select('IHMid')->get('IHMenus')->result_array();

        foreach($allMenus as $menu){
            $menuId     = $menu['IHMid'];
            $isSelected = (!empty($menus) && in_array($menuId, $menus)) ? 1 : 0;

            // CHECK IF RECORD EXISTS
            $exists = $this->db
                ->where('Erid',  $roleId)
                ->where('IHMid', $menuId)
                ->get('IHRolePermissions')
                ->row();

            if($exists){
                // UPDATE STATUS ONLY
                $this->db->where('Erid',  $roleId)
                         ->where('IHMid', $menuId)
                         ->update('IHRolePermissions', [
                             'Status'    => $isSelected,
                             'UpdatedAT' => date('Y-m-d H:i:s')
                         ]);
            } else {
                // INSERT FRESH
                $this->db->insert('IHRolePermissions', [
                    'Erid'      => $roleId,
                    'IHMid'     => $menuId,
                    'Status'    => $isSelected,
                    'CreatedAT' => date('Y-m-d H:i:s'),
                    'UpdatedAT' => date('Y-m-d H:i:s')
                ]);
            }
        }

        echo json_encode(['status' => 'success', 'msg' => 'Saved']);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
public function saveOffer()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $candidateId = $this->input->post('candidateId');
        $offerDate   = $this->input->post('offerDate');
        $noticeDays  = $this->input->post('noticeDays');
        $remarks     = $this->input->post('remarks');
        $offerStatus = $this->input->post('offerStatus');

        // 🔹 Get ApplicationId
        $app = $this->db->select('ApplicationId')
                        ->from('JobApplications')
                        ->where('CandidateId',$candidateId)
                        ->order_by('ApplicationId','DESC')
                        ->limit(1)
                        ->get()
                        ->row();

        if(!$app){
            echo json_encode(['status'=>'error','msg'=>'Application not found']);
            return;
        }

        $applicationId = $app->ApplicationId;

     
        $expectedJoining = date('Y-m-d', strtotime($offerDate.' +'.$noticeDays.' days'));

       

    $this->db->insert('CandidateOffers',[
        'ApplicationId'      => $applicationId,
        'OfferDate'          => $offerDate,
        'NoticePeriodDays'   => $noticeDays,
        'ExpectedJoiningDate'=> $expectedJoining,
        'OfferStatus'        => $offerStatus,
        'OfferActionAt'      => date('Y-m-d H:i:s')
    ]);

        $offerStage = $this->db
            ->where('StageGroup', 'Offer')
            ->get('RecruitmentStages')
            ->row();
        $offerStageId = $offerStage ? $offerStage->StageId : 12;

       
        $this->db->insert('CandidateStageTracking',[
            'ApplicationId' => $applicationId,
            'StageId'       => $offerStageId,
            'Action'        => 'Offer',
            'ActionBy'      => $Hrms_Session['IUid'],
            'ActionAt'      => date('Y-m-d H:i:s'),
            'Remarks'       => $remarks
        ]);

       
        $this->db->where('ApplicationId',$applicationId)
             ->update('JobApplications',[
                 'CurrentStage'  => $offerStageId,
                 'CurrentStatus' => 'Offer ' . $offerStatus
             ]);



            
    $candidate = $this->db
        ->where('CandidateId',$candidateId)
        ->get('IHRCandidates')
        ->row();

    if(empty($candidate) || empty($candidate->Email)){
        echo json_encode(['status'=>'error','msg'=>'Candidate email missing']);
        return;
    }

  
    $data['candidatelist'] = $candidate;
    $data['action'] = 'offer';  

    try {

   
    $to = $candidate->Email;

        $subject = "Offer Update - I-Net Secure Labs Pvt Ltd.";

        require(APPPATH.'libraries/InetMailer.php');
        $objs = new InetMailer();
        $mail = $objs->load();

        $mail->setFrom('info@inetcsc.com', 'I-NET CSC');
        $mail->addAddress(trim($to));

        $mail->isHTML(true);
        $mail->Subject = $subject;

        
        $mail->Body = $this->load->view('admin/CandisateEmail',$data,TRUE);

       if(!$mail->send()){
        echo json_encode([
            'status'=>'error',
            'msg'=>$mail->ErrorInfo
        ]);
        return;
    }

    } catch (\Exception $e) {
        
    }
        echo json_encode(['status'=>'success']);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
 

public function saveHiring()
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $userId = $Hrms_Session['IUid'] ?? 1;

        $candidateId = $this->input->post('candidateId');
        $joiningDate = $this->input->post('joiningDate');
        $salary      = $this->input->post('salaryOffered');
        $remarks     = $this->input->post('remarks');

      

        $app = $this->db->select('ApplicationId')
                        ->from('JobApplications')
                        ->where('CandidateId', $candidateId)
                        ->order_by('ApplicationId', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row();

        if(!$app){
            echo "Application not found";
            return;
        }

    

        $hiredStage = $this->db
            ->where('StageGroup', 'Hiring')
            ->like('StageName', 'Hired')
            ->get('RecruitmentStages')
            ->row();
        $hiredStageId = $hiredStage ? $hiredStage->StageId : 13;

        $this->db->insert('CandidateStageTracking', [

            'ApplicationId' => $app->ApplicationId,
            'StageId'       => $hiredStageId,
            'Action'        => 'Hired',
            'ActionBy'      => $userId,
            'Remarks'       => 'Candidate successfully hired',
            'ActionAt'      => date('Y-m-d H:i:s')

        ]);

       

        $this->db->insert('CandidateHiring', [

            'ApplicationId' => $app->ApplicationId,
            'CandidateId'   => $candidateId,
            'HiringDate'    => date('Y-m-d'),
            'JoiningDate'   => $joiningDate,
            'SalaryOffered' => $salary,
            'Remarks'       => $remarks,
            'CreatedBy'     => $userId,
            'CreatedAt'     => date('Y-m-d H:i:s')

        ]);

       

        $this->db->where('ApplicationId', $app->ApplicationId);

        $this->db->update('JobApplications', [

            'CurrentStage'  => $hiredStageId,
            'CurrentStatus' => 'Hired'

        ]);

        echo "success";
    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}
private function getApplicationId($candidateId)
{
    $app = $this->db->select('ApplicationId')
                    ->from('JobApplications')
                    ->where('CandidateId',$candidateId)
                    ->order_by('ApplicationId','DESC')
                    ->limit(1)
                    ->get()
                    ->row();

    return $app ? $app->ApplicationId : null;
}

public function viewResume($candidateId)
{
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $candidate = $this->db
            ->where('CandidateId',$candidateId)
            ->get('IHrCandidates')   
            ->row_array();

        if(!empty($candidate['ResumePath']))
        {
            redirect(base_url($candidate['ResumePath']));
        }
        else
        {
            echo "Resume not found";
        }

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function logout()
{
    $this->session->sess_destroy();
    redirect('admin/index');
}



public function get_notifications() {
    $this->load->model('Notification_model');
    $Hrms_Session = $this->session->userdata('logged_in');
    
    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {

        $userId = $Hrms_Session['IUid'];
        $roleId = $Hrms_Session['EmpRoleId'];

        $notifications = $this->Notification_model->getUnreadNotifications($userId, $roleId);
        $count = count($notifications);

        echo json_encode([
            'status' => 'success',
            'count' => $count,
            'data' => $notifications
        ]);

    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function mark_notification_read() {
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $this->load->model('Notification_model');
        $nid = $this->input->post('notification_id');
        
        if ($Hrms_Session && $nid) {
            $this->Notification_model->markAsRead($nid, $Hrms_Session['IUid']);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}

public function mark_all_notifications_read() {
    $Hrms_Session = $this->session->userdata('logged_in');

    if(isset($Hrms_Session) && !empty($Hrms_Session))
    {
        $this->load->model('Notification_model');
        $this->Notification_model->markAllAsRead($Hrms_Session['IUid'], $Hrms_Session['EmpRoleId']);
        echo json_encode(['status' => 'success']);
    }
    else
    {
        $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
        redirect($this->config->item('base_url')."admin/index");
    }
}



    // --- RESOURCE REQUEST & APPROVAL FLOW METHODS ---

    public function RequestedResources()
    {
        $check_session = $this->session->userdata("logged_in");
        if (empty($check_session)) {
            redirect($this->config->item("base_url") . "admin/index");
            return;
        }

        $roleId = isset($check_session["EmpRoleId"]) ? $check_session["EmpRoleId"] : null;
        $userId = isset($check_session["IUid"]) ? $check_session["IUid"] : null;

        // Fetch role details
        $roleRow = $this->db->select("RoleName")->from("emproles")->where("Erid", $roleId)->get()->row_array();
        $roleName = !empty($roleRow) ? strtolower($roleRow["RoleName"]) : "";

        $filters = [];
        // Role-based filtering
        if ($roleName === "hiring manager") {
            $filters["RequestedBy"] = $userId;
        } else if ($roleName === "approver") {
            $filters["ApproverId"] = $userId;
        }

        $data["employee_det"] = $check_session;
        $data["requests"] = $this->admin_model->getResourceRequests($filters);
        $data["approvers"] = $this->admin_model->getApproverUsers();
        $data["department"] = $this->db->select("Did, Departmentname")->from("departments")->where("Status", 1)->get()->result_array();
        $data["userRoleName"] = !empty($roleRow) ? $roleRow["RoleName"] : "";

        $this->template->set_master_template("../../themes/" . $this->config->item("active_template") . "/bo_template.php");
        $this->template->write_view("content", "admin/RequestedResources", $data);
        $this->template->render();
    }

    public function saveResourceRequest()
    {
        $check_session = $this->session->userdata("logged_in");
        if (empty($check_session)) {
            redirect($this->config->item("base_url") . "admin/index");
            return;
        }

        $inps = $this->input->post();
        if (empty($inps["JobTitle"]) || empty($inps["ApproverId"])) {
            $this->session->set_flashdata("error", "Job Title and Approver Name are required.");
            redirect($this->config->item("base_url") . "admin/RequestedResources");
            return;
        }

        // Generate Request Code
        $count = $this->db->count_all("resource_requests") + 1;
        $requestCode = "RR-" . date("Y") . "-" . str_pad($count, 4, "0", STR_PAD_LEFT);

        $data = [
            "RequestCode"          => $requestCode,
            "JobTitle"             => trim($inps["JobTitle"]),
            "FunctionalRole"       => isset($inps["FunctionalRole"]) ? trim($inps["FunctionalRole"]) : "",
            "Did"                  => isset($inps["Did"]) ? (int)$inps["Did"] : null,
            "NoofOpenings"         => isset($inps["NoofOpenings"]) ? (int)$inps["NoofOpenings"] : 1,
            "PositionType"         => isset($inps["PositionType"]) ? $inps["PositionType"] : "New Position",
            "ExpMin"               => isset($inps["ExpMin"]) ? (int)$inps["ExpMin"] : 0,
            "ExpMax"               => isset($inps["ExpMax"]) ? (int)$inps["ExpMax"] : 0,
            "SalMin"               => isset($inps["SalMin"]) ? (int)$inps["SalMin"] : 0,
            "SalMax"               => isset($inps["SalMax"]) ? (int)$inps["SalMax"] : 0,
            "RecruitmentStartDate" => !empty($inps["RecruitmentStartDate"]) ? $inps["RecruitmentStartDate"] : null,
            "TargetOnboardingDate" => !empty($inps["TargetOnboardingDate"]) ? $inps["TargetOnboardingDate"] : null,
            "ReasonForRequirement" => isset($inps["ReasonForRequirement"]) ? trim($inps["ReasonForRequirement"]) : "",
            "JobDescription"       => isset($inps["JobDescription"]) ? trim($inps["JobDescription"]) : "",
            "Responsibilities"     => isset($inps["Responsibilities"]) ? trim($inps["Responsibilities"]) : "",
            "RequestedBy"          => $check_session["IUid"],
            "ApproverId"           => (int)$inps["ApproverId"],
            "Status"               => "PENDING APPROVAL",
            "CreatedAt"            => date("Y-m-d H:i:s")
        ];

        $requestId = $this->admin_model->insertResourceRequest($data);
        if ($requestId) {
            // Trigger Email to Approver
            $this->_sendResourceRequestEmailToApprover($requestId);
            $this->session->set_flashdata("true", "Resource Request submitted successfully and sent for approval.");
        } else {
            $this->session->set_flashdata("error", "Failed to submit Resource Request.");
        }

        redirect($this->config->item("base_url") . "admin/RequestedResources");
    }

    public function updateResourceRequestStatus()
    {
        $check_session = $this->session->userdata("logged_in");
        if (empty($check_session)) {
            echo json_encode(["status" => "error", "message" => "Session expired"]);
            return;
        }

        $inps = $this->input->post();
        $requestId = isset($inps["RequestId"]) ? (int)$inps["RequestId"] : 0;
        $status = isset($inps["Status"]) ? strtoupper(trim($inps["Status"])) : "";
        $comment = isset($inps["ApprovalComment"]) ? trim($inps["ApprovalComment"]) : "";

        if (!$requestId || !in_array($status, ["ACCEPTED", "REJECTED"])) {
            echo json_encode(["status" => "error", "message" => "Invalid request parameter."]);
            return;
        }

        if (empty($comment)) {
            echo json_encode(["status" => "error", "message" => "Approval Comments are mandatory."]);
            return;
        }

        $updateData = [
            "Status"          => $status,
            "ApprovalComment" => $comment,
            "ActionedAt"      => date("Y-m-d H:i:s")
        ];

        $res = $this->admin_model->updateResourceRequest($requestId, $updateData);
        if ($res) {
            if ($status === "ACCEPTED") {
                $this->_sendResourceRequestAcceptEmail($requestId);
            } else if ($status === "REJECTED") {
                $this->_sendResourceRequestRejectEmail($requestId);
            }
            echo json_encode(["status" => "success", "message" => "Resource Request has been " . strtolower($status) . " successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update Resource Request."]);
        }
    }

    public function convertRequestToVacancy($requestId)
    {
        $check_session = $this->session->userdata("logged_in");
        if (empty($check_session)) {
            redirect($this->config->item("base_url") . "admin/index");
            return;
        }

        $req = $this->admin_model->getResourceRequestById((int)$requestId);
        if (empty($req) || $req["Status"] !== "ACCEPTED") {
            $this->session->set_flashdata("error", "Only ACCEPTED resource requests can be converted to Vacancies.");
            redirect($this->config->item("base_url") . "admin/RequestedResources");
            return;
        }

        // Generate Job Code
        $count = $this->db->count_all("ihrjobslist") + 1;
        $jobCode = "JOB-" . date("Y") . "-" . str_pad($count, 4, "0", STR_PAD_LEFT);

        $vacancyData = [
            "JobCode"               => $jobCode,
            "JobTitle"              => $req["JobTitle"],
            "RoleSummary"           => $req["FunctionalRole"],
            "Did"                   => $req["Did"],
            "EmploymentType"        => "Full-Time",
            "WorkMode"              => "Onsite",
            "EducationRequired"     => "Bachelor Degree",
            "ExpMin"                => $req["ExpMin"],
            "ExpMax"                => $req["ExpMax"],
            "SalMin"                => $req["SalMin"],
            "SalMax"                => $req["SalMax"],
            "NoofOpenings"          => $req["NoofOpenings"],
            "JobStatus"             => "Open",
            "JobDescription"        => $req["JobDescription"],
            "Responsibilities"      => $req["Responsibilities"],
            "PostedBy"              => $check_session["IUid"],
            "PostedOn"              => date("Y-m-d H:i:s")
        ];

        $this->db->insert("ihrjobslist", $vacancyData);
        $jid = $this->db->insert_id();

        if ($jid) {
            $this->admin_model->updateResourceRequest((int)$requestId, ["ConvertedJid" => $jid]);
            $this->session->set_flashdata("true", "Vacancy created successfully from approved Resource Request (" . $req["RequestCode"] . ").");
            redirect($this->config->item("base_url") . "admin/VaccancyList");
        } else {
            $this->session->set_flashdata("error", "Failed to convert Resource Request to Vacancy.");
            redirect($this->config->item("base_url") . "admin/RequestedResources");
        }
    }

    // --- EMAIL NOTIFICATION HELPERS ---

    private function _sendResourceRequestEmailToApprover($requestId)
    {
        $req = $this->admin_model->getResourceRequestById($requestId);
        if (empty($req) || empty($req["ApproverEmail"])) return false;

        $subject = "Action Required: Resource Request [" . $req["RequestCode"] . "] - " . $req["JobTitle"];
        $message = "Dear " . $req["ApproverName"] . ",\n\n";
        $message .= "A new Resource Request requires your approval:\n\n";
        $message .= "Request Code: " . $req["RequestCode"] . "\n";
        $message .= "Job Title: " . $req["JobTitle"] . "\n";
        $message .= "Functional Role: " . $req["FunctionalRole"] . "\n";
        $message .= "Department: " . $req["Departmentname"] . "\n";
        $message .= "Openings: " . $req["NoofOpenings"] . "\n";
        $message .= "Requested By: " . $req["RequestedByName"] . "\n";
        $message .= "Start Date: " . $req["RecruitmentStartDate"] . "\n";
        $message .= "Target Onboarding: " . $req["TargetOnboardingDate"] . "\n\n";
        $message .= "Please log in to the HRMS portal to review and approve/reject this request.\n";

        return @mail($req["ApproverEmail"], $subject, $message);
    }

    private function _sendResourceRequestAcceptEmail($requestId)
    {
        $req = $this->admin_model->getResourceRequestById($requestId);
        if (empty($req)) return false;

        $subject = "Resource Request ACCEPTED [" . $req["RequestCode"] . "] - " . $req["JobTitle"];
        $message = "Dear " . $req["RequestedByName"] . ",\n\n";
        $message .= "Your Resource Request has been ACCEPTED by " . $req["ApproverName"] . ".\n\n";
        $message .= "Request Code: " . $req["RequestCode"] . "\n";
        $message .= "Job Title: " . $req["JobTitle"] . "\n";
        $message .= "Approval Date: " . $req["ActionedAt"] . "\n";
        $message .= "Approver Comment: " . $req["ApprovalComment"] . "\n\n";
        $message .= "The Recruitment Manager can now proceed to convert this request into an active vacancy.\n";

        if (!empty($req["RequestedByEmail"])) {
            @mail($req["RequestedByEmail"], $subject, $message);
        }

        // Notify Recruitment Manager
        $recManagers = $this->db->select("u.EmpEmail")
            ->from("IHUsers u")
            ->join("emproles r", "u.Erid = r.Erid")
            ->where("LOWER(r.RoleName)", "recruitment manager")
            ->get()->result_array();

        foreach ($recManagers as $rm) {
            if (!empty($rm["EmpEmail"])) {
                @mail($rm["EmpEmail"], $subject, $message);
            }
        }
    }

    private function _sendResourceRequestRejectEmail($requestId)
    {
        $req = $this->admin_model->getResourceRequestById($requestId);
        if (empty($req) || empty($req["RequestedByEmail"])) return false;

        $subject = "Resource Request REJECTED [" . $req["RequestCode"] . "] - " . $req["JobTitle"];
        $message = "Dear " . $req["RequestedByName"] . ",\n\n";
        $message .= "Your Resource Request has been REJECTED by " . $req["ApproverName"] . ".\n\n";
        $message .= "Request Code: " . $req["RequestCode"] . "\n";
        $message .= "Job Title: " . $req["JobTitle"] . "\n";
        $message .= "Rejection Date: " . $req["ActionedAt"] . "\n";
        $message .= "Rejection Comment: " . $req["ApprovalComment"] . "\n";

        return @mail($req["RequestedByEmail"], $subject, $message);
    }


}