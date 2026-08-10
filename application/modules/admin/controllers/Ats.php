<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Ats extends MX_Controller
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
        $this->load->library('ATS_Engine');
        $this->load->library('PdfTextExtractor');
 
        $this->load->library('encrypt');
        $this->load->library('user_agent');
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('admin/admin_model');
 
 
 
        $roleId = $this->session->userdata('EmpRoleId'); 
        
        $menus = $this->admin_model->getMenusByRole($check_session['EmpRoleId']);
       
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
 
public function analyzeResumeModal()
    {
 
        $Hrms_Session=$this->session->userdata('logged_in');  
        
        if(isset($Hrms_Session) && !empty($Hrms_Session))
        {  
            
        $currentUrl = strtolower(uri_string());
 
        
         $data['currentUrlArray'] = $this->admin_model->getBreadcrumb($currentUrl);
         
            header('Content-Type: application/json');
 
            $job_id = $this->input->post('job_id');
     
            if (!$job_id) {
                echo json_encode(['status'=>'error','message'=>'Job ID missing']);
                return;
            }
 
          
            $vacancy = $this->db->select("jl.*,GROUP_CONCAT(s.SkillName ORDER BY s.SkillName SEPARATOR ', ') AS RequiredSkills", false)->from('IHRJobsList jl')
            ->join('JobSkills js', 'js.Jid = jl.Jid', 'left')
            ->join('IHSkills s', 's.SkillId = js.SkillId', 'left')
            ->where('jl.Jid', $job_id)
            ->group_by('jl.Jid')
            ->get()
            ->row_array();
           
             $this->logStage('vacancy',  $this->db->last_query());
 
            if (!$vacancy) {
                echo json_encode(['status'=>'error','message'=>'Job not found']);
                return;
            }
 
            $inpsCanfile = '';
             if(isset($_FILES['resume']) && !empty($_FILES['resume']))
                  {
 
                    $this->load->helper('text');
                   
                    log_message('error', $_FILES['resume']);
                  
                    $config1['upload_path'] = './atscheck/modal';
                    $config1['allowed_types'] = '*';
                    $config1['max_size']  = '200000';
                    $config1['overwrite'] = true;
                    $this->load->library('upload', $config1);
                    $this->upload->initialize($config1);
                   
                    if(isset($_FILES) && !empty($_FILES))
                    {
                      $folder_n = "CAND".$job_id.'_'.date('YmdHis') . rand(100, 999);
                      $upload_files = $_FILES;
                      if($upload_files['resume']['tmp_name']!='')
                      {
                        $temp = explode('.',$upload_files['resume']['name']);
                        $temp_cnt = count($temp)-1;
                        $upload_files['resume']['name']=$folder_n.".".$temp[$temp_cnt];
                        $_FILES['resume'] = array(
                        'name' => $upload_files['resume']['name'],
                        'type' => $upload_files['resume']['type'],
                        'tmp_name' => $upload_files['resume']['tmp_name'],
                        'error' => $upload_files['resume']['error'],
                        'size' => '2000000'
                        );
         
                       $this->upload->do_upload('resume');
                       $uploadData = $this->upload->data();
                       
 
                      }
                    }
 
                   $inpsCanfile = $upload_files['resume']['name'];
                   log_message('error File name', $inpsCanfile);
                }
 
                $candidateName = ucwords(
                    str_replace('_', ' ', pathinfo($uploadData['client_name'], PATHINFO_FILENAME))
                );
 
               
 
                $result = $this->ats_engine->processResume(
                    $uploadData['full_path'],
                    $vacancy
                );
                    
                    $this->logStage('vacancy', $vacancy);
                    $this->logStage('FINAL RESULT RETURN', $result);
 
             
                $atsStatus = $result['status'];
 
                
 
                $appStage = $this->db
                    ->where('StageGroup', 'Application')
                    ->order_by('StageOrder', 'ASC')
                    ->limit(1)
                    ->get('RecruitmentStages')
                    ->row_array();
 
                if (!$appStage) {
                    echo json_encode(['status' => 'error', 'message' => 'Recruitment stage not configured']);
                    return;
                }

               

                $existingCandidate = $this->db
                    ->where('Jid', $vacancy['Jid'])
                    ->where('PhoneNo', $result['mobileNumbers'])
                    ->get('IHrCandidates')
                    ->row_array();

                if ($existingCandidate) {
                    echo json_encode([
                        'status'  => 'error',
                        'message' => 'This resume is already screened'
                    ]);
                    return;
                }
 
               
 
                $this->db->trans_begin();
 
                
 
                $this->db->insert('IHrCandidates', [
                    'CandidateCode'   => 'CAND-' .$vacancy['Jid'].'-'. date('Ymd') . rand(100, 999),
                    'Jid'             => $vacancy['Jid'],
                    'JobCode'         => $vacancy['JobCode'],
                    'Fullname'        => $result['name'],
                    'Email'           => $result['email'],
                    'PhoneNo'         => $result['mobileNumbers'],
                    'ExpYrs'          => $result['expyrs'],
                     'ExperienceDetails' => json_encode($result['experience_details']),
                     'DomainBreakdown' => $result['domain'] ?? 'General',
                    'ResumePath'      => 'atscheck/modal/' . $uploadData['file_name'],
                    'Source'          => 'Upload',
                    'ProfileMatchPer' => $result['score'],
                    'ATS_Status'      => $atsStatus,
                    'ATS_Stage'       => 1,
                    'MatchedSkills'   => $result['matched_skills'] ?? '',
                    'EducationMatch'  => $result['education_match'],
                    'ExperienceMatch' => $result['experience'],
                    'ScoreBreakdown'  => json_encode($result['score_breakdown']),
                    'VerifiedBy'      => $Hrms_Session['IUid'],
                   
                    'VerifiedAt'      => date('Y-m-d H:i:s')
                ]);
 
                $candidateId = $this->db->insert_id();
 
               
 
                $this->db->insert('JobApplications', [
                    'Jid'           => $vacancy['Jid'],
                    'CandidateId'   => $candidateId,
                    'CurrentStage'  => $appStage['StageName'],
                    'CurrentStatus' => ($atsStatus === 'Rejected') ? 'Rejected' : 'In Progress'
                ]);
 
                $applicationId = $this->db->insert_id();
 
                
 
                $this->db->insert('CandidateStageTracking', [
                    'ApplicationId' => $applicationId,
                    'StageId'       => $appStage['StageId'],
                    'Action'        => 'Created',
                    'ActionBy'      => $Hrms_Session['IUid'],
                    'Remarks'       => 'Resume uploaded & ATS auto screening completed'
                ]);
 
              
 
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo json_encode([
                        'status'  => 'error',
                        'message' => 'Database transaction failed'
                    ]);
                    return;
                }
 
                $this->db->trans_commit();
 
               
 
                echo json_encode([
                    'status' => 'success',
                    'data'   => [
                        'name'   => $candidateName,
                        'score'  => $result['score'],
                        'status' => $atsStatus
                    ],
                    'redirect' => base_url('admin/CandidateList/' . $vacancy['Jid'])
                ]);  
            } else {
 
                $this->session->set_flashdata('error','Invalid Session.Please Login Again..!!');
                redirect($this->config->item('base_url')."admin/index");
            }
    }
 
 
    private function logStage($stage, $data = null)
    {
        $log  = "\n============================\n";
        $log .= date('Y-m-d H:i:s') . " | " . $stage . "\n";
        if ($data !== null) {
            $log .= print_r($data, true);
        }
        $log .= "\n============================\n";
 
        file_put_contents(APPPATH.'logs/ats_debug.log', $log, FILE_APPEND);
    }
 
}
 
 
 