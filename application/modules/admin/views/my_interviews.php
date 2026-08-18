<?php
// Find the nearest upcoming or today's interview (future only — no past fallback)
$next_interview = null;
$min_diff       = PHP_INT_MAX;
$now            = time();
$today_start    = strtotime('today');

if(!empty($Candidatelist)) {
    foreach($Candidatelist as $cl) {
        $resultLower = strtolower(trim($cl['Result'] ?? ''));
        if(($resultLower === 'assigned' || $resultLower === '') && !empty($cl['ScheduledAt']) && $cl['ScheduledAt'] !== '0000-00-00 00:00:00') {
            $scheduled_time = strtotime($cl['ScheduledAt']);

            // Only include interviews from today onwards (not past ones)
            if($scheduled_time >= $today_start && $scheduled_time > 0) {
                $diff = abs($scheduled_time - $now);
                if($diff < $min_diff) {
                    $min_diff       = $diff;
                    $next_interview = $cl;
                }
            }
        }
    }
}
// If $next_interview is still null, no countdown banner will be shown
?>
<section class="content">
<div class="container-fluid">

<div class="card card-warning card-outline">

<div class="card-header">
<div class="d-flex justify-content-between align-items-center">

<h3 class="card-title mb-0">
Assigned Interview
</h3>

</div>
</div>

<div class="card-body">

<?php if($next_interview): ?>
    <?php
    $interview_ts = strtotime($next_interview['ScheduledAt']);
    // Calculate remaining days
    $today_date = new DateTime('today');
    $interview_date = new DateTime(date('Y-m-d', $interview_ts));
    $interval = $today_date->diff($interview_date);
    $days = (int)$interval->format('%r%a');
    if ($days < 0) {
        $days = 0;
    }
    $remaining_text = "Remaining: " . $days . " Day" . ($days === 1 ? "" : "s");
    ?>
    <div class="interview-summary-card">
        <div class="interview-summary-title">
            <i class="fas fa-calendar-check mr-2 text-primary"></i>Next Upcoming Interview
        </div>
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="interview-summary-item">
                    <span class="label">Candidate Name:</span>
                    <span class="value"><?= htmlspecialchars($next_interview['Fullname']) ?></span>
                </div>
                <div class="interview-summary-item">
                    <span class="label">Candidate Code:</span>
                    <span class="badge badge-secondary"><?= htmlspecialchars($next_interview['CandidateCode']) ?></span>
                </div>
                <div class="interview-summary-item">
                    <span class="label">Interview Date:</span>
                    <span class="value"><?= date('l, d F Y - h:i A', $interview_ts) ?></span>
                </div>
                <div class="interview-summary-item mt-1">
                    <span class="label">Interview Mode:</span>
                    <?php
                    $nextMode = !empty($next_interview['InterviewType']) ? trim($next_interview['InterviewType']) : 'N/A';
                    $nextLink = !empty($next_interview['MeetLink']) ? trim($next_interview['MeetLink']) : '';
                    if (strtolower($nextMode) === 'online'):
                    ?>
                        <span class="badge badge-primary"><i class="fas fa-video mr-1"></i>Online</span>
                        <?php if (!empty($nextLink)): ?>
                            <a href="<?= htmlspecialchars($nextLink) ?>" target="_blank" class="btn btn-sm btn-success ml-2 font-weight-bold"><i class="fas fa-video mr-1"></i>Join Video Meeting</a>
                        <?php endif; ?>
                    <?php elseif (strtolower($nextMode) === 'offline'): ?>
                        <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i>Offline (In-Person)</span>
                    <?php else: ?>
                        <span class="badge badge-light"><?= htmlspecialchars($nextMode) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <span class="badge badge-info interview-remaining-badge mb-2">
                    <i class="fas fa-hourglass-half mr-1"></i><?= $remaining_text ?>
                </span>
                <br>
                <button type="button" class="btn btn-sm btn-info viewCandidateDetails font-weight-bold mt-1" data-id="<?= $next_interview['CandidateId'] ?>">
                    <i class="fas fa-road mr-1"></i>View Total Track
                </button>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="interview-summary-card">
        <div class="text-muted font-weight-bold">
            <i class="fas fa-info-circle mr-2 text-info"></i>No upcoming interviews scheduled.
        </div>
    </div>
<?php endif; ?>

<div class="mb-3">

<ul class="nav nav-pills nav-pills-sm nav-justified">

<li class="nav-item">
<a class="nav-link active interviewFilter rounded-pill" data-status="">
All
</a>
</li>

<li class="nav-item">
<a class="nav-link interviewFilter rounded-pill" data-status="Assigned">
Assigned
</a>
</li>

<li class="nav-item">
<a class="nav-link interviewFilter rounded-pill" data-status="Selected">
Selected
</a>
</li>

<li class="nav-item">
<a class="nav-link interviewFilter rounded-pill" data-status="Rejected">
Rejected
</a>
</li>

</ul>

</div>

<table id="example1" class="table table-bordered table-striped align-middle mb-0" style="width: 100% !important;">

<thead class="bg-success text-white">
<tr>
<th style="width: 4%;">S.No</th>
<th>Code</th>
<th>Name</th>
<th>Mobile No</th>
<th>Email</th>
<th class="text-center">Score</th>
<th class="text-center">Mode</th>
<th>Scheduled Time</th>
<th class="text-center">Current Status</th>
<th>Verified On</th>
<th class="text-center">Action</th>
</tr>
</thead>

<tbody>

<?php if(!empty($Candidatelist)){ $i=1; foreach($Candidatelist as $cl){ 
    $resultVal   = !empty($cl['Result']) ? trim($cl['Result']) : 'Assigned';
    $resultLower = strtolower($resultVal);
    $isRescheduledRow = ($resultLower === 'rescheduled');
    $trClass = $isRescheduledRow ? 'style="background-color: #fff9e6;"' : '';
?>

<tr <?= $trClass ?>>
<td class="text-center font-weight-bold"><?= $i++; ?></td>
<td>
  <a href="<?= base_url('admin/viewResume/'.$cl['CandidateId']); ?>" target="_blank" class="badge badge-pill badge-primary px-2 py-1 font-weight-bold" style="font-size: 11.5px;">
    <?= $cl['CandidateCode']; ?>
  </a>
</td>

<td>
  <a href="javascript:void(0);" class="viewCandidateDetails text-primary font-weight-bold" data-id="<?= $cl['CandidateId']; ?>">
    <?= htmlspecialchars($cl['Fullname']); ?>
  </a>
</td>

<td>
  <span class="text-dark font-weight-bold"><i class="fas fa-phone text-muted mr-1"></i><?= htmlspecialchars($cl['PhoneNo']); ?></span>
</td>

<td>
  <span class="text-muted small"><i class="fas fa-envelope text-primary mr-1"></i><?= htmlspecialchars($cl['Email']); ?></span>
</td>

<td class="text-center">
    <?php 
    $recVal = !empty($cl['ProfileMatchPer']) ? $cl['ProfileMatchPer'] : 'Review Required';
    $badgeClass = (stripos($recVal, 'Recommended') !== false && stripos($recVal, 'Not') === false) ? 'badge-success' : (stripos($recVal, 'Not') !== false ? 'badge-danger' : 'badge-warning');
    ?>
    <span class="badge <?= $badgeClass ?> font-weight-bold px-2 py-1"><?= htmlspecialchars($recVal) ?></span>
</td>

<td class="text-center">
    <?php
    $mode = !empty($cl['InterviewType']) ? trim($cl['InterviewType']) : '';
    $meetLink = !empty($cl['MeetLink']) ? trim($cl['MeetLink']) : '';
    if (strtolower($mode) === 'online'):
    ?>
        <span class="badge badge-primary"><i class="fas fa-video mr-1"></i>Online</span>
        <?php if (!empty($meetLink) && !$isRescheduledRow): ?>
            <a href="<?= htmlspecialchars($meetLink) ?>" target="_blank" class="btn btn-xs btn-outline-primary ml-1" title="Join Video Meeting"><i class="fas fa-video mr-1"></i>Join</a>
        <?php endif; ?>
    <?php elseif (strtolower($mode) === 'offline'): ?>
        <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i>Offline</span>
    <?php else: ?>
        <span class="badge badge-light"><?= !empty($mode) ? htmlspecialchars($mode) : 'N/A' ?></span>
    <?php endif; ?>
</td>

<td>
    <?php
    $scheduledAt = $cl['ScheduledAt'] ?? '';
    if (!empty($scheduledAt) && $scheduledAt !== '0000-00-00 00:00:00') {
        $ts = strtotime($scheduledAt);
        $dateFormatted = ($ts && $ts > 0) ? date('d M Y, h:i A', $ts) : '-';
        if ($isRescheduledRow) {
            echo '<del class="text-muted">' . $dateFormatted . '</del> <span class="badge badge-warning text-dark ml-1"><i class="fas fa-history mr-1"></i>Rescheduled</span>';
        } else {
            echo '<span class="text-dark font-weight-bold"><i class="fas fa-calendar-alt text-info mr-1"></i>' . $dateFormatted . '</span>';
        }
    } else {
        echo '<span class="text-muted small">Not Scheduled</span>';
    }
    ?>
</td>

<td class="text-center">
    <?php
    if ($isRescheduledRow) {
        echo '<span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-history mr-1"></i>Rescheduled</span>';
    } elseif ($resultLower === 'selected') {
        echo '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Selected</span>';
    } elseif ($resultLower === 'rejected') {
        echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i>Rejected</span>';
    } elseif ($resultLower === 'on hold') {
        echo '<span class="badge badge-warning px-2 py-1"><i class="fas fa-pause-circle mr-1"></i>On Hold</span>';
    } else {
        echo '<span class="badge badge-primary px-2 py-1"><i class="fas fa-clock mr-1"></i>' . htmlspecialchars($resultVal) . '</span>';
    }
    ?>
</td>

<td>
    <span class="small text-muted"><?= !empty($cl['AppliedOn']) ? date('d M Y, h:i A', strtotime($cl['AppliedOn'])) : '-'; ?></span>
</td>

<td class="text-center">
    <button type="button" class="btn btn-xs btn-info viewCandidateDetails mr-1 mb-1" data-id="<?= $cl['CandidateId']; ?>" title="View Candidate Track Timeline">
      <i class="fas fa-eye mr-1"></i> View Track
    </button>
    <button type="button" class="btn btn-xs btn-primary openAiQuestionsModal mr-1 mb-1" data-interview="<?= (int)($cl['InterviewId'] ?? 0); ?>" data-candidate="<?= htmlspecialchars($cl['Fullname'] ?? ''); ?>" data-job="<?= htmlspecialchars($cl['JobTitle'] ?? ''); ?>" data-score="<?= htmlspecialchars($cl['ProfileMatchPer'] ?? 'N/A'); ?>" title="AI Personalized Interview Questions">
      <i class="fas fa-brain mr-1"></i> AI Questions
    </button>
    <?php if(($resultLower == '' || $resultLower == 'assigned' || $resultLower == 'on hold') && !$isRescheduledRow): ?>
      <button type="button" class="btn btn-xs btn-warning openInterviewUpdate mb-1" data-interview="<?= $cl['InterviewId']; ?>" title="Update Interview Status">
        <i class="fas fa-edit mr-1"></i> Update Status
      </button>
    <?php endif; ?>
</td>
</tr>

<?php }} ?>

</tbody>
</table>


<!-- Interview Update Panel -->

<div id="interviewPanel" class="right-form">

<div class="right-form-header">
<h5>Update Interview Result</h5>
<button type="button" class="close-btn" id="closeInterviewPanel">&times;</button>
</div>

<div class="right-form-body">

<input type="hidden" id="interviewId">

<div class="form-group">
<label>Opinion</label>
<select id="interviewResult" class="form-control">
<option value="">Select Result</option>
<option value="Selected">Shortlisted</option>
<option value="Rejected">Rejected</option>
<option value="On Hold">On Hold</option>
</select>
</div>

<div class="form-group">
<label>Feedback</label>
<textarea id="interviewFeedback" class="form-control"></textarea>
</div>

<button class="btn btn-success" id="saveInterviewResult">
Save
</button>

</div>
</div>

</div>
</div>
</div>
</section>



<!-- Candidate Details Modal -->

<div class="modal fade" id="candidateDetailsModal" tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<div class="modal-header bg-info text-white">
<h5 class="modal-title"><i class="fas fa-route mr-2"></i>Candidate Total Journey Track</h5>
<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body" id="candidateDetailsBody">

<div class="text-center">
<i class="fa fa-spinner fa-spin"></i> Loading...
</div>

</div>

</div>
</div>
</div>

<script>

var base_url = "<?= base_url(); ?>";
var interviewTable = null;

function initInterviewDataTable() {
    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable().destroy();
    }
    if ($.fn.DataTable) {
        interviewTable = $('#example1').DataTable({
            responsive: true,
            autoWidth: false,
            columnDefs: [
                { orderable: false, targets: 0 }
            ]
        });
    }
}

$(document).ready(function () {

    setTimeout(function () {
        initInterviewDataTable();
    }, 100);

    // Re-adjust DataTables columns and recalc responsive status on window resize (Maximize / Minimize)
    $(window).on('resize orientationchange', function () {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().columns.adjust().responsive.recalc();
        }
    });

    $(document).on('click', '.interviewFilter', function (e) {
        e.preventDefault();

        $('.interviewFilter').removeClass('active');
        $(this).addClass('active');

        let status = $(this).data('status');

        $.ajax({
            url: "<?= base_url('admin/filterAssignedInterviews') ?>",
            type: "POST",
            data: { status: status },
            success: function (res) {
                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example1')) {
                    $('#example1').DataTable().destroy();
                }
                $('#example1 tbody').html(res);
                initInterviewDataTable();
            },
            error: function (xhr) {
                console.log('ERROR:', xhr.responseText);
            }
        });
    });
    /* ===== Save Interview Result ===== */
    $(document).on('click', '#saveInterviewResult', function () {

        $.post('<?= base_url("admin/updateInterviewResult") ?>', {
            interviewId: $('#interviewId').val(),
            result:      $('#interviewResult').val(),
            feedback:    $('#interviewFeedback').val()
        }, function (res) {

            let r = JSON.parse(res);

            if (r.status == 'success') {
               
                location.reload();
            } else {
                toastr.error('Error updating');
            }
        });
    });

    /* ===== Open Interview Update Panel ===== */
    $(document).on('click', '.openInterviewUpdate', function () {

        let interviewId = $(this).data('interview');

        $('#interviewId').val(interviewId);
        $('#interviewResult').val('');
        $('#interviewFeedback').val('');

        $('#interviewPanel').show();
    });

    /* ===== Close Interview Panel ===== */
    $('#closeInterviewPanel').on('click', function () {
        $('#interviewPanel').hide();
    });

    /* ===== View Candidate Details & Total Track ===== */
    $(document).on('click', '.viewCandidateDetails', function () {

        let candidateId = $(this).data('id');

        $('#candidateDetailsModal').modal('show');
        $('#candidateDetailsBody').html(
            '<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>'
        );

        $.ajax({
            url: "<?= base_url('admin/getCandidateIdDetails') ?>",
            type: "POST",
            data: { candidate_id: candidateId },
            dataType: "json",
            success: function (res) {

                if (res.status !== 'success') {
                    $('#candidateDetailsBody').html('<div class="alert alert-danger">No candidate data found</div>');
                    return;
                }

                let c = res.data.candidate;
                let stages = res.data.stages || [];
                let interviews = res.data.interviews || [];

                let html = `<div class="container-fluid">`;

                html += `
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0"><i class="fas fa-id-card mr-2"></i>Candidate Profile & Position Info</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Name:</strong> ${c.Fullname ?? '-'}</p>
                                <p class="mb-1"><strong>Candidate Code:</strong> <span class="badge badge-secondary">${c.CandidateCode ?? '-'}</span></p>
                                <p class="mb-1"><strong>Applied Position:</strong> ${c.JobTitle ?? '-'}</p>
                                <p class="mb-1"><strong>Email:</strong> ${c.Email ?? '-'}</p>
                                <p class="mb-1"><strong>Phone:</strong> ${c.PhoneNo ?? '-'}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Experience:</strong> ${c.ExpYrs ?? 0} Years</p>
                                <p class="mb-1"><strong>ATS Recommendation:</strong> <span class="badge ${ (c.ProfileMatchPer && c.ProfileMatchPer.includes('Recommended') && !c.ProfileMatchPer.includes('Not')) ? 'badge-success' : ((c.ProfileMatchPer && c.ProfileMatchPer.includes('Not')) ? 'badge-danger' : 'badge-warning') }">${c.ProfileMatchPer ?? 'Review Required'}</span></p>
                                <p class="mb-1"><strong>Current Status:</strong> <span class="badge badge-info">${c.CurrentStatus ?? '-'}</span></p>
                                <p class="mb-1"><strong>Applied Date:</strong> ${c.AppliedOn ?? '-'}</p>
                            </div>
                        </div>
                    </div>
                </div>`;

                html += `<h4 class="mb-3 font-weight-bold text-dark"><i class="fas fa-route mr-2 text-warning"></i>Candidate Total Journey Track</h4>`;
                html += `<div class="timeline timeline-inverse">`;

                if (stages.length > 0) {
                    stages.forEach(function (s) {
                        let badgeColor = 'bg-info';
                        let act = (s.Action || '').toLowerCase();
                        if (act.includes('rejected')) badgeColor = 'bg-danger';
                        else if (act.includes('shortlisted')) badgeColor = 'bg-success';
                        else if (act.includes('hold')) badgeColor = 'bg-warning';

                        html += `
                        <div>
                            <i class="fas fa-user-tag ${badgeColor}"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> ${s.ActionAt ?? '-'}</span>
                                <h3 class="timeline-header font-weight-bold text-primary">${s.StageName ?? 'Stage Update'}</h3>
                                <div class="timeline-body">
                                    <p class="mb-1"><strong>Action:</strong> <span class="badge ${badgeColor.replace('bg-', 'badge-')}">${s.Action ?? '-'}</span></p>
                                    <p class="mb-1"><strong>Updated By:</strong> ${s.ActionByName ?? 'HR Admin'}</p>
                                    <p class="mb-0"><strong>Remarks:</strong> ${s.Remarks ?? 'No remarks provided'}</p>
                                </div>
                            </div>
                        </div>`;
                    });
                }

                if (interviews.length > 0) {
                    interviews.forEach(function(iv) {
                        let ivMode = (iv.InterviewType || 'N/A');
                        let isOnline = ivMode.toLowerCase() === 'online';
                        let meetBtn = (isOnline && iv.MeetLink) 
                            ? `<br><a href="${iv.MeetLink}" target="_blank" class="btn btn-xs btn-primary mt-2"><i class="fas fa-video mr-1"></i>Join Video Meeting</a>` 
                            : '';

                        html += `
                        <div>
                            <i class="fas fa-calendar-check bg-warning"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="far fa-clock"></i> ${iv.ScheduledAt ?? '-'}</span>
                                <h3 class="timeline-header font-weight-bold text-dark">Interview Round ${iv.InterviewRound ?? 1} (${ivMode})</h3>
                                <div class="timeline-body">
                                    <p class="mb-1"><strong>Mode:</strong> ${ivMode}</p>
                                    <p class="mb-1"><strong>Scheduled Time:</strong> ${iv.ScheduledAt ?? '-'}</p>
                                    <p class="mb-1"><strong>Result / Status:</strong> <span class="badge badge-warning">${iv.Result || 'Assigned'}</span></p>
                                    ${meetBtn}
                                </div>
                            </div>
                        </div>`;
                    });
                }

                if (stages.length === 0 && interviews.length === 0) {
                    html += `<p class="text-muted p-2">No stage tracking or interview history found for this candidate.</p>`;
                }

                html += `<div><i class="far fa-clock bg-gray"></i></div></div></div>`;

                $('#candidateDetailsBody').html(html);
            }
        });
    });

    // ============================================================
    // AI PERSONALIZED INTERVIEW QUESTIONS ENGINE
    // ============================================================
    let activeInterviewId = null;
    let loadedQuestions = [];

    function updateSkillCoverageAndSource(res) {
        if (res.source) {
            const isAi = (res.source === 'ai');
            $('#aiSourceBadge')
                .toggleClass('badge-success', isAi)
                .toggleClass('badge-secondary', !isAi)
                .html(`<i class="fas ${isAi ? 'fa-robot' : 'fa-cog'} mr-1"></i> Source: ${isAi ? 'AI Engine' : 'Fallback Engine'}`)
                .show();
        } else {
            $('#aiSourceBadge').hide();
        }

        const covered   = res.covered_must_have_skills || [];
        const uncovered = res.uncovered_must_have_skills || [];

        if (covered.length > 0 || uncovered.length > 0) {
            let tagsHtml = '';
            covered.forEach(s => {
                tagsHtml += `<span class="badge badge-success px-2 py-1 font-weight-bold mr-1"><i class="fas fa-check mr-1"></i>${s}</span>`;
            });
            $('#aiSkillCoverageTags').html(tagsHtml || '<span class="text-muted small">None specified</span>');

            if (uncovered.length > 0) {
                let unTags = '';
                uncovered.forEach(s => {
                    unTags += `<span class="badge badge-danger px-2 py-1 font-weight-bold mr-1">${s}</span>`;
                });
                $('#aiUncoveredTags').html(unTags);
                $('#aiUncoveredWarnWrap').show();
            } else {
                $('#aiUncoveredWarnWrap').hide();
            }
            $('#aiSkillCoverageBar').show();
        } else {
            $('#aiSkillCoverageBar').hide();
        }
    }

    function updateCategoryCounts() {
        if (!loadedQuestions) return;
        const total = loadedQuestions.length;
        const must  = loadedQuestions.filter(q => {
            const t = (q.question_type || '').toLowerCase();
            return t === 'must_have_skill' || t === 'technical';
        }).length;
        const cand  = loadedQuestions.filter(q => (q.question_type || '').toLowerCase() === 'candidate_specific').length;
        const scen  = loadedQuestions.filter(q => (q.question_type || '').toLowerCase() === 'scenario').length;
        const beh   = loadedQuestions.filter(q => (q.question_type || '').toLowerCase() === 'behavioral').length;

        $('#tabCatAll').html(`<i class="fas fa-layer-group mr-1"></i> All Questions (${total})`);
        $('#tabCatMustHave').html(`<i class="fas fa-star mr-1 text-warning"></i> Must-Have Skills (${must})`);
        $('#tabCatCand').html(`<i class="fas fa-user-check mr-1 text-success"></i> Candidate-Specific (${cand})`);
        $('#tabCatScen').html(`<i class="fas fa-lightbulb mr-1 text-info"></i> Scenario (${scen})`);
        $('#tabCatBeh').html(`<i class="fas fa-users mr-1 text-purple" style="color:#7c3aed;"></i> Behavioral (${beh})`);
    }

    function renderQuestionsList(catFilter = 'all') {
        const listEl = $('#aiQuestionsList');
        if (!loadedQuestions || loadedQuestions.length === 0) {
            listEl.html(`<div class="text-center py-5 text-muted font-weight-bold">No AI interview questions available for this candidate.</div>`);
            return;
        }

        let filtered = loadedQuestions;
        if (catFilter !== 'all') {
            filtered = loadedQuestions.filter(q => {
                const t = (q.question_type || '').toLowerCase();
                if (catFilter === 'must_have_skill') return t === 'must_have_skill' || t === 'technical';
                return t === catFilter.toLowerCase();
            });
        }

        if (filtered.length === 0) {
            listEl.html(`<div class="text-center py-4 text-muted font-weight-bold">No questions found under this category.</div>`);
            return;
        }

        let html = '';
        filtered.forEach((q, idx) => {
            const type = (q.question_type || 'technical').toLowerCase();
            let typeBadgeClass = 'badge-primary';
            let typeLabel = 'Technical';
            if (type === 'must_have_skill') {
                typeBadgeClass = 'badge-warning text-dark';
                typeLabel = 'Must-Have Skill';
            } else if (type === 'candidate_specific') {
                typeBadgeClass = 'badge-success';
                typeLabel = 'Candidate-Specific';
            } else if (type === 'scenario') {
                typeBadgeClass = 'badge-info';
                typeLabel = 'Scenario';
            } else if (type === 'behavioral') {
                typeBadgeClass = 'badge-purple';
                typeLabel = 'Behavioral';
            }

            const diff = (q.difficulty || 'medium').toLowerCase();
            let diffBadgeClass = 'badge-info';
            if (diff.includes('beginner')) diffBadgeClass = 'badge-light border text-muted';
            else if (diff.includes('advanced') || diff.includes('hard')) diffBadgeClass = 'badge-danger';
            else if (diff.includes('intermediate')) diffBadgeClass = 'badge-warning text-dark';

            const status = (q.status_notes || 'unasked').toLowerCase();
            let statusBtnUnasked  = status === 'unasked' ? 'btn-secondary active' : 'btn-outline-secondary';
            let statusBtnAsked    = status === 'asked' ? 'btn-info active' : 'btn-outline-info';
            let statusBtnAnswered = status === 'answered' ? 'btn-success active' : 'btn-outline-success';
            let statusBtnSkipped  = status === 'skipped' ? 'btn-danger active' : 'btn-outline-danger';

            html += `
            <div class="card mb-3 border shadow-sm style="border-radius:10px; overflow:hidden;">
              <div class="card-header bg-white d-flex align-items-center justify-content-between py-2 px-3">
                <div>
                  <span class="badge ${typeBadgeClass} font-weight-bold mr-2 px-2 py-1">${typeLabel}</span>
                  <span class="badge ${diffBadgeClass} font-weight-bold px-2 py-1">${q.difficulty || 'Medium'}</span>
                  ${q.skill ? `<span class="badge badge-light border text-primary font-weight-bold ml-2 px-2 py-1"><i class="fas fa-tag mr-1"></i>${q.skill}</span>` : ''}
                </div>
                <small class="text-muted font-weight-bold">#${idx + 1}</small>
              </div>
              <div class="card-body p-3">
                <p class="font-weight-bold text-dark mb-2" style="font-size:14.5px; line-height:1.45;">
                  ${q.question}
                </p>
                ${q.reason ? `<div class="p-2 mb-2 bg-light border-left border-primary rounded text-muted small"><i class="fas fa-info-circle text-primary mr-1"></i><strong>Reasoning:</strong> ${q.reason}</div>` : ''}
                <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap gap-2">
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <button type="button" class="btn btn-xs ${statusBtnUnasked} q-status-btn" data-qid="${q.id}" data-status="unasked">Unasked</button>
                    <button type="button" class="btn btn-xs ${statusBtnAsked} q-status-btn" data-qid="${q.id}" data-status="asked">Asked</button>
                    <button type="button" class="btn btn-xs ${statusBtnAnswered} q-status-btn" data-qid="${q.id}" data-status="answered">Answered</button>
                    <button type="button" class="btn btn-xs ${statusBtnSkipped} q-status-btn" data-qid="${q.id}" data-status="skipped">Skipped</button>
                  </div>
                </div>
              </div>
            </div>`;
        });

        listEl.html(html);
    }

    function fetchAndLoadQuestions(interviewId, version = null, triggerBtn = null) {
        $('#aiQuestionsList').html(`
          <div class="text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
            <p class="font-weight-bold mb-0">Loading AI questions...</p>
          </div>
        `);

        $.ajax({
            url: '<?= base_url('admin/getAiInterviewQuestions'); ?>',
            type: 'POST',
            data: { interviewId: interviewId, version: version },
            success: function (rawRes) {
                let res;
                try { res = (typeof rawRes === 'object') ? rawRes : JSON.parse(rawRes); }
                catch(e) {
                    $('#aiQuestionsList').html(`<div class="alert alert-danger font-weight-bold"><strong>Parse Error:</strong><pre style="font-size:11px;">${rawRes.substring(0,500)}</pre></div>`);
                    return;
                }
                if (res.status === 'success') {
                    loadedQuestions = res.questions || [];

                    if (loadedQuestions.length === 0 && version === null) {
                        generateAiQuestions(interviewId, false, triggerBtn);
                        return;
                    }

                    if (res.candidate_name) $('#aiCandName').text(res.candidate_name);
                    if (res.job_title) $('#aiCandJob').text(res.job_title);
                    if (res.ats_score) $('#aiAtsBadge').text(`ATS Fit Match: ${res.ats_score}`);

                    if (res.available_versions && res.available_versions.length > 1) {
                        $('#aiVerDropdownWrap').show();
                        let vMenu = '';
                        res.available_versions.forEach(v => {
                            vMenu += `<a class="dropdown-item ai-ver-item font-weight-bold" href="javascript:void(0);" data-ver="${v}">Version ${v}</a>`;
                        });
                        $('#aiVerMenu').html(vMenu);
                    } else {
                        $('#aiVerDropdownWrap').hide();
                    }

                    updateSkillCoverageAndSource(res);
                    updateCategoryCounts();
                    renderQuestionsList('all');
                    $('#aiCategoryTabs a[data-cat="all"]').tab('show');
                } else {
                    $('#aiQuestionsList').html(`<div class="alert alert-danger font-weight-bold">${res.message || 'Failed to load questions.'}</div>`);
                }
            },
            error: function (xhr) {
                $('#aiQuestionsList').html(`<div class="alert alert-danger font-weight-bold"><strong>HTTP Error ${xhr.status}:</strong><pre style="font-size:11px;">${xhr.responseText ? xhr.responseText.substring(0,500) : 'No response'}</pre></div>`);
            }
        });
    }

    function generateAiQuestions(interviewId, isRegeneration = false, triggerBtn = null) {
        $('#aiQuestionsList').html(`
          <div class="text-center py-5 text-muted">
            <i class="fas fa-brain fa-pulse fa-3x mb-3 text-warning"></i>
            <h5 class="font-weight-bold text-dark mb-1">Generating Candidate-Personalized Questions...</h5>
            <p class="text-muted small">Parsing resume claims, vacancy criteria, ATS analysis, and verifying cross-candidate non-duplication rules...</p>
          </div>
        `);

        $.ajax({
            url: '<?= base_url('admin/generateAiInterviewQuestions'); ?>',
            type: 'POST',
            data: { interviewId: interviewId, isRegeneration: isRegeneration ? 1 : 0 },
            success: function (rawRes) {
                let res;
                try { res = (typeof rawRes === 'object') ? rawRes : JSON.parse(rawRes); }
                catch(e) {
                    $('#aiQuestionsList').html(`<div class="alert alert-danger font-weight-bold"><strong>Parse Error:</strong><pre style="font-size:11px;">${rawRes.substring(0,500)}</pre></div>`);
                    return;
                }
                if (res.status === 'success') {
                    loadedQuestions = res.questions || [];
                    if (res.candidate_name) $('#aiCandName').text(res.candidate_name);
                    if (res.job_title) $('#aiCandJob').text(res.job_title);
                    if (res.ats_score) $('#aiAtsBadge').text(`ATS Fit Match: ${res.ats_score}`);

                    updateSkillCoverageAndSource(res);
                    updateCategoryCounts();
                    renderQuestionsList('all');

                    if (triggerBtn) {
                        triggerBtn.removeClass('btn-outline-primary btn-primary').addClass('btn-success').html('<i class="fas fa-list-ol mr-1"></i> View AI Questions');
                    } else if (activeInterviewId) {
                        $(`.openAiQuestionsModal[data-interview="${activeInterviewId}"]`).removeClass('btn-outline-primary btn-primary').addClass('btn-success').html('<i class="fas fa-list-ol mr-1"></i> View AI Questions');
                    }
                } else {
                    $('#aiQuestionsList').html(`<div class="alert alert-danger font-weight-bold">${res.message || 'Failed to generate AI questions.'}</div>`);
                }
            },
            error: function (xhr) {
                $('#aiQuestionsList').html(`<div class="alert alert-danger font-weight-bold"><strong>HTTP Error ${xhr.status}:</strong><pre style="font-size:11px;">${xhr.responseText ? xhr.responseText.substring(0,500) : 'No response'}</pre></div>`);
            }
        });
    }


    // CLICK: OPEN AI QUESTIONS MODAL
    $(document).on('click', '.openAiQuestionsModal', function () {
        const btn = $(this);
        activeInterviewId = btn.attr('data-interview');
        const candName = btn.attr('data-candidate');
        const jobTitle = btn.attr('data-job');
        const atsScore = btn.attr('data-score');

        $('#aiCandName').text(candName || 'Candidate');
        $('#aiCandJob').text(jobTitle || 'Job Title');
        $('#aiAtsBadge').text(`ATS Fit Match: ${atsScore || 'N/A'}`);

        $('#aiQuestionsModal').modal('show');
        fetchAndLoadQuestions(activeInterviewId, null, btn);
    });

    // CATEGORY TAB FILTERING
    $(document).on('click', '#aiCategoryTabs a[data-cat]', function () {
        $('#aiCategoryTabs a').removeClass('active');
        $(this).addClass('active');
        const cat = $(this).attr('data-cat');
        renderQuestionsList(cat);
    });

    // REGENERATE BUTTON CLICK
    $(document).on('click', '#btnRegenerateAi', function () {
        if (activeInterviewId && confirm("Are you sure you want to generate a fresh candidate-personalized question set? Previous versions will remain in audit history.")) {
            generateAiQuestions(activeInterviewId, true);
        }
    });

    // VERSION ITEM CLICK
    $(document).on('click', '.ai-ver-item', function () {
        const ver = $(this).attr('data-ver');
        $('#aiVerBtn').text(`Version ${ver}`);
        fetchAndLoadQuestions(activeInterviewId, ver);
    });

    // QUESTION STATUS BUTTON CLICK
    $(document).on('click', '.q-status-btn', function () {
        const btn = $(this);
        const qid = btn.attr('data-qid');
        const status = btn.attr('data-status');

        btn.siblings().removeClass('active btn-secondary btn-info btn-success btn-danger')
             .addClass(function() {
                const s = $(this).attr('data-status');
                if (s === 'unasked') return 'btn-outline-secondary';
                if (s === 'asked') return 'btn-outline-info';
                if (s === 'answered') return 'btn-outline-success';
                if (s === 'skipped') return 'btn-outline-danger';
             });

        btn.removeClass('btn-outline-secondary btn-outline-info btn-outline-success btn-outline-danger')
           .addClass('active');
        if (status === 'unasked') btn.addClass('btn-secondary');
        else if (status === 'asked') btn.addClass('btn-info');
        else if (status === 'answered') btn.addClass('btn-success');
        else if (status === 'skipped') btn.addClass('btn-danger');

        $.ajax({
            url: '<?= base_url('admin/updateQuestionStatus'); ?>',
            type: 'POST',
            data: { questionId: qid, status: status },
            dataType: 'json'
        });
    });

    // PRINT QUESTION SHEET
    $(document).on('click', '#btnPrintAiQuestions', function () {
        const printWindow = window.open('', '_blank');
        const candName = $('#aiCandName').text();
        const jobTitle = $('#aiCandJob').text();
        const atsScore = $('#aiAtsBadge').text();

        let qHtml = '';
        loadedQuestions.forEach((q, idx) => {
            qHtml += `
            <div style="margin-bottom:18px; padding-bottom:12px; border-bottom:1px solid #e2e8f0; page-break-inside:avoid;">
              <div style="font-weight:bold; font-size:12px; color:#2563eb; text-transform:uppercase;">
                Question #${idx + 1} &bull; ${q.question_type ? q.question_type.toUpperCase() : 'TECHNICAL'} [${q.difficulty ? q.difficulty.toUpperCase() : 'MEDIUM'}]
              </div>
              <div style="font-size:14px; font-weight:bold; color:#0f172a; margin:6px 0;">
                ${q.question}
              </div>
              ${q.reason ? `<div style="font-size:11px; color:#64748b; font-style:italic;">Reasoning: ${q.reason}</div>` : ''}
              <div style="margin-top:8px; font-size:11px; color:#334155;">
                [ ] Asked &nbsp;&nbsp;&nbsp;&nbsp; [ ] Answered &nbsp;&nbsp;&nbsp;&nbsp; [ ] Skipped &nbsp;&nbsp;&nbsp;&nbsp; Rating/Notes: ____________________
              </div>
            </div>`;
        });

        printWindow.document.write(`
          <!DOCTYPE html>
          <html>
          <head>
            <title>AI Interview Questions - ${candName}</title>
            <style>
              body { font-family: 'Segoe UI', Arial, sans-serif; padding: 25px; color: #1f2937; }
              .header { border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
              .header h2 { margin: 0 0 6px 0; color: #0f172a; }
              .header p { margin: 0; color: #475569; font-size: 13px; }
            </style>
          </head>
          <body>
            <div class="header">
              <h2>AI Personalized Interview Questions</h2>
              <p><strong>Candidate:</strong> ${candName} &bull; <strong>Position:</strong> ${jobTitle} &bull; <strong>${atsScore}</strong></p>
            </div>
            ${qHtml}
          </body>
          </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function () { printWindow.print(); }, 400);
    });

});
</script>

<!-- ===== AI INTERVIEW QUESTIONS MODAL ===== -->
<div class="modal fade" id="aiQuestionsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header text-white px-4 py-3 align-items-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-bottom: none;">
        <div>
          <h5 class="modal-title font-weight-bold mb-0 text-white" id="aiModalTitle">
            <i class="fas fa-brain text-warning mr-2"></i> AI Personalized Interview Questions Engine
          </h5>
          <small class="text-white-50" id="aiModalSubtitle">Candidate-Tailored Competency & Non-Duplicative Assessment Set</small>
        </div>
        <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close" style="color:#ffffff !important; opacity:1 !important; font-size:26px;">
          <span aria-hidden="true" style="color:#ffffff !important;">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4" style="background:#f8fafc;">
        <!-- Candidate Summary Bar -->
        <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-white border rounded shadow-sm flex-wrap gap-2">
          <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-light border rounded text-center" style="min-width:45px;">
              <i class="fas fa-user-tie fa-2x text-primary"></i>
            </div>
            <div>
              <h6 class="font-weight-bold mb-0 text-dark" id="aiCandName">-</h6>
              <small class="text-muted" id="aiCandJob">-</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge badge-success px-3 py-2 font-weight-bold" id="aiSourceBadge" style="border-radius:12px; font-size:12px; display:none;">
              <i class="fas fa-robot mr-1"></i> Source: AI
            </span>
            <span class="badge badge-info px-3 py-2 font-weight-bold" id="aiAtsBadge" style="border-radius:12px; font-size:12px;">
              ATS Match: N/A
            </span>
            <div class="dropdown ml-2" id="aiVerDropdownWrap" style="display:none;">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle font-weight-bold" type="button" id="aiVerBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Version 1
              </button>
              <div class="dropdown-menu dropdown-menu-right" id="aiVerMenu"></div>
            </div>
          </div>
        </div>

        <!-- Must-Have Skill Coverage Bar -->
        <div id="aiSkillCoverageBar" class="mb-3 p-3 bg-white border rounded shadow-sm" style="display:none;">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="font-weight-bold text-dark style="font-size:13px;">
              <i class="fas fa-check-circle text-success mr-1"></i> Must-Have Skill Coverage:
            </div>
            <div id="aiSkillCoverageTags" class="d-flex flex-wrap gap-1"></div>
          </div>
          <div id="aiUncoveredWarnWrap" class="mt-2 text-danger small font-weight-bold" style="display:none;">
            <i class="fas fa-exclamation-triangle mr-1"></i> Uncovered Must-Have Skills: <span id="aiUncoveredTags"></span>
          </div>
        </div>

        <!-- Filter / Category Tabs -->
        <ul class="nav nav-pills mb-3 bg-white p-2 border rounded shadow-sm" id="aiCategoryTabs">
          <li class="nav-item">
            <a class="nav-link active font-weight-bold py-1 px-3 rounded-pill" href="javascript:void(0);" data-cat="all" id="tabCatAll">
              <i class="fas fa-layer-group mr-1"></i> All Questions
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold py-1 px-3 rounded-pill" href="javascript:void(0);" data-cat="must_have_skill" id="tabCatMustHave">
              <i class="fas fa-star mr-1 text-warning"></i> Must-Have Skills
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold py-1 px-3 rounded-pill" href="javascript:void(0);" data-cat="candidate_specific" id="tabCatCand">
              <i class="fas fa-user-check mr-1 text-success"></i> Candidate-Specific
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold py-1 px-3 rounded-pill" href="javascript:void(0);" data-cat="scenario" id="tabCatScen">
              <i class="fas fa-lightbulb mr-1 text-info"></i> Scenario
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold py-1 px-3 rounded-pill" href="javascript:void(0);" data-cat="behavioral" id="tabCatBeh">
              <i class="fas fa-users mr-1 text-purple" style="color:#7c3aed;"></i> Behavioral
            </a>
          </li>
        </ul>

        <!-- Questions List Container -->
        <div id="aiQuestionsList" style="min-height:220px;">
          <div class="text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
            <p class="font-weight-bold mb-0">Loading candidate-personalized questions...</p>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-white px-4 py-3 justify-content-between">
        <div>
          <button type="button" id="btnRegenerateAi" class="btn btn-warning font-weight-bold px-3" style="border-radius:6px;">
            <i class="fas fa-sync-alt mr-1"></i> Regenerate Fresh Questions
          </button>
        </div>
        <div class="d-flex gap-2">
          <button type="button" id="btnPrintAiQuestions" class="btn btn-outline-secondary font-weight-bold px-3" style="border-radius:6px;">
            <i class="fas fa-print mr-1"></i> Print Question Sheet
          </button>
          <button type="button" class="btn btn-secondary font-weight-bold px-4 ml-2" data-dismiss="modal" style="border-radius:6px;">Close</button>
        </div>
      </div>

    </div>
  </div>
</div>
