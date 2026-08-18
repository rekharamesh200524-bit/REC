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
                    <th>ATS Recommendation</th> 
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
<?php
    $recommendation = trim($cl['ProfileMatchPer'] ?? '');

    if (in_array($recommendation, ['Strong Match', 'Strongly Match', 'Recommended'])) {
        $badgeClass = 'badge-success';
        $recommendation = 'Strong Match';
    } elseif (in_array($recommendation, ['Potential Match', 'Review Required'])) {
        $badgeClass = 'badge-warning';
        $recommendation = 'Potential Match';
    } elseif (in_array($recommendation, ['Low Match', 'Not Recommended'])) {
        $badgeClass = 'badge-danger';
        $recommendation = 'Low Match';
    } else {
        $badgeClass = 'badge-secondary';
        $recommendation = $recommendation !== '' ? $recommendation : 'Potential Match';
    }

    $analysisData = [];

    if (!empty($cl['ScoreBreakdown'])) {
        if (is_string($cl['ScoreBreakdown'])) {
            $decoded = json_decode($cl['ScoreBreakdown'], true);
            if (is_array($decoded)) {
                $analysisData = $decoded;
            }
        } elseif (is_array($cl['ScoreBreakdown'])) {
            $analysisData = $cl['ScoreBreakdown'];
        }
    }

    $analysisData['recommendation'] =
        $analysisData['recommendation'] ?? $recommendation;

    $analysisData['recommendation_reason'] =
        $analysisData['recommendation_reason'] ?? '';

    $analysisData['relevant_evidence'] =
        $analysisData['relevant_evidence'] ?? [];

    $analysisData['missing_requirements'] =
        $analysisData['missing_requirements'] ?? [];

    $analysisData['domain'] =
        $analysisData['domain'] ?? '';

    $analysisData['matched_skills'] =
        $analysisData['matched_skills'] ?? ($cl['MatchedSkills'] ?? '');

    $analysisData['missing_skills'] =
        $analysisData['missing_skills'] ?? '';

    $analysisData['detected_degree'] =
        $analysisData['detected_degree'] ?? '';

    $analysisData['experience'] =
        $analysisData['experience'] ?? ($cl['ExperienceMatch'] ?? '');

    $analysisJson = htmlspecialchars(
        json_encode($analysisData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ENT_QUOTES,
        'UTF-8'
    );
?>

    <span class="badge <?= $badgeClass ?> p-2">
        <?= htmlspecialchars($recommendation, ENT_QUOTES, 'UTF-8') ?>
    </span>

    <button type="button"
            class="btn btn-xs btn-outline-info btnScoreHelp d-block mt-1"
            title="View ATS Recommendation Analysis"
            data-analysis="<?= $analysisJson ?>">
        <i class="fas fa-search"></i> View Analysis
    </button>
</td>
                                    <td><?= $cl['CurrentStatus'] ?> ?></td>
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
                    <i class="fas fa-user-check mr-2"></i>
                    ATS Recommendation Analysis
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
       $('#stageAction option[value="Screened"]').show();
   } else {
       $('#stageGroup').hide();
       $('#actionGroup').show();

       // Hide Screened option if candidate is already screened or moved beyond screening
       if (status.includes('screen') || (status !== 'cv uploaded' && status !== 'uploaded' && status !== '')) {
           $('#stageAction option[value="Screened"]').hide();
       } else {
           $('#stageAction option[value="Screened"]').show();
       }
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
                            <p><strong>ATS Recommendation:</strong>
                                <span class="badge ${
                                    (c.ProfileMatchPer === 'Strong Match' || c.ProfileMatchPer === 'Strongly Match' || c.ProfileMatchPer === 'Recommended') ? 'badge-success' :
                                    (c.ProfileMatchPer === 'Low Match' || c.ProfileMatchPer === 'Not Recommended') ? 'badge-danger' :
                                    (c.ProfileMatchPer === 'Potential Match' || c.ProfileMatchPer === 'Review Required') ? 'badge-warning' :
                                    'badge-secondary'}">
                                    ${c.ProfileMatchPer === 'Recommended' ? 'Strong Match' : (c.ProfileMatchPer === 'Review Required' ? 'Potential Match' : (c.ProfileMatchPer === 'Not Recommended' ? 'Low Match' : (c.ProfileMatchPer ?? 'Potential Match')))}
                                </span>
                            </p>
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
    let raw = btn.attr('data-analysis') || '{}';
    let analysis = {};

    try {
        analysis = JSON.parse(raw);
    } catch (e) {
        console.error('ATS analysis parse error:', e);
        analysis = {};
    }

    let recommendation = analysis.recommendation || 'Potential Match';
    if (recommendation === 'Recommended') recommendation = 'Strong Match';
    if (recommendation === 'Review Required') recommendation = 'Potential Match';
    if (recommendation === 'Not Recommended') recommendation = 'Low Match';

    let reason = analysis.recommendation_reason || 'No recommendation reason is available.';
    let evidence = analysis.relevant_evidence || [];
    let missing = analysis.missing_requirements || [];
    let domain = analysis.domain || analysis.candidate_domain || 'Not identified';
    let candidateDomain = analysis.candidate_domain || analysis.domain || 'Not identified';
    let jobDomain = analysis.job_domain || 'Not identified';
    let domainStatus = (analysis.domain_status || 'UNCLEAR').toUpperCase();
    let domainAnalysis = analysis.domain_analysis || {};
    let matchedSkills = analysis.matched_skills || 'Not identified';
    let missingSkills = analysis.missing_skills || 'None identified';
    let detectedDegree = analysis.detected_degree || 'Not identified';
    let experience = analysis.experience || 'Not verified';

    let profile = analysis.candidate_profile || {};
    let headline = profile.headline || 'Candidate';
    let currentRole = profile.current_role || 'Not specified in resume';
    let currentCompany = profile.current_company || 'Not specified in resume';
    let degree = profile.degree || detectedDegree;
    let institution = profile.institution || 'Not specified in resume';
    let gradYear = profile.grad_year || 'Not specified in resume';
    let training = profile.training || 'Not specified in resume';
    let summary = profile.summary || 'Summary not available.';
    let categorizedSkills = profile.categorized_skills || {};
    let workHistory = profile.work_history || [];
    let projects = profile.projects || [];

    if (!Array.isArray(evidence)) evidence = [evidence];
    if (!Array.isArray(missing)) missing = [missing];

    let badgeClass = 'badge-secondary';
    if (recommendation === 'Strong Match' || recommendation === 'Strongly Match' || recommendation === 'Recommended') {
        badgeClass = 'badge-success';
    } else if (recommendation === 'Potential Match' || recommendation === 'Review Required') {
        badgeClass = 'badge-warning';
    } else if (recommendation === 'Low Match' || recommendation === 'Not Recommended' || recommendation === 'Not Suitable') {
        badgeClass = 'badge-danger';
    }

    // Build Categorized Skills HTML
    let skillsCatHtml = '';
    if (Object.keys(categorizedSkills).length > 0) {
        for (let cat in categorizedSkills) {
            let skillsArr = categorizedSkills[cat].split(', ');
            let badgesStr = skillsArr.map(function(s) { return '<span class="badge badge-info mr-1 mb-1">' + s + '</span>'; }).join(' ');
            skillsCatHtml += '<div class="mb-2"><strong class="text-secondary">' + cat + ':</strong><br>' + badgesStr + '</div>';
        }
    } else {
        skillsCatHtml = '<p class="text-muted mb-0">No specific technical skills categorized.</p>';
    }

    // Build Work History HTML
    let workHistHtml = '';
    if (workHistory.length > 0) {
        workHistory.forEach(function(w) {
            workHistHtml += '<li class="mb-2"><i class="fas fa-briefcase text-primary mr-2"></i><strong>' + w.role + '</strong> — ' + w.company + '<small class="text-muted d-block ml-4">' + w.period + ' (' + w.duration + ')</small></li>';
        });
    } else {
        workHistHtml = '<li class="text-muted">No employment history extracted.</li>';
    }

    // Build Projects HTML
    let projectsHtml = '';
    if (projects.length > 0) {
        projects.forEach(function(p) {
            projectsHtml += '<li class="mb-2"><i class="fas fa-folder-open text-info mr-2"></i><strong>' + p.title + '</strong></li>';
        });
    } else {
        projectsHtml = '<li class="text-muted">No explicit projects identified in resume.</li>';
    }

    // Build Evidence HTML
    let evidenceHtml = '';
    evidence.forEach(function(item) {
        if (item && item.toString().trim() !== '') {
            evidenceHtml += '<li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i>' + item + '</li>';
        }
    });
    if (evidenceHtml === '') evidenceHtml = '<li class="text-muted">No specific supporting evidence recorded.</li>';

    // Build Missing Requirements HTML
    let missingHtml = '';
    missing.forEach(function(item) {
        if (item && item.toString().trim() !== '') {
            missingHtml += '<li class="mb-2"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>' + item + '</li>';
        }
    });
    if (missingHtml === '') missingHtml = '<li class="text-success"><i class="fas fa-check-circle mr-2"></i>No major missing requirements identified.</li>';

    let domainWarningHtml = '';
    if (domainStatus === 'WRONG_DOMAIN') {
        domainWarningHtml = `
            <div class="callout callout-danger mb-4">
                <h5 class="text-danger font-weight-bold mb-2"><i class="fas fa-exclamation-circle mr-2"></i>Wrong Domain Resume</h5>
                <p class="mb-2"><strong>Candidate Domain:</strong> ${candidateDomain}</p>
                <p class="mb-2"><strong>Job Domain:</strong> ${jobDomain}</p>
                <p class="mb-0 text-dark">Candidate's professional background is ${candidateDomain}, while this vacancy is ${jobDomain}.</p>
                <p class="mb-0 mt-2"><strong>Final Recommendation:</strong> <span class="badge badge-danger p-2">Not Suitable</span></p>
            </div>
        `;
    }

    let html = `
        <div class="container-fluid">
            ${domainWarningHtml}
            
            <!-- PART A: CANDIDATE PROFILE LAYER -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header bg-light">
                    <h3 class="card-title text-info font-weight-bold mb-0">
                        <i class="fas fa-user mr-2"></i>PART A: CANDIDATE PROFILE OVERVIEW
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="font-weight-bold text-dark mb-1">${headline}</h4>
                            <p class="text-muted mb-2"><i class="fas fa-building mr-1"></i> ${currentRole} at ${currentCompany}</p>
                            <p class="mb-1"><strong><i class="fas fa-graduation-cap mr-1"></i> Education:</strong> ${degree} ${gradYear !== 'Not specified in resume' ? '(' + gradYear + ')' : ''}</p>
                            <p class="mb-1"><strong><i class="fas fa-university mr-1"></i> Institution:</strong> ${institution}</p>
                            <p class="mb-1"><strong><i class="fas fa-certificate mr-1"></i> Training / Specialization:</strong> ${training}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="callout callout-info">
                                <h5><i class="fas fa-file-alt mr-2"></i>Professional Summary</h5>
                                <p class="mb-0 text-dark">${summary}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-secondary h-100">
                                <div class="card-header">
                                    <h5 class="card-title font-weight-bold"><i class="fas fa-layer-group mr-2"></i>Categorized Technical Stack</h5>
                                </div>
                                <div class="card-body">
                                    ${skillsCatHtml}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-outline card-secondary h-100">
                                <div class="card-header">
                                    <h5 class="card-title font-weight-bold"><i class="fas fa-history mr-2"></i>Work History & Projects</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-secondary">Work History:</h6>
                                    <ul class="list-unstyled mb-3">${workHistHtml}</ul>
                                    <h6 class="font-weight-bold text-secondary">Projects:</h6>
                                    <ul class="list-unstyled mb-0">${projectsHtml}</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PART B: EXISTING ATS JOB MATCH EVALUATION -->
            <div class="card card-outline card-primary mb-3">
                <div class="card-header bg-light">
                    <h3 class="card-title text-primary font-weight-bold mb-0">
                        <i class="fas fa-tasks mr-2"></i>PART B: ATS VACANCY MATCH EVALUATION
                    </h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-warning mb-3">
                        <h5 class="mb-2">
                            <strong>Final Recommendation:</strong> 
                            <span class="badge ${badgeClass} p-2 ml-2" style="font-size: 14px;">${recommendation}</span>
                        </h5>
                        <p class="mb-0 text-dark"><strong>Reason:</strong> ${reason}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Candidate Domain:</strong> ${candidateDomain}</p>
                            <p class="mb-1"><strong>Job Domain:</strong> ${jobDomain}</p>
                            <p class="mb-1"><strong>Domain Status:</strong> ${domainStatus}</p>
                            <p class="mb-1"><strong>Experience Match:</strong> ${experience}</p>
                            <p class="mb-1"><strong>Education Match:</strong> ${detectedDegree}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Matched Core Skills:</strong> ${matchedSkills || 'None identified'}</p>
                            <p class="mb-1"><strong>Missing Core Skills:</strong> ${missingSkills || 'None identified'}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-success card-outline mb-0">
                                <div class="card-header">
                                    <h5 class="card-title text-success font-weight-bold"><i class="fas fa-check-circle mr-2"></i>Relevant Evidence</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">${evidenceHtml}</ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-warning card-outline mb-0">
                                <div class="card-header">
                                    <h5 class="card-title text-warning font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Missing / Needs Verification</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">${missingHtml}</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    `;

    $('#scoreBreakdownModalBody').html(html);
    $('#scoreBreakdownModal').modal('show');
});
</script>
 
<script>
$(document).ready(function() {
    setTimeout(function() {
        if ($.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().destroy();
        }
        $('#example1').DataTable({
            "responsive": true,
            "autoWidth": false
        });
        $(window).on('resize orientationchange', function() {
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().columns.adjust().responsive.recalc();
            }
        });
    }, 100);
});
</script>