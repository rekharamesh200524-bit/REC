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


<table id="example1" class="table table-bordered table-striped">

<thead class="bg-success text-white">
<tr>
<th>S.No</th>
<th>Code</th>
<th>Name</th>
<th>Mobile No</th>
<th>Email</th>
<th>Score</th>
<th>Mode</th>
<th>Scheduled Time</th>
<th>Current Status</th>
<th>Verified On</th>
<th>Action</th>
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
<td><?= $i++; ?></td>
<td>
<a href="<?= base_url('admin/viewResume/'.$cl['CandidateId']); ?>"
target="_blank"
class="text-warning font-weight-bold">
<?= $cl['CandidateCode']; ?>
</a>
</td>

<td>
<a href="javascript:void(0);"
class="viewCandidateDetails text-primary font-weight-bold"
data-id="<?= $cl['CandidateId']; ?>">
<?= $cl['Fullname']; ?>
</a>
</td>

<td><?= $cl['PhoneNo']; ?></td>
<td><?= $cl['Email']; ?></td>
<td>
    <?php 
    $recVal = !empty($cl['ProfileMatchPer']) ? $cl['ProfileMatchPer'] : 'Potential Match';
    if ($recVal === 'Recommended') $recVal = 'Strong Match';
    if ($recVal === 'Review Required') $recVal = 'Potential Match';
    if ($recVal === 'Not Recommended') $recVal = 'Low Match';
    $badgeClass = (in_array($recVal, ['Strong Match', 'Strongly Match', 'Recommended'])) ? 'badge-success' : (in_array($recVal, ['Low Match', 'Not Recommended']) ? 'badge-danger' : 'badge-warning');
    ?>
    <span class="badge <?= $badgeClass ?> font-weight-bold p-1"><?= htmlspecialchars($recVal) ?></span>
</td>

<td>
    <?php
    $mode = !empty($cl['InterviewType']) ? trim($cl['InterviewType']) : '';
    $meetLink = !empty($cl['MeetLink']) ? trim($cl['MeetLink']) : '';
    if (strtolower($mode) === 'online'):
    ?>
        <span class="badge badge-primary"><i class="fas fa-video mr-1"></i>Online</span>
        <?php if (!empty($meetLink) && !$isRescheduledRow): ?>
            <br><a href="<?= htmlspecialchars($meetLink) ?>" target="_blank" class="btn btn-xs btn-outline-primary mt-1"><i class="fas fa-video mr-1"></i>Join Meet</a>
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
            echo $dateFormatted;
        }
    } else {
        echo 'Not Scheduled';
    }
    ?>
</td>

<td>
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

<td><?= $cl['AppliedOn']; ?></td>

<td>

<button type="button"
class="btn btn-sm btn-info viewCandidateDetails mr-1 mb-1"
data-id="<?= $cl['CandidateId']; ?>"
title="View Candidate Total Track">
<i class="fas fa-eye"></i> View Track
</button>

<?php
if(($resultLower == '' || $resultLower == 'assigned' || $resultLower == 'on hold') && !$isRescheduledRow){
?>

<button type="button"
class="btn btn-sm btn-warning openInterviewUpdate mb-1"
data-interview="<?= $cl['InterviewId']; ?>">
<i class="fas fa-edit"></i> Update Status
</button>

<?php
} else {

$badge = 'badge-secondary';

if($result == 'selected'){
$badge = 'badge-success';
}elseif($result == 'rejected'){
$badge = 'badge-danger';
}elseif($result == 'on hold'){
$badge = 'badge-warning';
}
?>

<span class="badge <?= $badge ?>">
<?= $cl['Result']; ?>
</span>

<?php } ?>

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
var interviewTable;

$(document).ready(function () {

    // ✅ Delay to ensure theme's DataTable init runs first, then we take over
    setTimeout(function () {

        if ($.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().destroy();
        }

        interviewTable = $('#example1').DataTable({
            responsive: true,
            autoWidth: false
        });
$('.interviewFilter.active').click();
    }, 100);


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

    $('#example1 tbody').html(res);

    window.scrollTo({ top: 0, behavior: 'smooth' });
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
                                <p class="mb-1"><strong>ATS Recommendation:</strong> <span class="badge ${ (c.ProfileMatchPer === 'Strong Match' || c.ProfileMatchPer === 'Strongly Match' || c.ProfileMatchPer === 'Recommended') ? 'badge-success' : ((c.ProfileMatchPer === 'Low Match' || c.ProfileMatchPer === 'Not Recommended') ? 'badge-danger' : 'badge-warning') }">${c.ProfileMatchPer === 'Recommended' ? 'Strong Match' : (c.ProfileMatchPer === 'Review Required' ? 'Potential Match' : (c.ProfileMatchPer === 'Not Recommended' ? 'Low Match' : (c.ProfileMatchPer ?? 'Potential Match')))}</span></p>
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

});
</script>
