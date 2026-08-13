<?php
    $employee_det = $this->session->userdata('logged_in');
         
     if(empty($employee_det)) { redirect($this->config->item('base_url').'admin/index'); }
    $theme_path = $this->config->item('theme_locations').$this->config->item('active_template');
    if(empty($jobdetails)) { $jobdetails = array('Jid' => '', 'JobCode' => ''); }

?>


 <section class="content">
  <div class="container-fluid">

    <div class="card card-warning card-outline">

    
      <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

          <h3 class="card-title mb-0">
            Candidate Details

            <a href="javascript:void(0)"
               class="viewVacancyBtn badge badge-pill badge-warning text-dark ml-2"
               data-id="<?= $jobdetails['Jid']; ?>">
               <?= $jobdetails['JobCode']; ?>
            </a>
          </h3>

          <!-- Breadcrumb aligned right -->
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
              <a href="<?= base_url('admin/dashboard'); ?>">
                Dashboard
              </a>
            </li>

            <li class="breadcrumb-item">
              <a href="<?= base_url('admin/VaccancyList'); ?>">
                Vacancy List
              </a>
            </li>

            <li class="breadcrumb-item active">
              Candidate Details
            </li>
          </ol>

        </div>

      </div>
     


     
      <div class="card-body pb-0">

        <ul class="nav nav-pills nav-pills-sm nav-justified mb-3">

          <li class="nav-item">
            <a class="nav-link active filterPill rounded-pill" data-status="">
              All
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link filterPill rounded-pill" data-status="CV Uploaded">
              CV Uploaded
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link filterPill rounded-pill" data-status="Selected">
              Selected
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link filterPill rounded-pill" data-status="In Progress">
              In Progress
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link filterPill rounded-pill" data-status="On Hold">
              On Hold
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link filterPill rounded-pill" data-status="Rejected">
              Rejected
            </a>
          </li>

        </ul>

      </div>
    


              <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                  <thead class="bg-success text-white">
                  <tr>
                    <th>S.No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Mobile No</th>
                    <th>Email</th>
                    <th>Score</th> 
                    <th>Current Status</th>
                    <th>Verified On</th>
                    <th>Action</th>
                   </tr>
                  </thead>
                                <tbody>
                            <?php
                            
                         

                            if (isset($Candidatelist) && !empty($Candidatelist)) {
                                $i = 1;
                                foreach ($Candidatelist as $cl) {
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                               <td>
<!-- <a href="javascript:void(0)"
   class="text-warning font-weight-bold viewVacancyBtn"
   data-id="<?= $cl['Jid'] ?>">
   <?= $cl['CandidateCode'] ?>
</a> -->
 
<a href="<?= base_url($cl['ResumePath']); ?>"
   target="_blank"
   class="text-warning font-weight-bold">
   <?= $cl['CandidateCode'] ?>
</a>
</td>
                                    <td>
<a href="javascript:void(0);"
   class="viewCandidateSimple text-primary font-weight-bold"
   data-id="<?= $cl['CandidateId']; ?>">
   <?= $cl['Fullname']; ?>
</a>
</td>

                                    <td><?= $cl['PhoneNo'] ?></td>
                                    <td><?= $cl['Email'] ?></td> 
                                          <td>
                                         <?= $cl['ProfileMatchPer'] ?? 0 ?>%
                                         <button type="button"
                                             class="btn btn-xs btn-outline-info btnScoreHelp d-block mt-1"
                                             title="View Score Breakdown"
                                             data-score="<?= htmlspecialchars($cl['ProfileMatchPer'] ?? 0) ?>"
                                             data-breakdown='<?= htmlspecialchars(json_encode(is_string($cl['ScoreBreakdown'] ?? null) ? json_decode($cl['ScoreBreakdown'] ?? '{}', true) : ($cl['ScoreBreakdown'] ?? []))) ?>'
                                             data-skills="<?= htmlspecialchars($cl['MatchedSkills'] ?? '') ?>"
                                             data-edu="<?= htmlspecialchars($cl['EducationMatch'] ?? 'No') ?>"
                                             data-exp="<?= htmlspecialchars($cl['ExperienceMatch'] ?? 'No') ?>"
                                             data-jobskills="<?= htmlspecialchars($cl['JobSkills'] ?? '') ?>">
                                             <i class="fas fa-question-circle"></i> Help
                                         </button>
                                     </td>
                                    <td><?= $cl['CurrentStatus'] ?></td>
                                    <td><?= $cl['AppliedOn'] ?></td>  
                                    <td>

<div class="btn-group" role="group">

<!-- View -->
<!-- <button type="button"
        class="btn btn-sm btn-success viewCandidateDetails"
        data-id="<?= $cl['CandidateId']; ?>">""
        title="View Candidate">
<i class="fas fa-eye"></i>
</button> -->
<button type="button"
        class="btn btn-sm btn-success viewCandidateDetails"
        data-id="<?= $cl['CandidateId']; ?>"
        title="View Candidate">
<i class="fas fa-eye"></i>
</button>


<!-- <button class="btn btn-sm btn-primary openCandidateStage"
        data-id="<?= $cl['CandidateId']; ?>"
        data-stage="<?= $cl['CurrentStageOrder'] ?? 1 ?>">
    <i class="fas fa-edit"></i>
</button> -->


<button class="btn btn-sm btn-primary openCandidateStage"
        data-id="<?= $cl['CandidateId']; ?>"
        data-stage="<?= $cl['CurrentStageOrder'] ?? 1 ?>"
        data-status="<?= htmlspecialchars($cl['CurrentStatus'] ?? '', ENT_QUOTES); ?>"
        title="Update Stage">
<i class="fas fa-edit"></i>
</button>


<?php 
$currentStatus = strtolower(trim($cl['CurrentStatus'] ?? ''));
$showOffer = false;
$showOnboarding = false;
$showHiring = false;
$isHired = false;

if (strpos($currentStatus, 'selected') !== false || 
    $currentStatus == 'offer pending' || 
    $currentStatus == 'offer accepted' || 
    $currentStatus == 'offer rejected' ||
    $currentStatus == 'offer released') {
    $showOffer = true;
}

if ($currentStatus == 'offer accepted') {
    $showOnboarding = true;
}

if ($currentStatus == 'on boarding') {
    $showHiring = true;
}

if (strpos($currentStatus, 'hired') !== false) {
    $isHired = true;
}

if ($showOffer): ?>
    <button type="button"
        class="btn btn-sm btn-info openOfferModal"
        data-id="<?= $cl['CandidateId']; ?>"
        title="Release Offer">
        <i class="fas fa-phone"></i>
    </button>
<?php endif; ?>

<?php if ($showOnboarding): ?>
    <button type="button"
        class="btn btn-sm btn-warning openOnboardingModal"
        data-id="<?= $cl['CandidateId']; ?>"
        title="Start Onboarding">
        <i class="fas fa-user-check"></i>
    </button>
<?php endif; ?>

<?php if ($showHiring): ?>
    <button type="button"
        class="btn btn-sm btn-success openHiringModal"
        data-id="<?= $cl['CandidateId']; ?>"
        title="Hiring">
        <i class="fas fa-user-plus"></i>
    </button>
<?php endif; ?>

<?php if ($isHired): ?>
    <span class="badge badge-success p-2">
        HIRED
    </span>
<?php endif; ?>


</div>

</td>  
                                    </tr>
                                    <?php
                                        }
                                    }    
                                    ?>
                                       
                            </tbody> 
                    </table>
              </div>
                  
         </div>
      </div>
    </section>
   



<div id="offerPanel" class="right-form">

    <div class="right-form-header">
        <h5>Offer Release</h5>
        <button type="button" class="close-btn" id="closeOfferPanel">&times;</button>
    </div>

    <div class="right-form-body">

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Offer Information</h3>
            </div>

            <div class="card-body">

                <input type="hidden" id="offerCandidateId">

                <div class="form-group">
                    <label>Offer Issued Date</label>
                    <input type="date" class="form-control" id="offerDate">
                </div>

                <div class="form-group">
                    <label>Notice Period (Days)</label>
                    <input type="number" class="form-control" id="noticeDays">
                </div>

                <div class="form-group">
                    <label>Expected Joining Date</label>
                    <input type="text" class="form-control" id="expectedJoinDate">
                </div>
                 
                <div class="form-group">
    <label>Offer Status</label>
    <select class="form-control" id="offerStatus">
        <option value="">Select Status</option>
        <option value="Pending">Pending</option>
        <option value="Accepted">Accepted</option>
        <option value="Rejected">Rejected</option>
    </select>
</div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" id="offerRemarks"></textarea>
                </div>

                <button class="btn btn-success" id="saveOffer">
                    Save
                </button>

            </div>
        </div>

    </div>

</div>
 <div id="hiringPanel" class="right-form">

    <div class="right-form-header">
        <h5>Hiring Details</h5>
        <button type="button" class="close-btn" id="closeHiringPanel">&times;</button>
    </div>

    <div class="right-form-body">

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Employee Hiring Information</h3>
            </div>

            <div class="card-body">

                <input type="hidden" id="hiringCandidateId">

                <div class="form-group">
                    <label>Joining Date</label>
                    <input type="date" class="form-control" id="joiningDate">
                </div>

                <div class="form-group">
                    <label>Salary Offered</label>
                    <input type="number" class="form-control" id="salaryOffered">
                </div>

                <div class="form-group">
                    <label>Employment Type</label>
                    <input type="text" class="form-control" id="employmentType">
                </div>

                <div class="form-group">
                    <label>Work Location</label>
                    <input type="text" class="form-control" id="workLocation">
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" id="hiringRemarks"></textarea>
                </div>

                <button class="btn btn-success" id="saveHiring">
                    Save Hiring
                </button>

            </div>
        </div>

    </div>
</div>

 <div id="candidateStagePanel" class="right-form">

<div class="right-form-header">
<h5>Candidate Stage Update</h5>
<button type="button" class="close-btn" id="closeCandidateStage">&times;</button>
</div>

<div class="right-form-body">

<div class="card card-default">
<div class="card-header">
<h3 class="card-title">Stage Information</h3>
</div>

<div class="card-body">

<input type="hidden" id="stageCandidateId">

<div class="form-group" id="stageGroup">
<label>Stage</label>
<select id="stageId" class="form-control">
<option value="">Select Stage</option>
</select>
</div>

<div class="form-group" id="actionGroup">
    <label>Action</label>
    <select id="stageAction" class="form-control">
        <option value="">Select Action</option>
        <option value="Screened">Screened</option>
        <option value="Shortlisted">Shortlisted</option>
        <option value="Rejected">Rejected</option>
        <option value="On Hold">On Hold</option>
        <option value="Not Qualifed">Not Qualifed</option>
        <option value="Not Intrested">Not Intersted</option>
        <option value="Reschedule">Reschedule</option>
    </select>
</div>
<!-- Interview (only for Screened) -->
<div class="shortlistedOnly" style="display:none">

    <!-- Interview Schedule -->
    <div class="form-group">
        <label>Interview Schedule</label>
        <input type="date" id="interviewDate" class="form-control">
    </div>

    <!-- Interview Type -->
    <div class="form-group">
        <label>Interview Mode </label>
        <select id="interviewType" class="form-control">
            <option value="">Select Type</option>
            <option value="Online">Online</option>
            <option value="Offline">Offline</option>
        </select>
    </div>

    <!-- Interview Level -->
    <div class="form-group">
        <label>Interview Level</label>
        <select id="interviewLevel" class="form-control">
            <option value="">Select Level</option>
        </select>
    </div>

    <!-- Interviewer -->
    <div class="form-group">
        <label>Interviewer</label>
        <select id="interviewerId" class="form-control">
            <option value="">Select Interviewer</option>
            <?php foreach($this->db->get('IHUsers')->result_array() as $u){ ?>
            <option value="<?= $u['IUid'] ?>"><?= $u['EmpName'] ?></option>
            <?php } ?>
        </select>
    </div>

</div>
<!-- Follow Up (Switch Off / RNR) -->
<div class="followupOnly" style="display:none">

<div class="form-group">
<label>Follow Up Type</label>
<select id="followupType" class="form-control">
<option value="">Select</option>
<option value="Whatsapp">Whatsapp</option>
<option value="Email">Email</option>
<option value="Message">Meeting</option>
<option value="Call">Call</option>
</select>
</div>

<div class="form-group">
<label>Next Follow Up Date</label>
<input type="datetime-local" id="nextFollowupDate" class="form-control">
</div>

</div>


<div class="form-group">
<label>Remarks</label>
<textarea id="stageRemarks" class="form-control"></textarea>
</div>

<button class="btn btn-success" id="saveCandidateStage">
Save
</button>

</div>
</div>

</div>
</div>


<div class="modal fade" id="candidateDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Candidate Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="candidateDetailsBody">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Loading...
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="candidateupdateDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Candidate Followup Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="candidateupdateDetailsBody">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Loading...
                </div>
            </div>

        </div>
    </div>
        
</div><div class="modal fade" id="vacancyDetailsModal" tabindex="-1">
 <div class="modal-dialog modal-lg">
  <div class="modal-content">

   <div class="modal-header">
    <h5 class="modal-title">Vacancy Details</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
   </div>

   <div class="modal-body" id="vacancyDetailsBody">
    <div class="text-center p-5">
     <i class="fa fa-spinner fa-spin fa-2x"></i>
    </div>
   </div>

  </div>
 </div>
</div>   <!-- CLOSE vacancy modal completely -->

<div id="vacancyOverlay"></div>


 <div id="onboardingPanel" class="right-form">

    <div class="right-form-header">
        <h5>Onboarding Update</h5>
        <button type="button" class="close-btn" id="closeOnboardingPanel">&times;</button>
    </div>

    <div class="right-form-body">

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Onboarding Information</h3>
            </div>

            <div class="card-body">

                <input type="hidden" id="onboardCandidateId">

                <div class="form-group">
                    <label>Documents Submitted?</label>
                    <select id="documentsSubmitted" class="form-control">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea id="onboardRemarks" class="form-control"></textarea>
                </div>

                <button class="btn btn-success" id="saveOnboarding">
                    Save
                </button>

            </div>
        </div>

    </div>

</div>

<div class="modal fade" id="scoreBreakdownModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    ATS Score Breakdown
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    &times;

                </button>
            </div>

            <div class="modal-body" id="scoreBreakdownModalBody">

                <div class="text-center p-5">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>

            </div>

        </div>
    </div>
</div>

<script>

    var base_url = "<?= base_url(); ?>";


    

   $(document).on('click', '.editCandidateDetails', function () {

    let candidateId = $(this).data('id');
    console.log(candidateId);
    $('#candidateupdateDetailsModal').modal('show');
    $('#candidateupdateDetailsBody').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');

   
    });

/////

function loadNextStages(currentOrder){

 $.post('<?= base_url("admin/getNextStages") ?>',{
    currentOrder : currentOrder
 },function(res){

    let stages = JSON.parse(res);
    let dropdown = $('#stageId');
    dropdown.html('<option value="">Select Stage</option>');

    stages.forEach(function(stage){
        dropdown.append(
            `<option value="${stage.StageId}" data-name="${stage.StageName}" data-group="${stage.StageGroup}">
                ${stage.StageName}
            </option>`
        );
    });

 });

}


////

$(document).on('click','.openCandidateStage',function(){

   let cid = $(this).data('id');
   let currentOrder = $(this).data('stage');   
   let status = ($(this).data('status') || '').toString().trim().toLowerCase();

   $('#stageCandidateId').val(cid);

   $('.shortlistedOnly, .followupOnly').hide();
   $('#interviewDate').val('');
   $('#stageAction').val('');
   $('#stageRemarks').val('');

   window.currentCandidateJobPanels = [];
   $.post('<?= base_url("admin/getCandidateInterviewPanelInfo") ?>', { candidateId: cid }, function(res) {
       try {
           let d = JSON.parse(res);
           if (d.status === 'success' && d.panels) {
               window.currentCandidateJobPanels = d.panels;
           }
       } catch(e) {}
   });

   if (status === 'cv uploaded' || status === 'uploaded') {
       $('#stageGroup').show();
       $('#actionGroup').hide();
   } else {
       $('#stageGroup').hide();
       $('#actionGroup').show();
   }

   $('#candidateStagePanel').addClass('open');
   $('#vacancyOverlay').addClass('show');

   loadNextStages(currentOrder);   

});

$('#saveCvScreeningBtn').on('click', function() {
    var $btn = $(this);
    var originalText = $btn.html();
    var cid = $('#cvCandidateId').val();
    var action = $('#cvScreeningAction').val();
    var remarks = $('#cvScreeningRemarks').val();

    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

    $.post('<?= base_url("admin/saveCandidateStage") ?>', {
        candidateId: cid,
        action: action,
        remarks: remarks
    }, function(res) {
        var data;
        try {
            data = JSON.parse(res);
        } catch(e) {
            data = { status: 'error', msg: 'Invalid server response' };
        }

        if (data.status === 'success' || data.status === 'rejected') {
            toastr.success(data.msg || 'Candidate stage updated successfully.');
            $('#cvScreeningModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else {
            $btn.prop('disabled', false).html(originalText);
            toastr.error(data.msg || 'Error updating stage.');
        }
    }).fail(function() {
        $btn.prop('disabled', false).html(originalText);
        toastr.error('Network or server error.');
    });
});

$('#closeCandidateStage').on('click',function(){
  $('#candidateStagePanel').removeClass('open');
  $('#vacancyOverlay').removeClass('show');
});



$('#saveCandidateStage').on('click',function(){
    console.log('SAVE CLICKED');

    var $btn = $(this);
    var originalText = $btn.html();

    if ($('#stageGroup').is(':visible') && !$('#stageId').val()) {
        toastr.error("Please select stage.");
        return;
    }

    if ($('#actionGroup').is(':visible')) {
        var action = $('#stageAction').val();
        if (!action) {
            toastr.error("Please select action.");
            return;
        }

        if (action === 'Shortlisted' || action === 'Reschedule') {
            var intDate = $('#interviewDate').val();
            var intType = $('#interviewType').val();
            var intLevel = $('#interviewLevel').val();
            var interviewer = $('#interviewerId').val();

            if (!intDate) {
                toastr.error("Please select interview schedule date.");
                return;
            }

            if (!intType) {
                toastr.error("Please select interview mode.");
                return;
            }

            if (!intLevel) {
                toastr.error("Please select interview level.");
                return;
            }

            if (!interviewer) {
                toastr.error("Please select interviewer.");
                return;
            }
        }
    }

    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

    $.post('<?= base_url("admin/saveCandidateStage") ?>',{
        candidateId: $('#stageCandidateId').val(),
        stageId: $('#stageId').val(),
        action: $('#stageAction').val(),
        remarks: $('#stageRemarks').val(),

        followupType: $('#followupType').val(),
        nextFollowupDate: $('#nextFollowupDate').val(),

        interviewDate: $('#interviewDate').val(),
        interviewLevel: $('#interviewLevel').val(),
        interviewType: $('#interviewType').val(),
        interviewerId: $('#interviewerId').val()
    },function(res){
        console.log('Save response:', res);
        var data;
        try {
            data = JSON.parse(res);
        } catch (e) {
            data = { status: 'error', msg: 'Invalid server response' };
        }

        if (data.status === 'success' || data.status === 'rejected') {
            toastr.success(data.msg || 'Candidate stage updated successfully.');
            
          
            $('#candidateStagePanel').removeClass('open');
            $('#vacancyOverlay').removeClass('show');

            
            setTimeout(function() {
                location.reload();
            }, 1000);
        } else if (data.status === 'failed') {
           
            toastr.warning(data.msg || 'Candidate stage updated, but email notification failed.');
            
         
            $('#candidateStagePanel').removeClass('open');
            $('#vacancyOverlay').removeClass('show');

            setTimeout(function() {
                location.reload();
            }, 1500);
        } else {
          
            toastr.error(data.msg || 'Error updating candidate stage.');
            $btn.prop('disabled', false).html(originalText);
        }
    }).fail(function() {
        toastr.error('Server error. Please try again.');
        $btn.prop('disabled', false).html(originalText);
    });
});
 

/////
$(document).on('click', '.viewVacancyBtn', function () {
    let jid = $(this).data('id');
    $('#vacancyDetailsModal').modal('show');
    $('#vacancyDetailsBody').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
    $.post('<?= base_url("admin/getJobDetails") ?>', {jid: jid}, function (res) {
        let d = JSON.parse(res);
        let html = `<div class="container-fluid">`;
        html += `<div class="card card-primary collapsed-card"><div class="card-header bg-primary"><h3 class="card-title">Basic Information</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><div class="row"><div class="col-md-6"><p><b>Job Code:</b> ${d.JobCode}</p><p><b>Job Title:</b> ${d.JobTitle}</p><p><b>Department:</b> ${d.Departmentname}</p><p><b>Role:</b> ${d.RoleSummary}</p><p><b>Status:</b> ${d.JobStatus}</p></div><div class="col-md-6"><p><b>Posted By:</b> ${d.PostedByName}</p><p><b>Posted On:</b> ${d.PostedOn}</p><p><b>Expiry Date:</b> ${d.ExpiryDate}</p><p><b>Work Mode:</b> ${d.WorkMode}</p><p><b>Employment:</b> ${d.EmploymentType}</p><p><b>Language:</b> ${d.CommunicationLang}</p></div></div></div></div>`;
        html += `<div class="card card-info collapsed-card"><div class="card-header bg-info"><h3 class="card-title">Salary & Experience</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><div class="row"><div class="col-md-6"><p><b>Experience Required:</b> ${d.ExpMin ?? 0} - ${d.ExpMax ?? 0} Years</p></div><div class="col-md-6"><p><b>Salary Required:</b> ${d.SalMin ?? 0} - ${d.SalMax ?? 0} LPA</p></div></div></div></div>`;
        html += `<div class="card card-secondary collapsed-card"><div class="card-header bg-secondary"><h3 class="card-title">Location & Education</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><p><b>Job Location:</b> ${d.JobLocation}</p><p><b>Education Required:</b> ${d.EducationRequired}</p></div></div>`;
        html += `<div class="card card-warning collapsed-card"><div class="card-header bg-warning"><h3 class="card-title">Skills</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><p>${d.Skills}</p></div></div>`;
        html += `<div class="card card-dark collapsed-card"><div class="card-header bg-dark"><h3 class="card-title">Roles & Responsibilities</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body">${d.Responsibilities}</div></div>`;
        html += `<div class="card card-success collapsed-card"><div class="card-header bg-success"><h3 class="card-title">Job Description</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body">${d.JobDescription}</div></div>`;
        html += `</div>`;
        $('#vacancyDetailsBody').html(html);
    });
});

$(document).on('change','#stageId',function(){

  let option = $(this).find('option:selected');
  let stageName = option.data('name') || '';

  $('.screenedOnly').hide();
  $('.followupOnly').hide();
  
  let action = $('#stageAction').val();
  if (action !== 'Shortlisted' && action !== 'Reschedule') {
      $('#interviewDate').val('');
  }
  
  $('#followupType').val('');
  $('#nextFollowupDate').val('');

  if(stageName.toLowerCase() === 'switch off' || stageName.toLowerCase() === 'rnr'){
       $('.followupOnly').slideDown();
   }

});

$(document).on('change','#stageAction',function(){

 let action = $(this).val();

 
 $('.screenedOnly').hide();
 $('.shortlistedOnly').hide();

 
 if(action == "Shortlisted"){

     $('.screenedOnly').slideDown();     
     $('.shortlistedOnly').slideDown(); 

     loadInterviewLevels();            
 }

 // Handle Reschedule action
 if(action == "Reschedule"){
     $('.screenedOnly').slideDown();     
     $('.shortlistedOnly').slideDown(); 

     loadInterviewLevels();            
 }

});
$('#increaseLevel').on('click',function(e){
 e.preventDefault();

 let lvl = parseInt($('#interviewLevel').val());

 if(lvl < 4){
    $('#interviewLevel').val(lvl+1);
 }
});

function autoSelectInterviewerForLevel() {
    if (!window.currentCandidateJobPanels || window.currentCandidateJobPanels.length === 0) return;

    let selectedOption = $('#interviewLevel option:selected');
    let selectedText = selectedOption.text();
    let levelNum = 1;

    let match = selectedText.match(/level\s*(\d+)/i);
    if (match && match[1]) {
        levelNum = parseInt(match[1]);
    } else {
        let selectedIdx = $('#interviewLevel')[0].selectedIndex;
        if (selectedIdx > 0) {
            levelNum = selectedIdx;
        }
    }

    let panel = window.currentCandidateJobPanels.find(p => parseInt(p.LevelOrder) === levelNum);
    if (panel && panel.InterviewerId) {
        $('#interviewerId').val(panel.InterviewerId);
    }
}

function loadInterviewLevels(){

 $.post('<?= base_url("admin/getInterviewLevels") ?>',{},function(res){

   let data = JSON.parse(res);
   let ddl = $('#interviewLevel');

   ddl.html('<option value="">Select Level</option>');

   data.forEach(function(r){
     ddl.append(`<option value="${r.StageId}">${r.StageName}</option>`);
   });

   if (ddl.find('option').length > 1) {
       ddl.prop('selectedIndex', 1);
   }

   autoSelectInterviewerForLevel();

 });
}

$(document).on('change', '#interviewLevel', function() {
    autoSelectInterviewerForLevel();
});


$(document).on('click', '[data-card-widget="collapse"]', function () {
 
    let currentCard = $(this).closest('.card');
 
    if (currentCard.hasClass('collapsed-card')) {
 
        
        $('.modal .card').not(currentCard).each(function () {
            if (!$(this).hasClass('collapsed-card')) {
                $(this).CardWidget('collapse');
            }
        });
 
    }
 
});
$(document).ready(function () {

    let jid = "<?= $jobdetails['Jid']; ?>";

    $(document).on('click', '.filterPill', function (e) {
        e.preventDefault();

        $('.filterPill').removeClass('active');
        $(this).addClass('active');

        let status = $(this).data('status');

        $.ajax({
            url: base_url + 'admin/filterCandidates',
            type: 'POST',
            data: { status: status, jid: jid },
            success: function (res) {
                $('#example1 tbody').html(res);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            error: function (xhr) {
                console.log('ERROR:', xhr.responseText);
            }
        });
    });

});
$(document).on('click', '.openOnboardingModal', function () {

    let cid = $(this).data('id');

    $('#onboardCandidateId').val(cid);
    $('#documentsSubmitted').val('');
    $('#onboardRemarks').val('');

   
    $('#onboardingPanel').addClass('open');
$('#vacancyOverlay').addClass('show');

});
$('#closeOnboardingPanel').on('click', function(){
    $('#onboardingPanel').removeClass('open');
    $('#vacancyOverlay').removeClass('show');
});
$(document).on('click', '#saveOnboarding', function () {
 console.log("SAVE CLICKED");

   
    let candidateId = $('#onboardCandidateId').val();
    let documents = $('#documentsSubmitted').val();
    let remarks = $('#onboardRemarks').val();

    if (documents === "") {
        toastr.error("Please select documents submitted status");
        return;
    }

    $.post('<?= base_url("admin/saveOnboarding") ?>', {
        candidateId: candidateId,
        documentsSubmitted: documents,
        remarks: remarks
    }, function (res) {

        toastr.success("Onboarding updated successfully");
       
        $('#onboardingPanel').removeClass('open');
$('#vacancyOverlay').removeClass('show');
        location.reload();

    });

});


$(document).on('click','.openOfferModal',function(){

    let cid = $(this).data('id');

    $('#offerCandidateId').val(cid);
    $('#offerDate').val('');
    $('#noticeDays').val('');
    $('#offerRemarks').val('');
    $('#expectedJoinDate').val('');
    $('#offerStatus').val('');

    $.ajax({
        url: base_url + "admin/getCandidateIdDetails",
        type: "POST",
        data: { candidate_id: cid },
        dataType: "json",
        success: function (res) {
            if (res.status === 'success' && res.data.offers && res.data.offers.length > 0) {
                let latestOffer = res.data.offers[res.data.offers.length - 1];
                if (latestOffer.OfferDate) {
                    $('#offerDate').val(latestOffer.OfferDate);
                }
                if (latestOffer.NoticePeriodDays) {
                    $('#noticeDays').val(latestOffer.NoticePeriodDays);
                }
                if (latestOffer.ExpectedJoiningDate) {
                    $('#expectedJoinDate').val(latestOffer.ExpectedJoiningDate);
                }
                if (latestOffer.OfferStatus) {
                    $('#offerStatus').val(latestOffer.OfferStatus);
                }
            }
        }
    });

    $('#offerPanel').addClass('open');
    $('#vacancyOverlay').addClass('show');
});

$('#closeOfferPanel').on('click',function(){
    $('#offerPanel').removeClass('open');
    $('#vacancyOverlay').removeClass('show');
});
$(document).on('click','#saveOffer',function(){

    let candidateId = $('#offerCandidateId').val();
    let offerDate   = $('#offerDate').val();
    let noticeDays  = $('#noticeDays').val();
    let remarks     = $('#offerRemarks').val();
       let status      = $('#offerStatus').val(); 

    if(offerDate === '' || noticeDays === ''){
        toastr.error("Please fill all required fields");
        return;
    }

    $.post('<?= base_url("admin/saveOffer") ?>',{
        candidateId : candidateId,
        offerDate   : offerDate,
        noticeDays  : noticeDays,
          offerStatus : status,
        remarks     : remarks
    },function(res){

        toastr.success("Offer saved successfully");

        $('#offerPanel').removeClass('open');
        $('#vacancyOverlay').removeClass('show');

        location.reload();
    });

});
$('#offerDate, #noticeDays').on('change keyup', function(){

    let offerDate = $('#offerDate').val();
    let days = parseInt($('#noticeDays').val());

    if(offerDate && days){

        let date = new Date(offerDate);
        date.setDate(date.getDate() + days);

        let yyyy = date.getFullYear();
        let mm = String(date.getMonth()+1).padStart(2,'0');
        let dd = String(date.getDate()).padStart(2,'0');

        $('#expectedJoinDate').val(yyyy + '-' + mm + '-' + dd);
    }
});


$(document).on('click', '.openHiringModal', function () {

    let candidateId = $(this).data('id');

    $('#hiringCandidateId').val(candidateId);

    $('#hiringPanel').addClass('open');
    $('#vacancyOverlay').addClass('show');

});



$('#closeHiringPanel').on('click', function () {

    $('#hiringPanel').removeClass('open');
    $('#vacancyOverlay').removeClass('show');

});



$(document).on('click', '#saveHiring', function () {

    let candidateId    = $('#hiringCandidateId').val();
    let joiningDate    = $('#joiningDate').val();
    let salaryOffered  = $('#salaryOffered').val();
    let employmentType = $('#employmentType').val();
    let workLocation   = $('#workLocation').val();
    let remarks        = $('#hiringRemarks').val();

    if(joiningDate == '' || salaryOffered == ''){
        toastr.error("Please fill required fields");
        return;
    }

    $.post('<?= base_url("admin/saveHiring") ?>', {

        candidateId     : candidateId,
        joiningDate     : joiningDate,
        salaryOffered   : salaryOffered,
        employmentType  : employmentType,
        workLocation    : workLocation,
        remarks         : remarks

    }, function (res) {

        console.log(res);

        toastr.success("Hiring completed successfully");

        $('#hiringPanel').removeClass('open');
        $('#vacancyOverlay').removeClass('show');

        location.reload();

    });

});
$(document).on('click', '.viewCandidateDetails', function () {

    let candidateId = $(this).data('id');

    $('#candidateDetailsModal').modal('show');
    $('#candidateDetailsBody').html(
        '<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>'
    );

    $.ajax({
        url: base_url + "admin/getCandidateIdDetails",
        type: "POST",
        data: { candidate_id: candidateId },
        dataType: "json",
        success: function (res) {

            if (res.status !== 'success') {
                $('#candidateDetailsBody').html('<div class="alert alert-danger">No data found</div>');
                return;
            }

            let c = res.data.candidate;
            let stages = res.data.stages;
            let interviews = res.data.interviews;
            let offers = res.data.offers;
            let followups = res.data.followups;

            let html = `<div class="container-fluid">`;

            /* BASIC INFO */
            html += `
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Basic Information</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${c.Fullname ?? '-'}</p>
                            <p><strong>Email:</strong> ${c.Email ?? '-'}</p>
                            <p><strong>Phone:</strong> ${c.PhoneNo ?? '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Experience:</strong> ${c.ExpYrs ?? 0} Years</p>
                            <p><strong>ATS Score:</strong> ${c.ProfileMatchPer ?? 0}%</p>
                            <p><strong>Status:</strong>
                                <span class="badge ${
                                    c.ATS_Status && c.ATS_Status.toLowerCase().includes('shortlisted') ? 'badge-success' :
                                    c.ATS_Status && c.ATS_Status.toLowerCase().includes('selected') ? 'badge-success' :
                                    c.ATS_Status && c.ATS_Status.toLowerCase().includes('rejected') ? 'badge-danger' :
                                    c.ATS_Status && c.ATS_Status.toLowerCase().includes('hold') ? 'badge-warning' :
                                    'badge-secondary'}">
                                    ${c.ATS_Status ?? '-'}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>`;
            /* ================= EXPERIENCE BREAKDOWN ================= */
html += `
<div class="card card-warning collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Experience Breakdown</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">`;
 


let exp = c.experience_details;


if (typeof exp === "string") {
    try {
        exp = JSON.parse(exp);
    } catch (e) {
        exp = null;
    }
}

if (exp && exp.jobs && exp.jobs.length > 0) {

    exp.jobs.forEach(function(e) {

        html += `
        <div class="border p-2 mb-2">
            <strong>${e.from} - ${e.to}</strong><br>
            Duration: ${(e.years ?? 0)} Years ${(e.months ?? 0)} Months
        </div>`;
    });

    html += `
    <div class="alert alert-info mt-3">
        <strong>Total Experience:</strong> ${exp.total}
    </div>`;
}
html += `</div></div>`;
 

            
            html += `
            <div class="card card-info collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Job Details</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Job Title:</strong> ${c.JobTitle ?? '-'}</p>
                    <p><strong>Location:</strong> ${c.JobLocation ?? '-'}</p>
                    <p><strong>Employment Type:</strong> ${c.EmploymentType ?? '-'}</p>
                </div>
            </div>`;

           
            html += `
            <div class="card card-secondary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Stage Timeline</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-inverse">`;

            if (stages.length > 0) {
                stages.forEach(function (s) {
                    let badgeColor = 'bg-info';
                    if (s.Action && s.Action.toLowerCase().includes('rejected')) badgeColor = 'bg-danger';
                    else if (s.Action && s.Action.toLowerCase().includes('shortlisted')) badgeColor = 'bg-success';
                    else if (s.Action && s.Action.toLowerCase().includes('hold')) badgeColor = 'bg-warning';

                    html += `
                    <div>
                        <i class="fas fa-user ${badgeColor}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> ${s.ActionAt}</span>
                            <h3 class="timeline-header">${s.StageName}</h3>
                            <div class="timeline-body">
                                <strong>Action:</strong> ${s.Action ?? '-'}<br>
                                <strong>By:</strong> ${s.ActionByName ?? 'System'}<br>
                                <strong>Remarks:</strong> ${s.Remarks ?? '-'}
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html += `
                <div>
                    <i class="fas fa-info bg-secondary"></i>
                    <div class="timeline-item">
                        <div class="timeline-body">No stage tracking found</div>
                    </div>
                </div>`;
            }

            html += `<div><i class="far fa-clock bg-gray"></i></div></div></div></div>`;

            html += `
            <div class="card card-dark collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Interviews</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-inverse">`;

            if (interviews && interviews.length > 0) {
                interviews.forEach(function (i) {
                    let badgeColor = 'bg-info';
                    if (i.Result && i.Result.toLowerCase().includes('selected')) badgeColor = 'bg-success';
                    else if (i.Result && i.Result.toLowerCase().includes('rejected')) badgeColor = 'bg-danger';
                    else if (i.Result && i.Result.toLowerCase().includes('assigned')) badgeColor = 'bg-warning';

                    html += `
                    <div>
                        <i class="fas fa-user-tie ${badgeColor}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> ${(i.ScheduledAt && i.ScheduledAt !== '0000-00-00 00:00:00') ? i.ScheduledAt : '-'}</span>
                            <h3 class="timeline-header">Interview Round ${i.InterviewRound ?? '-'}</h3>
                            <div class="timeline-body">
                                <strong>Type:</strong> ${i.InterviewType ?? '-'}<br>
                                <strong>Result:</strong> ${i.Result ?? 'Assigned'}<br>
                                <strong>Feedback:</strong> ${i.Feedback ?? '-'}
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html += `
                <div>
                    <i class="fas fa-info bg-secondary"></i>
                    <div class="timeline-item">
                        <div class="timeline-body">No interviews scheduled</div>
                    </div>
                </div>`;
            }

            html += `<div><i class="far fa-clock bg-gray"></i></div></div></div></div>`;

            
            html += `
            <div class="card card-success collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Offers</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">`;

            if (offers && offers.length > 0) {
                offers.forEach(function (o) {
                    html += `
                    <div class="border p-2 mb-2">
                        <strong>Status:</strong> ${o.OfferStatus ?? '-'}<br>
                        <strong>Expected Joining:</strong> ${o.ExpectedJoiningDate ?? '-'}
                    </div>`;
                });
            } else {
                html += `<p>No offer details</p>`;
            }

            html += `</div></div>`;

           
            html += `
            <div class="card card-warning collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Follow Ups</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">`;

            if (followups && followups.length > 0) {
                followups.forEach(function (f) {
                    html += `
                    <div class="border p-2 mb-2">
                        <strong>Type:</strong> ${f.FollowUpType ?? '-'}<br>
                        <strong>Notes:</strong> ${f.FollowUpNotes ?? '-'}
                    </div>`;
                });
            } else {
                html += `<p>No follow-ups</p>`;
            }

            html += `</div></div></div>`;

            $('#candidateDetailsBody').html(html);
        }
    });
});



$(document).on('click', '.viewCandidateSimple', function () {

    let candidateId = $(this).data('id');

    $('#candidateDetailsModal').modal('show');
    $('#candidateDetailsBody').html(
        '<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>'
    );

    $.ajax({
        url: base_url + "admin/getCandidateIdDetails",
        type: "POST",
        data: { candidate_id: candidateId },
        dataType: "json",
        success: function (res) {

            if (res.status !== 'success') {
                $('#candidateDetailsBody').html('<div class="alert alert-danger">No data found</div>');
                return;
            }

            let c = res.data.candidate;
            let stages = res.data.stages;

            let html = `
            <div class="card card-primary">
                <div class="card-body">
                    <h5>${c.Fullname ?? '-'}</h5>
                    <p><strong>Email:</strong> ${c.Email ?? '-'}</p>
                    <p><strong>Phone:</strong> ${c.PhoneNo ?? '-'}</p>
                    <p><strong>Status:</strong>
                        <span class="badge ${
                            c.ATS_Status && c.ATS_Status.toLowerCase().includes('shortlisted') ? 'badge-success' :
                            c.ATS_Status && c.ATS_Status.toLowerCase().includes('selected') ? 'badge-success' :
                            c.ATS_Status && c.ATS_Status.toLowerCase().includes('rejected') ? 'badge-danger' :
                            c.ATS_Status && c.ATS_Status.toLowerCase().includes('hold') ? 'badge-warning' :
                            'badge-secondary'}">
                            ${c.ATS_Status ?? '-'}
                        </span>
                    </p>
                </div>
            </div>`;

            html += `<div class="timeline timeline-inverse">`;

            if (stages.length > 0) {
                stages.forEach(function (s) {
                    let badgeColor = 'bg-info';
                    if (s.Action && s.Action.toLowerCase().includes('rejected')) badgeColor = 'bg-danger';
                    else if (s.Action && s.Action.toLowerCase().includes('shortlisted')) badgeColor = 'bg-success';
                    else if (s.Action && s.Action.toLowerCase().includes('hold')) badgeColor = 'bg-warning';

                    html += `
                    <div>
                        <i class="fas fa-user ${badgeColor}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> ${s.ActionAt}</span>
                            <h3 class="timeline-header">${s.StageName}</h3>
                            <div class="timeline-body">
                                <strong>Action:</strong> ${s.Action ?? '-'}<br>
                                <strong>Remarks:</strong> ${s.Remarks ?? '-'}
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html += `<p class="text-muted p-2">No stage tracking found</p>`;
            }

            html += `<div><i class="far fa-clock bg-gray"></i></div></div>`;

            $('#candidateDetailsBody').html(html);
        }
    });
});
$(document).on('click', '.btnScoreHelp', function () {
    let btn = $(this);

   
    let score        = btn.data('score')    ?? 0;
    let matchedSkills = btn.data('skills') ?? '';
    let eduMatch     = btn.data('edu')      ?? 'No';
    let expMatch     = btn.data('exp')      ?? 'No';
    let jobSkillsStr = btn.data('jobskills') ?? '';

    let breakdown = {};
    try {
        let raw = btn.data('breakdown');
        if (raw) {
            breakdown = (typeof raw === 'string') ? JSON.parse(raw) : raw;
        }
    } catch(e) { console.error('ScoreBreakdown parse error', e); }

    let hasBreakdown = Object.keys(breakdown).length > 0;

    let skillsScore   = parseInt(breakdown.skills)           || 0;
    let eduScore      = parseInt(breakdown.education)        || 0;
    let expScore      = parseInt(breakdown.experience)       || 0;
    let projectsScore = parseInt(breakdown.projects)         || 0;
    let certScore     = parseInt(breakdown.certifications)   || 0;
    let qualityScore  = parseInt(breakdown.resume_quality)   || 0;
    let domainScore   = parseInt(breakdown.domain_knowledge) || 0;

    
    let skillsArr = jobSkillsStr.split(',').map(s => s.trim()).filter(s => s.length > 0);
    let totalSkills = skillsArr.length;

    
    let maxSkillsScore   = parseInt(breakdown.skills_max)           || (totalSkills > 0 ? totalSkills * 5 : 50);
    let maxEduScore      = parseInt(breakdown.education_max)        || 20;
    let maxExpScore      = parseInt(breakdown.experience_max)       || 20;
    let maxProjectsScore = parseInt(breakdown.project_max)          || 5;
    let maxCertScore     = parseInt(breakdown.certification_max)    || 10;
    let maxQualityScore  = parseInt(breakdown.resume_quality_max)   || 5;
    let maxDomainScore   = parseInt(breakdown.domain_max)           || 5;

   
    if (skillsScore > maxSkillsScore) skillsScore = maxSkillsScore;
    if (eduScore > maxEduScore) eduScore = maxEduScore;
    if (expScore > maxExpScore) expScore = maxExpScore;
    if (projectsScore > maxProjectsScore) projectsScore = maxProjectsScore;
    if (certScore > maxCertScore) certScore = maxCertScore;
    if (qualityScore > maxQualityScore) qualityScore = maxQualityScore;
    if (domainScore > maxDomainScore) domainScore = maxDomainScore;

    
    if (skillsScore < 0) skillsScore = 0;
    if (eduScore < 0) eduScore = 0;
    if (expScore < 0) expScore = 0;
    if (projectsScore < 0) projectsScore = 0;
    if (certScore < 0) certScore = 0;
    if (qualityScore < 0) qualityScore = 0;
    if (domainScore < 0) domainScore = 0;

    
    let gainedScore = skillsScore + eduScore + expScore + projectsScore + certScore + qualityScore + domainScore;
    let maxScore = maxSkillsScore + maxEduScore + maxExpScore + maxProjectsScore + maxCertScore + maxQualityScore + maxDomainScore;

   
    let matchPercentage = maxScore > 0 ? parseFloat(((gainedScore / maxScore) * 100).toFixed(2)) : 0;
    if (matchPercentage > 100) matchPercentage = 100;

    let breakdownHtml = '';

    if (hasBreakdown) {
        breakdownHtml = `
            <h6 class="text-primary font-weight-bold mb-3">
                <i class="fas fa-chart-bar mr-2"></i>ATS Score Breakdown Analysis
            </h6>
            <div class="row text-center mb-3">
                <div class="col-12">
                    <div class="h4 font-weight-bold text-navy mb-1">
                        Total Score: <span class="text-success">${gainedScore}</span> / <span class="text-secondary">${maxScore}</span> Marks
                    </div>
                    <div class="small text-muted font-weight-bold">
                        (Match Percentage: ${matchPercentage}%)
                    </div>
                </div>
            </div>

            <table class="table table-sm table-bordered table-striped mt-2">
                <thead class="bg-success text-white">
                    <tr>
                        <th>Assessment Criteria</th>
                        <th class="text-center" style="width: 120px;">Score Gained</th>
                        <th class="text-center" style="width: 120px;">Max Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Skills Match</strong>
                            <div class="small text-muted text-truncate" style="max-width: 320px;" title="${matchedSkills}">
                                ${matchedSkills ? '<strong>Matched:</strong> ' + matchedSkills : 'No skills matched'}
                            </div>
                        </td>
                        <td class="text-center font-weight-bold ${skillsScore > 0 ? 'text-success' : 'text-danger'}">${skillsScore}</td>
                        <td class="text-center text-muted">${maxSkillsScore}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Education Match</strong>
                            <div class="small text-muted">Required Match: ${eduMatch}</div>
                        </td>
                        <td class="text-center font-weight-bold ${eduMatch === 'Yes' ? 'text-success' : 'text-danger'}">${eduScore}</td>
                        <td class="text-center text-muted">${maxEduScore}</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Experience Match</strong>
                            <div class="small text-muted">Required Match: ${expMatch}</div>
                        </td>
                        <td class="text-center font-weight-bold ${expMatch === 'Yes' ? 'text-success' : 'text-danger'}">${expScore}</td>
                        <td class="text-center text-muted">${maxExpScore}</td>
                    </tr>
                    <tr>
                        <td><strong>Projects & Achievements</strong></td>
                        <td class="text-center font-weight-bold ${projectsScore > 0 ? 'text-success' : 'text-muted'}">${projectsScore}</td>
                        <td class="text-center text-muted">${maxProjectsScore}</td>
                    </tr>
                    <tr>
                        <td><strong>Certifications</strong></td>
                        <td class="text-center font-weight-bold ${certScore > 0 ? 'text-success' : 'text-muted'}">${certScore}</td>
                        <td class="text-center text-muted">${maxCertScore}</td>
                    </tr>
                    <tr>
                        <td><strong>Resume Quality</strong></td>
                        <td class="text-center font-weight-bold ${qualityScore > 0 ? 'text-success' : 'text-muted'}">${qualityScore}</td>
                        <td class="text-center text-muted">${maxQualityScore}</td>
                    </tr>
                    <tr>
                        <td><strong>Role Fit / Domain Knowledge</strong></td>
                        <td class="text-center font-weight-bold ${domainScore > 0 ? 'text-success' : 'text-muted'}">${domainScore}</td>
                        <td class="text-center text-muted">${maxDomainScore}</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-light p-2 rounded border mt-3">
                <div class="d-flex justify-content-between font-weight-bold text-dark small">
                    <span>Overall Resume Fit Match Percentage:</span>
                    <span class="text-primary">${matchPercentage}%</span>
                </div>
                <div class="progress mt-1" style="height: 6px; background: #e9ecef; border-radius: 3px;">
                    <div class="progress-bar bg-primary" style="width: ${matchPercentage}%;"></div>
                </div>
            </div>
        `;
    } else {
        breakdownHtml = `
            <h6 class="text-primary font-weight-bold mb-3">
                <i class="fas fa-chart-bar mr-2"></i>ATS Score Analysis
            </h6>
            <div class="alert alert-warning">
                Detailed breakdown is not available for this legacy record.
            </div>
            <div class="bg-light p-3 rounded border">
                <div class="d-flex justify-content-between font-weight-bold text-dark small">
                    <span>Overall Resume Fit Match Percentage:</span>
                    <span class="text-primary">${score}%</span>
                </div>
                <div class="progress mt-1" style="height: 6px; background: #e9ecef; border-radius: 3px;">
                    <div class="progress-bar bg-primary" style="width: ${score}%;"></div>
                </div>
            </div>
        `;
    }

    $('#scoreBreakdownModalBody').html(breakdownHtml);
    $('#scoreBreakdownModal').modal('show');
});
</script>
 
<script>
$(document).ready(function() {
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable({
            "responsive": true,
            "autoWidth": false
        });
    }
    $(window).on('resize orientationchange', function() {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().columns.adjust().responsive.recalc();
        }
    });
});
</script>