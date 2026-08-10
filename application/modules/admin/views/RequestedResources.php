<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Requested Resources</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard'); ?>">Home</a></li>
          <li class="breadcrumb-item active">Requested Resources</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<section class="content">
  <div class="container-fluid">

    <?php if ($this->session->flashdata('true')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i> <?= $this->session->flashdata('true'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-1"></i> <?= $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <div class="card card-primary card-outline">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-list-alt mr-2"></i>Resource Requests List</h3>
        <div class="card-tools ml-auto">
          <button type="button" class="btn btn-sm btn-warning font-weight-bold" id="openRequestResourcePanel">
            <i class="fas fa-plus-circle mr-1"></i> Request Resource
          </button>
        </div>
      </div>

      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap" id="requestsTable">
          <thead class="thead-light">
            <tr>
              <th>Request Code</th>
              <th>Job Title / Designation</th>
              <th>Functional Role</th>
              <th>Department</th>
              <th>Openings</th>
              <th>Requested By</th>
              <th>Approver</th>
              <th>Target Onboarding</th>
              <th>Request Date</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($requests)): ?>
              <?php foreach ($requests as $req): ?>
                <tr>
                  <td class="font-weight-bold text-primary"><?= htmlspecialchars($req['RequestCode']); ?></td>
                  <td><?= htmlspecialchars($req['JobTitle']); ?></td>
                  <td><?= htmlspecialchars($req['FunctionalRole'] ? $req['FunctionalRole'] : '-'); ?></td>
                  <td><?= htmlspecialchars($req['Departmentname'] ? $req['Departmentname'] : '-'); ?></td>
                  <td><span class="badge badge-info px-2"><?= (int)$req['NoofOpenings']; ?></span></td>
                  <td><?= htmlspecialchars($req['RequestedByName'] ? $req['RequestedByName'] : '-'); ?></td>
                  <td><?= htmlspecialchars($req['ApproverName'] ? $req['ApproverName'] : '-'); ?></td>
                  <td><?= $req['TargetOnboardingDate'] ? date('d-M-Y', strtotime($req['TargetOnboardingDate'])) : '-'; ?></td>
                  <td><?= date('d-M-Y', strtotime($req['CreatedAt'])); ?></td>
                  <td>
                    <?php if ($req['Status'] === 'PENDING APPROVAL'): ?>
                      <span class="badge badge-warning px-2"><i class="fas fa-clock mr-1"></i>Pending Approval</span>
                    <?php elseif ($req['Status'] === 'ACCEPTED'): ?>
                      <span class="badge badge-success px-2"><i class="fas fa-check-circle mr-1"></i>Accepted</span>
                    <?php elseif ($req['Status'] === 'REJECTED'): ?>
                      <span class="badge badge-danger px-2"><i class="fas fa-times-circle mr-1"></i>Rejected</span>
                    <?php else: ?>
                      <span class="badge badge-secondary px-2"><?= htmlspecialchars($req['Status']); ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <!-- View Details Button -->
                    <button type="button" class="btn btn-info btn-xs mr-1" onclick="viewRequestDetails(<?= htmlspecialchars(json_encode($req)); ?>)">
                      <i class="fas fa-eye"></i> View
                    </button>

                    <!-- Approver Actions -->
                    <?php if ($req['Status'] === 'PENDING APPROVAL'): ?>
                      <button type="button" class="btn btn-success btn-xs mr-1" onclick="openApprovalModal(<?= $req['RequestId']; ?>, 'ACCEPTED', '<?= htmlspecialchars($req['RequestCode']); ?>')">
                        <i class="fas fa-check"></i> Accept
                      </button>
                      <button type="button" class="btn btn-danger btn-xs mr-1" onclick="openApprovalModal(<?= $req['RequestId']; ?>, 'REJECTED', '<?= htmlspecialchars($req['RequestCode']); ?>')">
                        <i class="fas fa-times"></i> Reject
                      </button>
                    <?php endif; ?>

                    <!-- Convert to Vacancy (Recruitment Manager / Management) -->
                    <?php if ($req['Status'] === 'ACCEPTED'): ?>
                      <?php if (!empty($req['ConvertedJid'])): ?>
                        <span class="badge badge-outline-primary"><i class="fas fa-link mr-1"></i>Vacancy Created</span>
                      <?php else: ?>
                        <a href="<?= base_url('admin/convertRequestToVacancy/' . $req['RequestId']); ?>" class="btn btn-primary btn-xs" onclick="return confirm('Convert this approved Resource Request into an active vacancy?');">
                          <i class="fas fa-plus"></i> Create Vacancy
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="11" class="text-center text-muted py-4">
                  <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                  No resource requests found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<!-- ==========================================
     SLIDING PANEL: REQUEST RESOURCE PANEL (RIGHT FORM)
     ========================================== -->
<div id="requestResourcePanel" class="right-form">
  <div class="right-form-header">
    <h5 class="mb-0 font-weight-bold"><i class="fas fa-user-plus mr-2"></i>Request Resource</h5>
    <button type="button" class="close-btn" id="closeRequestResourcePanel">&times;</button>
  </div>

  <div class="right-form-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-default shadow-none border-0">
          <form action="<?= base_url('admin/saveResourceRequest'); ?>" method="post">
            <div class="card-body p-0">
              <div class="bs-stepper">
                
                <div class="bs-stepper-header" role="tablist">
                  <div class="step" data-target="#res-job-part">
                    <button type="button" class="step-trigger" role="tab" aria-controls="res-job-part" id="res-job-part-trigger">
                      <span class="bs-stepper-circle">1</span>
                      <span class="bs-stepper-label">JOB INFO</span>
                    </button>
                  </div>
                  <div class="line"></div>
                  <div class="step" data-target="#res-salary-part">
                    <button type="button" class="step-trigger" role="tab" aria-controls="res-salary-part" id="res-salary-part-trigger">
                      <span class="bs-stepper-circle">2</span>
                      <span class="bs-stepper-label">SALARY & DATES</span>
                    </button>
                  </div>
                  <div class="line"></div>
                  <div class="step" data-target="#res-desc-part">
                    <button type="button" class="step-trigger" role="tab" aria-controls="res-desc-part" id="res-desc-part-trigger">
                      <span class="bs-stepper-circle">3</span>
                      <span class="bs-stepper-label">JD & RESPONSIBILITIES</span>
                    </button>
                  </div>
                </div>

                <div class="bs-stepper-content mt-3">
                  
                  <!-- STEP 1: JOB INFO -->
                  <div id="res-job-part" class="content" role="tabpanel" aria-labelledby="res-job-part-trigger">
                    
                    <div class="form-group">
                      <label class="text-label font-weight-bold">Job Title / Designation <span class="text-danger">*</span></label>
                      <input type="text" name="JobTitle" class="form-control" placeholder="e.g. Senior Software Engineer" required>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Functional Role</label>
                      <input type="text" name="FunctionalRole" class="form-control" placeholder="e.g. Backend Developer">
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Department <span class="text-danger">*</span></label>
                      <select name="Did" class="form-control" required>
                        <option value="">Select Department</option>
                        <?php if (!empty($department)): ?>
                          <?php foreach ($department as $d): ?>
                            <option value="<?= $d['Did']; ?>"><?= htmlspecialchars($d['Departmentname']); ?></option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Position Type <span class="text-danger">*</span></label>
                      <select name="PositionType" class="form-control" required>
                        <option value="New Position">New Position</option>
                        <option value="Replacement">Replacement</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Approver Name <span class="text-danger">*</span></label>
                      <select name="ApproverId" class="form-control" required>
                        <option value="">Select Approver</option>
                        <?php if (!empty($approvers)): ?>
                          <?php foreach ($approvers as $app): ?>
                            <option value="<?= $app['IUid']; ?>"><?= htmlspecialchars($app['EmpName']); ?> (<?= htmlspecialchars($app['RoleName'] ? $app['RoleName'] : 'Approver'); ?>)</option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Reason for Requirement</label>
                      <textarea name="ReasonForRequirement" class="form-control" rows="3" placeholder="State reasons for this resource requirement..."></textarea>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="resStepperNext()">Next <i class="fas fa-arrow-right ml-1"></i></button>
                  </div>

                  <!-- STEP 2: SALARY & DATES -->
                  <div id="res-salary-part" class="content" role="tabpanel" aria-labelledby="res-salary-part-trigger">
                    
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <label class="text-label font-weight-bold">Min Experience (Years)</label>
                          <input type="number" name="ExpMin" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-6">
                          <label class="text-label font-weight-bold">Max Experience (Years)</label>
                          <input type="number" name="ExpMax" class="form-control" value="0" min="0">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <label class="text-label font-weight-bold">Minimum Salary (₹ / Annum)</label>
                          <input type="number" name="SalMin" class="form-control" placeholder="e.g. 500000">
                        </div>
                        <div class="col-md-6">
                          <label class="text-label font-weight-bold">Maximum Salary (₹ / Annum)</label>
                          <input type="number" name="SalMax" class="form-control" placeholder="e.g. 800000">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Recruitment Start Date</label>
                      <input type="date" name="RecruitmentStartDate" class="form-control">
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Target Onboarding Date</label>
                      <input type="date" name="TargetOnboardingDate" class="form-control">
                    </div>

                    <button type="button" class="btn btn-secondary mr-1" onclick="resStepperPrev()"><i class="fas fa-arrow-left mr-1"></i> Previous</button>
                    <button type="button" class="btn btn-primary" onclick="resStepperNext()">Next <i class="fas fa-arrow-right ml-1"></i></button>
                  </div>

                  <!-- STEP 3: JD & RESPONSIBILITIES -->
                  <div id="res-desc-part" class="content" role="tabpanel" aria-labelledby="res-desc-part-trigger">
                    
                    <div class="form-group">
                      <label class="text-label font-weight-bold">Number of Positions / Openings <span class="text-danger">*</span></label>
                      <input type="number" name="NoofOpenings" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Job Description <span class="text-danger">*</span></label>
                      <textarea name="JobDescription" class="form-control" rows="4" placeholder="Enter job description manually..." required></textarea>
                    </div>

                    <div class="form-group">
                      <label class="text-label font-weight-bold">Roles & Responsibilities <span class="text-danger">*</span></label>
                      <textarea name="Responsibilities" class="form-control" rows="4" placeholder="Enter key roles & responsibilities manually..." required></textarea>
                    </div>

                    <button type="button" class="btn btn-secondary mr-1" onclick="resStepperPrev()"><i class="fas fa-arrow-left mr-1"></i> Previous</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-1"></i> Submit Request</button>
                  </div>

                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     VIEW DETAILS MODAL
     ========================================== -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold" id="viewDetailsModalLabel"><i class="fas fa-info-circle mr-2"></i>Resource Request Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="detailsModalContent">
        <!-- Dynamic JS insertion -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     APPROVAL CONFIRMATION MODAL
     ========================================== -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" id="approvalModalHeader">
        <h5 class="modal-title font-weight-bold" id="approvalModalTitle">Decision Confirmation</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="approvalForm" onsubmit="submitApproval(event)">
        <input type="hidden" name="RequestId" id="approvalRequestId">
        <input type="hidden" name="Status" id="approvalStatus">

        <div class="modal-body">
          <p id="approvalTargetText" class="font-weight-bold mb-3"></p>

          <div class="form-group">
            <label class="font-weight-bold">Approval Comments <span class="text-danger">*</span></label>
            <textarea name="ApprovalComment" id="approvalComment" class="form-control" rows="4" placeholder="Enter required approval/rejection comments..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" id="approvalSubmitBtn">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
var resStepperObj = null;

$(document).ready(function() {
  // Panel Open / Close controls
  $('#openRequestResourcePanel').on('click', function() {
    $('#requestResourcePanel').addClass('open');
  });

  $('#closeRequestResourcePanel').on('click', function() {
    $('#requestResourcePanel').removeClass('open');
  });

  // Initialize Stepper
  var stepperEl = document.querySelector('#requestResourcePanel .bs-stepper');
  if (stepperEl && typeof Stepper !== 'undefined') {
    resStepperObj = new Stepper(stepperEl);
  }
});

function resStepperNext() {
  if (resStepperObj) {
    resStepperObj.next();
  }
}

function resStepperPrev() {
  if (resStepperObj) {
    resStepperObj.previous();
  }
}

function viewRequestDetails(req) {
  var html = '<table class="table table-bordered table-sm">' +
    '<tr><th style="width:30%">Request Code</th><td><strong class="text-primary">' + (req.RequestCode || '') + '</strong></td></tr>' +
    '<tr><th>Job Title / Designation</th><td>' + (req.JobTitle || '') + '</td></tr>' +
    '<tr><th>Functional Role</th><td>' + (req.FunctionalRole || '-') + '</td></tr>' +
    '<tr><th>Department</th><td>' + (req.Departmentname || '-') + '</td></tr>' +
    '<tr><th>Number of Positions</th><td>' + (req.NoofOpenings || 1) + '</td></tr>' +
    '<tr><th>Position Type</th><td>' + (req.PositionType || 'New Position') + '</td></tr>' +
    '<tr><th>Experience</th><td>' + (req.ExpMin || 0) + ' - ' + (req.ExpMax || 0) + ' Years</td></tr>' +
    '<tr><th>Salary Range</th><td>₹ ' + (req.SalMin || 0) + ' - ₹ ' + (req.SalMax || 0) + '</td></tr>' +
    '<tr><th>Start Date</th><td>' + (req.RecruitmentStartDate || '-') + '</td></tr>' +
    '<tr><th>Target Onboarding Date</th><td>' + (req.TargetOnboardingDate || '-') + '</td></tr>' +
    '<tr><th>Reason for Requirement</th><td>' + (req.ReasonForRequirement || '-') + '</td></tr>' +
    '<tr><th>Job Description</th><td><pre style="white-space:pre-wrap; font-family:inherit;">' + (req.JobDescription || '') + '</pre></td></tr>' +
    '<tr><th>Roles & Responsibilities</th><td><pre style="white-space:pre-wrap; font-family:inherit;">' + (req.Responsibilities || '') + '</pre></td></tr>' +
    '<tr><th>Requested By</th><td>' + (req.RequestedByName || '-') + '</td></tr>' +
    '<tr><th>Approver</th><td>' + (req.ApproverName || '-') + '</td></tr>' +
    '<tr><th>Request Date</th><td>' + (req.CreatedAt || '') + '</td></tr>' +
    '<tr><th>Status</th><td><strong>' + (req.Status || '') + '</strong></td></tr>';

  if (req.ApprovalComment) {
    html += '<tr><th>Approver Comment</th><td class="text-danger">' + req.ApprovalComment + '</td></tr>';
    html += '<tr><th>Actioned At</th><td>' + (req.ActionedAt || '') + '</td></tr>';
  }

  html += '</table>';
  $('#detailsModalContent').html(html);
  $('#viewDetailsModal').modal('show');
}

function openApprovalModal(requestId, status, requestCode) {
  $('#approvalRequestId').val(requestId);
  $('#approvalStatus').val(status);
  $('#approvalComment').val('');

  var header = $('#approvalModalHeader');
  var btn = $('#approvalSubmitBtn');

  if (status === 'ACCEPTED') {
    header.attr('class', 'modal-header bg-success text-white');
    $('#approvalModalTitle').text('Accept Resource Request [' + requestCode + ']');
    $('#approvalTargetText').html('You are about to <span class="text-success font-weight-bold">ACCEPT</span> request <code>' + requestCode + '</code>.');
    btn.attr('class', 'btn btn-success').html('<i class="fas fa-check mr-1"></i> Confirm Acceptance');
  } else {
    header.attr('class', 'modal-header bg-danger text-white');
    $('#approvalModalTitle').text('Reject Resource Request [' + requestCode + ']');
    $('#approvalTargetText').html('You are about to <span class="text-danger font-weight-bold">REJECT</span> request <code>' + requestCode + '</code>.');
    btn.attr('class', 'btn btn-danger').html('<i class="fas fa-times mr-1"></i> Confirm Rejection');
  }

  $('#approvalModal').modal('show');
}

function submitApproval(e) {
  e.preventDefault();
  var comment = $('#approvalComment').val().trim();
  if (!comment) {
    alert('Approval Comments are mandatory.');
    return;
  }

  $.ajax({
    url: '<?= base_url("admin/updateResourceRequestStatus"); ?>',
    type: 'POST',
    data: $('#approvalForm').serialize(),
    dataType: 'json',
    success: function(res) {
      if (res.status === 'success') {
        $('#approvalModal').modal('hide');
        location.reload();
      } else {
        alert(res.message || 'Error updating status');
      }
    },
    error: function() {
      alert('Network or server error.');
    }
  });
}
</script>
