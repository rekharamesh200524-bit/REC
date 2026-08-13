<?php
// Ensure that default fallback arrays exist to avoid JS errors
$all_jobs = isset($all_jobs) ? $all_jobs : [];
$all_candidates = isset($all_candidates) ? $all_candidates : [];
$departments = isset($departments) ? $departments : [];

$employee_det = $this->session->userdata('logged_in');
$roleId = isset($employee_det['EmpRoleId']) ? $employee_det['EmpRoleId'] : 1;
$dashboardTitle = ($roleId == 1 || $roleId == 2) ? 'Master Dashboard' : 'Department Dashboard';
$emp_name = isset($employee_det['EmpName']) ? $employee_det['EmpName'] : 'HR Manager';
?>


<div id="dashboard-wrapper" class="theme-job">
<section class="content pt-3 pb-4">
  <div class="container-fluid">

    <!-- ===== QUOTE BANNER ===== -->
    <div id="quoteBanner">
      <div class="row align-items-center">
        <div class="col-md-8 col-sm-12">
          <span class="quote-icon">&ldquo;</span>
          <p class="quote-text" id="quoteText">Loading inspiration...</p>
          <p class="quote-author" id="quoteAuthor"></p>
          <div class="quote-dots" id="quoteDots"></div>
        </div>
        <div class="col-md-4 col-sm-12 mt-3 mt-md-0">
          <div class="banner-greeting">
            <div class="greeting-time" id="liveClock">--:--:--</div>
            <div class="greeting-name">&#128075; Welcome, <?= htmlspecialchars($emp_name) ?></div>
            <div class="greeting-date" id="liveDate"></div>
          </div>
        </div>
    </div>

    <!-- ===== ON-HOLD REMINDER PUSH NOTIFICATIONS ===== -->
    <?php if (!empty($onhold_reminders)): ?>
      <div class="mt-3 mb-2">
        <?php foreach ($onhold_reminders as $rem): ?>
          <div class="alert alert-warning alert-dismissible fade show shadow-sm border-warning" role="alert">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
              <div class="py-1">
                <h6 class="alert-heading font-weight-bold mb-1 text-dark">
                  <i class="fas fa-bell text-warning mr-2"></i>Vacancy Hold Expiry Notification
                </h6>
                <p class="mb-0 text-dark">
                  The vacancy <strong><?= htmlspecialchars($rem['JobTitle']) ?></strong> (<code><?= htmlspecialchars($rem['JobCode']) ?></code>) hold period ended on <strong><?= date('d M Y', strtotime($rem['HoldUntilDate'])) ?></strong>. Please review and resume recruitment activities.
                </p>
              </div>
              <div class="mt-2 mt-sm-0">
                <a href="<?= base_url('admin/VaccancyList') ?>" class="btn btn-sm btn-warning text-dark font-weight-bold shadow-sm">
                  <i class="fas fa-eye mr-1"></i> View Vacancies
                </a>
              </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- ===== HEADER & TOGGLE ===== -->
    <div class="section-label mb-3">Analytics Overview</div>
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
      <h2 class="h4 mb-0 font-weight-bold text-dark d-flex align-items-center">
        <i class="fas fa-columns text-primary mr-2 page-title-icon"></i><?= $dashboardTitle ?>
      </h2>
      <div class="btn-group btn-group-toggle" id="dashboardToggle" role="group" aria-label="Dashboard toggle">
        <button type="button" class="btn btn-toggle-job active" data-toggle-target="job">
          <i class="fas fa-briefcase mr-2"></i>Jobs
        </button>
        <button type="button" class="btn btn-toggle-candidate" data-toggle-target="candidate">
          <i class="fas fa-user-tie mr-2"></i>Candidates
        </button>
      </div>
    </div>

    <!-- DYNAMIC KPI CARDS -->
    <div class="row mb-4">
      <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body d-flex align-items-center kpi-card-body kpi-bg-total">
            <span class="info-box-icon elevation-1 text-white mr-3 kpi-icon-box">
              <i class="fas fa-chart-pie"></i>
            </span>
            <div class="info-box-content">
              <h3 class="mb-0 kpi-value" id="kpi-total">0</h3>
              <p class="mb-0 kpi-label" id="label-total">Total</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body d-flex align-items-center kpi-card-body kpi-bg-open">
            <span class="info-box-icon elevation-1 text-white mr-3 kpi-icon-box">
              <i class="fas fa-folder-open"></i>
            </span>
            <div class="info-box-content">
              <h3 class="mb-0 kpi-value" id="kpi-open">0</h3>
              <p class="mb-0 kpi-label" id="label-open">Open</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body d-flex align-items-center kpi-card-body kpi-bg-hold">
            <span class="info-box-icon elevation-1 text-white mr-3 kpi-icon-box">
              <i class="fas fa-pause-circle"></i>
            </span>
            <div class="info-box-content">
              <h3 class="mb-0 kpi-value" id="kpi-hold">0</h3>
              <p class="mb-0 kpi-label" id="label-hold">Hold</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body d-flex align-items-center kpi-card-body kpi-bg-rejected">
            <span class="info-box-icon elevation-1 text-white mr-3 kpi-icon-box">
              <i class="fas fa-times-circle"></i>
            </span>
            <div class="info-box-content">
              <h3 class="mb-0 kpi-value" id="kpi-rejected">0</h3>
              <p class="mb-0 kpi-label" id="label-rejected">Rejected</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body d-flex align-items-center kpi-card-body kpi-bg-closed">
            <span class="info-box-icon elevation-1 text-white mr-3 kpi-icon-box">
              <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
              <h3 class="mb-0 kpi-value" id="kpi-closed">0</h3>
              <p class="mb-0 kpi-label" id="label-closed">Hired</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    <!-- ===== RESOURCE REQUESTS & VACANCIES OVERVIEW ===== -->
    <div class="card card-primary card-outline shadow-sm mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold mb-0 text-dark">
          <i class="fas fa-file-signature text-primary mr-2"></i>Resource Requests & Vacancy Tracking
        </h3>
        <span class="badge badge-primary px-3 py-1 font-weight-bold"><?= count(isset($resource_requests_list) ? $resource_requests_list : []); ?> Total Requests</span>
      </div>
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 text-nowrap">
          <thead class="bg-success text-white">
            <tr>
              <th>Request Code</th>
              <th>Job Title / Vacancy</th>
              <th>Department</th>
              <th>Openings</th>
              <th>Requested By</th>
              <th>Requested Date</th>
              <th>Target Onboarding</th>
              <th>Approver</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($resource_requests_list)): ?>
              <?php foreach ($resource_requests_list as $rr): ?>
                <tr>
                  <td class="font-weight-bold text-primary"><?= htmlspecialchars($rr['RequestCode']); ?></td>
                  <td><strong class="text-dark"><?= htmlspecialchars($rr['JobTitle']); ?></strong></td>
                  <td><?= htmlspecialchars($rr['Departmentname'] ? $rr['Departmentname'] : '-'); ?></td>
                  <td><span class="badge badge-info px-2"><?= (int)$rr['NoofOpenings']; ?></span></td>
                  <td><i class="fas fa-user-circle text-secondary mr-1"></i><?= htmlspecialchars($rr['RequestedByName'] ? $rr['RequestedByName'] : 'System'); ?></td>
                  <td><?= date('d-M-Y', strtotime($rr['CreatedAt'])); ?></td>
                  <td><?= $rr['TargetOnboardingDate'] ? date('d-M-Y', strtotime($rr['TargetOnboardingDate'])) : '-'; ?></td>
                  <td><?= htmlspecialchars($rr['ApproverName'] ? $rr['ApproverName'] : '-'); ?></td>
                  <td>
                    <?php if ($rr['Status'] === 'PENDING APPROVAL'): ?>
                      <span class="badge badge-warning px-2"><i class="fas fa-clock mr-1"></i>Pending Approval</span>
                    <?php elseif ($rr['Status'] === 'ACCEPTED'): ?>
                      <span class="badge badge-success px-2"><i class="fas fa-check-circle mr-1"></i>Approved & Published</span>
                    <?php elseif ($rr['Status'] === 'REJECTED'): ?>
                      <span class="badge badge-danger px-2"><i class="fas fa-times-circle mr-1"></i>Rejected</span>
                    <?php else: ?>
                      <span class="badge badge-secondary px-2"><?= htmlspecialchars($rr['Status']); ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-info btn-xs font-weight-bold" onclick="viewDashboardRequestDetails(<?= htmlspecialchars(json_encode($rr)); ?>)">
                      <i class="fas fa-eye mr-1"></i> View Details
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center text-muted py-4">
                  <i class="fas fa-inbox fa-2x d-block text-secondary mb-2"></i>
                  No resource requests recorded yet.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- DASHBOARD REQUEST DETAILS MODAL -->
    <div class="modal fade" id="dashRequestModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Resource Request Details</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="dashRequestModalContent"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    function viewDashboardRequestDetails(rr) {
      var html = '<table class="table table-bordered table-sm">'+
        '<tr><th style="width:30%">Request Code</th><td><strong class="text-primary">' + (rr.RequestCode || '') + '</strong></td></tr>'+
        '<tr><th>Job Title / Vacancy</th><td>' + (rr.JobTitle || '') + '</td></tr>'+
        '<tr><th>Department</th><td>' + (rr.Departmentname || '-') + '</td></tr>'+
        '<tr><th>Number of Positions</th><td>' + (rr.NoofOpenings || 1) + '</td></tr>'+
        '<tr><th>Position Type</th><td>' + (rr.PositionType || 'New Position') + '</td></tr>'+
        '<tr><th>Experience Required</th><td>' + (rr.ExpMin || 0) + ' - ' + (rr.ExpMax || 0) + ' Years</td></tr>'+
        '<tr><th>Start Date</th><td>' + (rr.RecruitmentStartDate || '-') + '</td></tr>'+
        '<tr><th>Target Onboarding Date</th><td>' + (rr.TargetOnboardingDate || '-') + '</td></tr>'+
        '<tr><th>Reason for Requirement</th><td>' + (rr.ReasonForRequirement || '-') + '</td></tr>'+
        '<tr><th>Job Description</th><td><pre style="white-space:pre-wrap; font-family:inherit;">' + (rr.JobDescription || '') + '</pre></td></tr>'+
        '<tr><th>Roles & Responsibilities</th><td><pre style="white-space:pre-wrap; font-family:inherit;">' + (rr.Responsibilities || '') + '</pre></td></tr>'+
        '<tr><th>Requested By</th><td><strong class="text-dark">' + (rr.RequestedByName || '-') + '</strong></td></tr>'+
        '<tr><th>Approver Name</th><td>' + (rr.ApproverName || '-') + '</td></tr>'+
        '<tr><th>Requested Date</th><td>' + (rr.CreatedAt || '') + '</td></tr>'+
        '<tr><th>Status</th><td><strong>' + (rr.Status || '') + '</strong></td></tr>';

      if (rr.ApprovalComment) {
        html += '<tr><th>Approver Comment</th><td class="text-danger">' + rr.ApprovalComment + '</td></tr>';
        html += '<tr><th>Actioned At</th><td>' + (rr.ActionedAt || '') + '</td></tr>';
      }

      html += '</table>';
      $('#dashRequestModalContent').html(html);
      $('#dashRequestModal').modal('show');
    }
    </script>

<!-- MAIN ANALYTICS SECTION -->
    <div class="row analytics-row">
      <div class="col-lg-8 mb-4">
        <div class="card h-100 shadow-sm dashboard-card-accent dashboard-accent-shadow">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
              <h5 class="card-title mb-0" id="summary-title">
                <i class="fas fa-user-tie text-primary mr-2"></i>Candidates Summary
              </h5>
              
              <div class="d-flex flex-wrap align-items-center">
                <?php if ($roleId == 1 || $roleId == 2): ?>
                <div class="form-group mb-0 mr-2">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-building"></i></span>
                    </div>
                    <select id="deptFilter" class="form-control">
                      <option value="">All Departments</option>
                      <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['Did'] ?>"><?= htmlspecialchars($dept['Departmentname']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <?php endif; ?>

                <div class="form-group mb-0">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <select id="monthFilter" class="form-control">
                      <option value="">All Months</option>
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table id="summaryTable" class="table table-bordered table-striped">
                <thead class="summary-table-header">
                  <tr id="tableHeader">
                    <!-- JS Injection -->
                  </tr>
                </thead>
                <tbody id="tableBody">
                  <!-- JS Injection -->
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 text-muted small">
              <span id="tableInfo">Showing 0 entries</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mb-4">
        <div class="card shadow-sm mb-3 dashboard-card-accent dashboard-accent-shadow">
          <div class="card-header">
            <h5 class="card-title mb-0" id="chart-title">
              <i class="fas fa-chart-pie text-primary mr-2"></i>Status Distribution
            </h5>
          </div>
          <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center justify-content-center mb-3 chart-container-box">
              <canvas id="dynamicDistributionChart" class="chart-canvas"></canvas>
              <div id="candidatePipeline" class="w-100" style="display:none;"></div>
            </div>
            <div id="chartDetails" class="mt-3 overflow-auto chart-details-box"></div>
          </div>
        </div>

        <div class="card shadow-sm hr-tip-card">
          <div class="card-body p-3">
            <div class="d-flex align-items-center mb-2">
              <span class="hr-tip-icon">&#128161;</span>
              <span class="hr-tip-title">HR Tip of the Day</span>
            </div>
            <p class="mb-0 hr-tip-text" id="hrTipText">Loading tip...</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
</div>

<!-- DYNAMIC DASHBOARD ENGINE -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  // ===== HIRING QUOTES =====
  const hiringQuotes = [
    { text: "Hiring is not just about filling roles \u2014 it\u2019s about shaping the future of your organization.", author: "\u2014 HR Excellence" },
    { text: "Great vision without great people is irrelevant. The right hire changes everything.", author: "\u2014 Jim Collins" },
    { text: "Every great business is built by exceptional people. Hire character, train skill.", author: "\u2014 Peter Schutz" },
    { text: "Culture eats strategy for breakfast. Hire for culture fit, and your team will thrive.", author: "\u2014 Peter Drucker" },
    { text: "The secret of my success is that we have gone to exceptional lengths to hire the best people in the world.", author: "\u2014 Steve Jobs" },
    { text: "You need to have a collaborative hiring process. Hire thoughtfully, not urgently.", author: "\u2014 Laszlo Bock, Google" },
    { text: "Your employees are your company\u2019s real competitive advantage. Hire wisely.", author: "\u2014 Anne Mulcahy" },
    { text: "Talent wins games, but teamwork and intelligence win championships. Hire both.", author: "\u2014 Michael Jordan" },
    { text: "A company\u2019s most valuable asset is not its people \u2014 it\u2019s the hiring process that attracts them.", author: "\u2014 Talent Insight" },
    { text: "Interview well, onboard better, and retain always. That\u2019s the recruiter\u2019s golden rule.", author: "\u2014 Recruiting Today" }
  ];
  const hrTips = [
    "Always send a personalised rejection email \u2014 candidates remember how you made them feel.",
    "Structured interviews reduce bias by 40%. Use the same questions for every candidate.",
    "Follow up within 48 hours of an interview. Speed signals respect and company culture.",
    "Job descriptions with inclusive language attract 42% more diverse applicants.",
    "Employee referrals produce the highest quality hires. Invest in your referral programme.",
    "Video interviews save 60% of scheduling time. Embrace async screening for early stages.",
    "Use ATS data to identify your best-performing sourcing channels every quarter.",
    "Candidate experience directly impacts your employer brand \u2014 even rejected candidates talk.",
    "Set clear hiring SLAs: target 30 days from JD approval to offer letter.",
    "Onboarding doesn\u2019t end on day one. A 90-day plan dramatically improves retention."
  ];

  // Random HR Tip
  const tipEl = document.getElementById('hrTipText');
  if (tipEl) tipEl.textContent = hrTips[Math.floor(Math.random() * hrTips.length)];

  // Rotating Quotes
  let currentQuote = 0;
  const quoteTextEl = document.getElementById('quoteText');
  const quoteAuthEl = document.getElementById('quoteAuthor');
  const quoteDotsEl = document.getElementById('quoteDots');

  function buildDots() {
    if (!quoteDotsEl) return;
    quoteDotsEl.innerHTML = '';
    hiringQuotes.forEach((_, i) => {
      const dot = document.createElement('span');
      dot.className = 'quote-dot' + (i === 0 ? ' active' : '');
      dot.onclick = () => showQuote(i);
      quoteDotsEl.appendChild(dot);
    });
  }
  function showQuote(idx) {
    currentQuote = idx;
    if (quoteTextEl) {
      quoteTextEl.classList.remove('quote-anim');
      void quoteTextEl.offsetWidth;
      quoteTextEl.classList.add('quote-anim');
      quoteTextEl.textContent = hiringQuotes[idx].text;
    }
    if (quoteAuthEl) quoteAuthEl.textContent = hiringQuotes[idx].author;
    document.querySelectorAll('.quote-dot').forEach((d, i) => d.classList.toggle('active', i === idx));
  }
  buildDots();
  showQuote(0);
  setInterval(() => showQuote((currentQuote + 1) % hiringQuotes.length), 6000);

  // Live Clock
  function updateClock() {
    const now = new Date();
    const pad = n => String(n).padStart(2, '0');
    const clockEl = document.getElementById('liveClock');
    if (clockEl) clockEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
    const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const dateEl = document.getElementById('liveDate');
    if (dateEl) dateEl.textContent = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
  }
  updateClock();
  setInterval(updateClock, 1000);
  
  // State variables
  let activeToggle = 'job'; // Default toggle
  let selectedDept = '';
  let selectedMonth = '';
  let myChartInstance = null;

  // Raw Database Data encoded as JSON arrays
  const jobsData = <?= json_encode($all_jobs) ?>;
  const candidatesData = <?= json_encode($all_candidates) ?>;

  // Initialize event listeners
  function init() {
    const toggleContainer = document.getElementById('dashboardToggle');
    const buttons = toggleContainer.querySelectorAll('button[data-toggle-target]');
    
    buttons.forEach(btn => {
      btn.addEventListener('click', function () {
        const target = this.getAttribute('data-toggle-target');
        activeToggle = (target === 'job') ? 'job' : 'candidate';
        updateDashboard();
      });
    });

    const deptFilterEl = document.getElementById('deptFilter');
    if (deptFilterEl) {
      deptFilterEl.addEventListener('change', function () {
        selectedDept = this.value;
        updateDashboard();
      });
    }

    document.getElementById('monthFilter').addEventListener('change', function () {
      selectedMonth = this.value;
      updateDashboard();
    });

    // Render initial view
    updateDashboard();
  }

  // Update whole dashboard metrics, tables, and chart
  function updateDashboard() {
    const wrapper = document.getElementById('dashboard-wrapper');
    if (wrapper) {
      wrapper.className = (activeToggle === 'job') ? 'theme-job' : 'theme-candidate';
    }
    let filteredData = [];
    
    if (activeToggle === 'job') {
      filteredData = jobsData.filter(item => {
        // Filter by Department
        if (selectedDept && String(item.Did) !== String(selectedDept)) return false;
        // Filter by Month (PostedOn)
        if (selectedMonth) {
          if (!item.PostedOn) return false;
          const month = new Date(item.PostedOn).getMonth() + 1; // getMonth is 0-indexed
          if (String(month) !== String(selectedMonth)) return false;
        }
        return true;
      });
    } else {
      filteredData = candidatesData.filter(item => {
        // Filter by Department
        if (selectedDept && String(item.Did) !== String(selectedDept)) return false;
        // Filter by Month (AppliedOn)
        if (selectedMonth) {
          if (!item.AppliedOn) return false;
          const month = new Date(item.AppliedOn).getMonth() + 1;
          if (String(month) !== String(selectedMonth)) return false;
        }
        return true;
      });
    }

    // Dynamic UI Title & KPI Label Updates
    const summaryTitle = document.getElementById('summary-title');
    const chartTitle = document.getElementById('chart-title');
    
    let totalCount = filteredData.length;
    let openCount = 0;
    let holdCount = 0;
    let rejectedCount = 0;
    let closedCount = 0;

    if (activeToggle === 'job') {
      summaryTitle.innerHTML = '<i class="fas fa-briefcase text-primary mr-2"></i>Jobs Summary';
      chartTitle.innerHTML = '<i class="fas fa-chart-pie text-primary mr-2"></i>Job Status Distribution';

      document.getElementById('label-total').innerText = 'Total Jobs';
      document.getElementById('label-open').innerText = 'Open Jobs';
      document.getElementById('label-hold').innerText = 'Hold Jobs';
      document.getElementById('label-rejected').innerText = 'Cancelled Jobs';
      document.getElementById('label-closed').innerText = 'Closed Jobs';

      // KPI Icons — Jobs theme
      const kpiIcons = [
        { id: 'kpi-total',    icon: 'fa-chart-pie' },
        { id: 'kpi-open',     icon: 'fa-folder-open' },
        { id: 'kpi-hold',     icon: 'fa-pause-circle' },
        { id: 'kpi-rejected', icon: 'fa-ban' },
        { id: 'kpi-closed',   icon: 'fa-handshake' }
      ];
      kpiIcons.forEach(function(item) {
        const card = document.getElementById(item.id);
        if (card) {
          const iconEl = card.closest('.kpi-card-body');
          if (iconEl) {
            const iconBox = iconEl.querySelector('.kpi-icon-box i');
            if (iconBox) {
              iconBox.className = 'fas ' + item.icon;
            }
          }
        }
      });

      filteredData.forEach(job => {
        const status = (job.JobStatus || '').toLowerCase();
        if (status === 'open' || status === 're-open') {
          openCount++;
        } else if (status === 'on-hold') {
          holdCount++;
        } else if (status === 'not required') {
          rejectedCount++;
        } else if (status === 'closed') {
          closedCount++;
        } else {
          openCount++; // Fallback
        }
      });
    } else {
      summaryTitle.innerHTML = '<i class="fas fa-users text-teal mr-2"></i>Candidates Summary';
      chartTitle.innerHTML = '<i class="fas fa-project-diagram text-teal mr-2"></i>Recruitment Pipeline';

      document.getElementById('label-total').innerText = 'Total Applicants';
      document.getElementById('label-open').innerText = 'Active Applicants';
      document.getElementById('label-hold').innerText = 'On Hold';
      document.getElementById('label-rejected').innerText = 'Rejected';
      document.getElementById('label-closed').innerText = 'Hired';

      // KPI Icons — Candidates theme
      const kpiIconsCand = [
        { id: 'kpi-total',    icon: 'fa-users' },
        { id: 'kpi-open',     icon: 'fa-user-check' },
        { id: 'kpi-hold',     icon: 'fa-user-clock' },
        { id: 'kpi-rejected', icon: 'fa-user-times' },
        { id: 'kpi-closed',   icon: 'fa-handshake' }
      ];
      kpiIconsCand.forEach(function(item) {
        const card = document.getElementById(item.id);
        if (card) {
          const iconEl = card.closest('.kpi-card-body');
          if (iconEl) {
            const iconBox = iconEl.querySelector('.kpi-icon-box i');
            if (iconBox) {
              iconBox.className = 'fas ' + item.icon;
            }
          }
        }
      });

      filteredData.forEach(cand => {
        const status = (cand.CurrentStatus || '').toLowerCase();
        if (status.includes('rejected')) {
          rejectedCount++;
        } else if (status.includes('accepted') || status.includes('boarding') || status.includes('hired')) {
          closedCount++;
        } else if (status.includes('hold') || status.includes('pending')) {
          holdCount++;
        } else {
          openCount++;
        }
      });
    }

    // Counters update with sleek animation
    animateCounter('kpi-total', totalCount);
    animateCounter('kpi-open', openCount);
    animateCounter('kpi-hold', holdCount);
    animateCounter('kpi-rejected', rejectedCount);
    animateCounter('kpi-closed', closedCount);

    // Re-render Table Content
    renderTable(filteredData);

    // Re-render Distribution Chart
    renderChart(openCount, holdCount, rejectedCount, closedCount);
    refreshToggleButtons();
  }

  // Counter Number Increment animation
  function animateCounter(id, target) {
    const el = document.getElementById(id);
    if (!el) return;
    const current = parseInt(el.innerText) || 0;
    if (current === target) {
      el.innerText = target;
      return;
    }
    
    let start = current;
    const duration = 250; 
    const stepTime = 25;
    const steps = duration / stepTime;
    const increment = (target - current) / steps;
    let step = 0;

    const timer = setInterval(() => {
      step++;
      start += increment;
      if (step >= steps) {
        clearInterval(timer);
        el.innerText = target;
      } else {
        el.innerText = Math.round(start);
      }
    }, stepTime);
  }

  function refreshToggleButtons() {
    const buttons = document.querySelectorAll('#dashboardToggle button[data-toggle-target]');
    buttons.forEach(btn => {
      const target = btn.getAttribute('data-toggle-target');
      if (target === activeToggle) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  }

  // Get stylized badge for status
  function getStatusBadge(status) {
    let s = (status || '').trim();
    let lower = s.toLowerCase();
    let displayText = s;

    // Map open/re-open/active status text to "Active"
    if (lower === 'open' || lower === 're-open' || lower === 'active') {
      displayText = 'Active';
    } else {
      // Capitalize first letter of each word for clean presentation if not mapped
      displayText = s.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
    }

    // Determine colors
    let bgColor = '#E2E8F0';
    let textColor = '#475569';

    if (lower === 'open' || lower === 're-open' || lower === 'active') {
      bgColor = '#F3E8FF'; // Light purple
      textColor = '#7C3AED'; 
    } else if (lower === 'on-hold' || lower === 'hold' || lower === 'pending') {
      bgColor = '#FFEDD5'; // Light orange
      textColor = '#EA580C'; // Deep orange
    } else if (lower === 'not required' || lower === 'cancelled' || lower.includes('reject') || lower === 'cancel') {
      bgColor = '#FEE2E2'; // Light red
      textColor = '#DC2626'; // Deep red
    } else if (lower === 'closed' || lower.includes('hired') || lower.includes('accept') || lower.includes('board') || lower.includes('select')) {
      bgColor = '#DCFCE7'; // Light green
      textColor = '#16A34A'; 
    }

    return `<span class="badge status-badge-custom" style="background-color: ${bgColor}; color: ${textColor};">${displayText}</span>`;
  }

  // Draw table based on active toggle
  function renderTable(data) {
    // Destroy DataTable if it already exists to allow re-initialization
    if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#summaryTable')) {
      $('#summaryTable').DataTable().destroy();
      $('#summaryTable').empty();
    }

    // Recreate thead and tbody structure inside #summaryTable
    const tableEl = document.getElementById('summaryTable');
    if (tableEl) {
      tableEl.innerHTML = `
        <thead class="summary-table-header">
          <tr id="tableHeader"></tr>
        </thead>
        <tbody id="tableBody"></tbody>
      `;
    }

    const header = document.getElementById('tableHeader');
    const body = document.getElementById('tableBody');
    const info = document.getElementById('tableInfo');

    if (!header || !body) return;

    header.innerHTML = '';
    body.innerHTML = '';

    if (activeToggle === 'job') {
      header.innerHTML = `
        <th>S. No</th>
        <th>Job Title</th>
        <th>Openings</th>
        <th>Posted Date</th>
        <th>Status</th>
      `;

      if (data.length === 0) {
        body.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">No jobs found matching filters.</td></tr>`;
        if (info) {
          info.style.display = 'block';
          info.innerText = `Showing 0 entries`;
        }
        return;
      }

      data.forEach((job, index) => {
        const dateStr = job.PostedOn || 'N/A';
        let statusLabel = job.JobStatus || 'Draft';
        let badge = getStatusBadge(statusLabel);
        
        body.innerHTML += `
          <tr>
            <td>${index + 1}</td>
            <td>${job.JobTitle || 'N/A'}</td>
            <td>${job.NoofOpenings || 0}</td>
            <td>${dateStr}</td>
            <td>${badge}</td>
          </tr>
        `;
      });

    } else {
      header.innerHTML = `
        <th>S. No</th>
        <th>Candidate Name</th>
        <th>Email</th>
        <th>Applied Job</th>
        <th>Applied Date</th>
        <th>Status</th>
      `;

      if (data.length === 0) {
        body.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">No candidates found matching filters.</td></tr>`;
        if (info) {
          info.style.display = 'block';
          info.innerText = `Showing 0 entries`;
        }
        return;
      }

      data.forEach((cand, index) => {
        const dateStr = cand.AppliedOn || 'N/A';
        let statusLabel = cand.CurrentStatus || 'Pending';
        let badge = getStatusBadge(statusLabel);

        body.innerHTML += `
          <tr>
            <td>${index + 1}</td>
            <td>${cand.Fullname || 'N/A'}</td>
            <td>${cand.Email || ''}</td>
            <td>${cand.JobTitle || 'N/A'}</td>
            <td>${dateStr}</td>
            <td>${badge}</td>
          </tr>
        `;
      });
    }

    if (info) {
      info.style.display = 'none'; // Hide native simple entries counter
    }

    // Initialize DataTable with Export options matching bo_template.php
    if (window.jQuery && $.fn.DataTable) {
      $('#summaryTable').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#summaryTable_wrapper .col-md-6:eq(0)');
    }
  }

  // Draw Chart.js and legend indicators
  function renderChart(open, hold, rejected, closed) {
    const canvas = document.getElementById('dynamicDistributionChart');
    const pipelineEl = document.getElementById('candidatePipeline');
    const detailsContainer = document.getElementById('chartDetails');
    if (!canvas || !pipelineEl) return;

    if (myChartInstance) {
      myChartInstance.destroy();
    }

    const total = open + hold + rejected + closed;
    detailsContainer.innerHTML = '';

    if (activeToggle === 'job') {
      canvas.style.display = 'block';
      pipelineEl.style.display = 'none';

      const labels = ['Open', 'On-Hold', 'Cancelled', 'Closed'];
      const dataValues = [open, hold, rejected, closed];
      const bgColors = ['#3b82f6', '#475569', '#dc2626', '#0891b2']; // Blue, Slate, Red, Cyan for Jobs

      if (total === 0) {
        detailsContainer.innerHTML = '<div class="text-center text-muted py-3">No distribution data.</div>';
        return;
      }

      labels.forEach((label, idx) => {
        const val = dataValues[idx];
        const percentage = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
        const color = bgColors[idx];

        detailsContainer.innerHTML += `
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-1 dist-item-wrapper">
              <span class="dist-item-label"><i class="fas fa-circle mr-2 dist-item-icon" style="color: ${color};"></i>${label}</span>
              <span class="dist-item-val">${val} <small class="text-muted font-weight-normal">(${percentage}%)</small></span>
            </div>
            <div class="progress dist-progress">
              <div class="progress-bar dist-progress-bar" style="width: ${percentage}%; background-color: ${color};">
                <div class="dist-progress-dot" style="border: 3px solid ${color};"></div>
              </div>
            </div>
          </div>
        `;
      });

      if (window.Chart) {
        myChartInstance = new Chart(canvas.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: labels,
            datasets: [{
              data: dataValues,
              backgroundColor: bgColors,
              borderWidth: 0,
              hoverOffset: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            cutoutPercentage: 72,
            legend: { display: false },
            plugins: {
              legend: { display: false }
            }
          }
        });
      }
    } else {
      canvas.style.display = 'none';
      pipelineEl.style.display = 'block';

      // Build recruitment pipeline steps visual representation
      const activePercent = total > 0 ? ((open / total) * 100).toFixed(0) : 0;
      const holdPercent = total > 0 ? ((hold / total) * 100).toFixed(0) : 0;
      const hiredPercent = total > 0 ? ((closed / total) * 100).toFixed(0) : 0;

      pipelineEl.innerHTML = `
        <div class="pipeline-funnel p-2">
          <div class="pipeline-step">
            <div class="d-flex justify-content-between font-weight-bold mb-1" style="font-size: 13.5px;">
              <span><i class="fas fa-inbox text-teal mr-2"></i>1. Total Applied</span>
              <span class="badge badge-secondary" style="font-size: 12px; padding: 4px 8px;">${total} Candidates</span>
            </div>
            <div class="progress dist-progress"><div class="progress-bar" style="width: 100%; background-color: #0d9488;"></div></div>
          </div>
          <div class="pipeline-step">
            <div class="d-flex justify-content-between font-weight-bold mb-1" style="font-size: 13.5px;">
              <span><i class="fas fa-user-clock text-emerald mr-2"></i>2. Under Review</span>
              <span class="badge badge-info" style="font-size: 12px; padding: 4px 8px;">${open} (${activePercent}%)</span>
            </div>
            <div class="progress dist-progress"><div class="progress-bar" style="width: ${activePercent}%; background-color: #10b981;"></div></div>
          </div>
          <div class="pipeline-step">
            <div class="d-flex justify-content-between font-weight-bold mb-1" style="font-size: 13.5px;">
              <span><i class="fas fa-pause-circle text-warning mr-2"></i>3. Shortlisted / On Hold</span>
              <span class="badge badge-warning" style="font-size: 12px; padding: 4px 8px;">${hold} (${holdPercent}%)</span>
            </div>
            <div class="progress dist-progress"><div class="progress-bar" style="width: ${holdPercent}%; background-color: #f59e0b;"></div></div>
          </div>
          <div class="pipeline-step">
            <div class="d-flex justify-content-between font-weight-bold mb-1" style="font-size: 13.5px;">
              <span><i class="fas fa-check-double text-success mr-2"></i>4. Hired</span>
              <span class="badge badge-success" style="font-size: 12px; padding: 4px 8px;">${closed} (${hiredPercent}%)</span>
            </div>
            <div class="progress dist-progress"><div class="progress-bar" style="width: ${hiredPercent}%; background-color: #047857;"></div></div>
          </div>
        </div>
      `;

      // In candidate mode, detailsContainer will show a simple summary breakdown
      detailsContainer.innerHTML = `
        <div class="alert alert-light border-0 shadow-none mb-0" style="background-color: rgba(13, 148, 136, 0.04);">
          <h6 class="font-weight-bold text-teal mb-2"><i class="fas fa-bullseye mr-2"></i>Conversion Rate</h6>
          <p class="mb-0 text-muted" style="font-size: 13px; line-height: 1.5;">
            Out of <strong>${total}</strong> applicants, <strong>${closed}</strong> candidates have been successfully hired, resulting in a conversion rate of <strong>${total > 0 ? ((closed/total)*100).toFixed(1) : 0}%</strong>.
          </p>
        </div>
      `;
    }
  }

  // Start initialization
  init();

});
</script>
