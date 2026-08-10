 <?php
    $employee_det = $this->session->userdata('logged_in');

    if (empty($employee_det)) {
        redirect($this->config->item('base_url') . 'admin/index');
    }
    $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template');

    ?>



  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">

          <div class="card card-success card-outline">
              <div class="card-header">
                  <div class="d-flex justify-content-between align-items-center">
                      <h3 class="card-title mb-0"></h3>

                      <a class="btn btn-sm btn-warning" id="openVacancyPanel">
                          <i class="fas fa-briefcase"></i> Add New Job
                      </a>
                  </div>
              </div>
              <div class="">

                  <form method="POST" action="<?= base_url('admin/vacancies') ?>" class="mb-4">

                      <div class="card card-light">
                          <div class="card-body">

                              <div class="row align-items-end">

                                  <!-- Date Range -->
                                  <div class="col-md-3">
                                      <div class="form-group mb-0">
                                          <label>Posted Date Range</label>
                                          <input type="text"
                                              name="dateRange"
                                              id="dateRange"
                                              class="form-control"
                                              placeholder="Select date range"
                                              value="<?= htmlspecialchars($this->input->post('dateRange', TRUE) ?: $this->input->get('dateRange', TRUE)) ?>">
                                      </div>
                                  </div>

                                  <!-- Department -->
                                  <div class="col-md-3">
                                      <div class="form-group mb-0">
                                          <label>Department</label>
                                          <!-- <select name="department" class="form-control"> -->
                                          <select name="department" class="form-control" onchange="this.form.submit()">
                                              <option value="">All Departments</option>
                                              <?php foreach ($department as $d): ?>
                                                  <option value="<?= $d['Departmentname'] ?>"
                                                      <?= (($this->input->post('department', TRUE) ?: $this->input->get('department', TRUE)) == $d['Departmentname']) ? 'selected' : '' ?>>
                                                      <?= $d['Departmentname'] ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          </select>
                                      </div>
                                  </div>

                                  <!-- Status -->
                                  <div class="col-md-3">
                                      <div class="form-group mb-0">
                                          <label>Status</label>
                                          <!-- <select name="status" class="form-control"> -->
                                          <select name="status" class="form-control" onchange="this.form.submit()">
                                              <option value="">All Status</option>
                                              <option value="Open" <?= (($this->input->post('status', TRUE) ?: $this->input->get('status', TRUE)) == 'Open') ? 'selected' : '' ?>>Open</option>
                                              <option value="Closed" <?= (($this->input->post('status', TRUE) ?: $this->input->get('status', TRUE)) == 'Closed') ? 'selected' : '' ?>>Closed</option>
                                              <option value="On-Hold" <?= (($this->input->post('status', TRUE) ?: $this->input->get('status', TRUE)) == 'On-Hold') ? 'selected' : '' ?>>On-Hold</option>
                                              <option value="Draft" <?= (($this->input->post('status', TRUE) ?: $this->input->get('status', TRUE)) == 'Draft') ? 'selected' : '' ?>>Draft</option>
                                          </select>
                                      </div>
                                  </div>

                                  <!-- Reset Button -->
                                  <div class="col-md-3">
                                      <a href="<?= base_url('admin/vacancies') ?>"
                                          class="btn btn-outline-secondary btn-block">
                                          <i class="fas fa-undo"></i> Reset
                                      </a>
                                  </div>

                              </div>

                          </div>
                      </div>

                  </form>

                  <!-- /.card-header -->
                  <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                          <thead class="bg-success">
                              <tr>
                                  <th>S.No</th>
                                  <th>Job Code</th>
                                  <th>Job Title</th>
                                  <th>Department</th>
                                  <th>Employment</th>
                                  <th>Work Mode</th>
                                  <!-- <th>Education</th> -->
                                  <th>No of Openings</th>
                                  <th>Expiry Date</th>
                                  <th>Job Status</th>
                                  <th>Posted On</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php

                                // echo "<pre>vaclist"; print_r($vaclist); exit;

                                if (isset($vaclist) && !empty($vaclist)) {
                                    $i = 1;
                                    foreach ($vaclist as $vl) {
                                ?>
                                      <tr>
                                          <td><?= $i++; ?></td>
                                          <td><a href="<?php echo $this->config->item('base_url') ?>admin/Candidatelist/<?php echo $vl['Jid']; ?>"><?= $vl['JobCode'] ?></a></td>
                                          <td><?= $vl['JobTitle'] ?></td>
                                          <td><?= $vl['Departmentname'] ?></td>
                                          <td><?= $vl['EmploymentType'] ?></td>
                                          <td><?= $vl['WorkMode'] ?></td>
                                          <td><?= $vl['NoofOpenings'] ?></td>
                                          <td><?= $vl['ExpiryDate'] ?></td>
                                          <td><?= $vl['JobStatus'] ?></td>
                                          <td><?= $vl['PostedOn'] ?></td>
                                          <td class="text-center">

                                              <div class="btn-group" role="group">

                                                  <!-- Edit Job -->
                                                  <button type="button"
                                                      class="btn btn-sm btn-primary editJobBtn"
                                                      title="Edit Job"
                                                      data-id="<?= $vl['Jid']; ?>">
                                                      <i class="fas fa-edit"></i>
                                                  </button>

                                                  <button type="button"
                                                      class="btn btn-sm btn-secondary viewVacancyBtn"
                                                      title="View Vacancy"
                                                      data-id="<?= $vl['Jid']; ?>">
                                                      <i class="fas fa-eye"></i>
                                                  </button>

                                                  <?php if ($vl['JobStatus'] == 'Open') { ?>

                                                      <!-- <input type="hidden" name="job_id" id="jobId"> -->

                                                      <!-- Put On Hold -->
                                                      <button type="button"
                                                          class="btn btn-sm btn-warning jobStatusBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          data-status="On-Hold"
                                                          title="Put On Hold">
                                                          <i class="fas fa-pause-circle"></i>
                                                      </button>

                                                      <!-- Close Job -->
                                                      <button type="button"
                                                          class="btn btn-sm btn-danger jobStatusBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          data-status="Closed"
                                                          title="Close Job">
                                                          <i class="fas fa-times-circle"></i>
                                                      </button>
                                                      <!-- Upload Resumes -->
                                                      <button type="button"
                                                          class="btn btn-sm btn-success uploadResumeBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          title="Upload Resumes">
                                                          <i class="fas fa-upload"></i>
                                                      </button>

                                                      <!-- Analyze Resumes -->
                                                      <!-- <button type="button"
                                                                    class="btn btn-sm btn-info analyzeResumeBtn"
                                                                    data-id="<?= $vl['Jid']; ?>"
                                                                    title="Analyze Resumes">
                                                                <i class="fas fa-chart-line"></i>
                                                            </button> -->

                                                  <?php } elseif ($vl['JobStatus'] == 'On-Hold') { ?>

                                                      <button type="button"
                                                          class="btn btn-sm btn-success jobStatusBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          data-status="Open"
                                                          title="Re-Open Job">
                                                          <i class="fas fa-play-circle"></i>
                                                      </button>

                                                  <?php } elseif ($vl['JobStatus'] == 'Closed') { ?>

                                                      <button type="button"
                                                          class="btn btn-sm btn-info jobStatusBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          data-status="Open"
                                                          title="Re-Open Job">
                                                          <i class="fas fa-redo"></i>
                                                      </button>

                                                  <?php } elseif ($vl['JobStatus'] == 'Re-Open') { ?>

                                                      <button type="button"
                                                          class="btn btn-sm btn-success jobStatusBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          data-status="Open"
                                                          title="Mark as Open">
                                                          <i class="fas fa-check-circle"></i>
                                                      </button>

                                                      <!-- Close Job -->
                                                      <button type="button"
                                                            class="btn btn-sm btn-danger jobStatusBtn"
                                                            data-id="<?= $vl['Jid']; ?>"
                                                            data-status="Closed"
                                                            title="Close Job">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>

                                                  <?php } elseif ($vl['JobStatus'] == 'Draft') { ?>

                                                      <button type="button"
                                                          class="btn btn-sm btn-success jobStatusBtn"
                                                          data-id="<?= $vl['Jid']; ?>"
                                                          data-status="Open"
                                                          title="Publish Job">
                                                          <i class="fas fa-upload"></i>
                                                      </button>

                                                  <?php } elseif ($vl['JobStatus'] == 'Not Required') { ?>

                                                      <button type="button"
                                                          class="btn btn-sm btn-secondary"
                                                          disabled
                                                          title="Job Not Required">
                                                          <i class="fas fa-ban"></i>
                                                      </button>

                                                  <?php } ?>

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
                  <!-- /.card-body -->
              </div><!-- /.card -->
          </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <div class="modal fade" id="uploadModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">

              <div class="modal-header">
                  <h5 class="modal-title">Upload Resume</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-body">


                  <form id="resumeDropzone"
                      class="dropzone" method="post"
                      enctype="multipart/form-data">
                      <input type="hidden" id="jobId">

                  </form>


              </div>

          </div>
      </div>
  </div>

  <!-- Right Side Panel -->
  <div id="vacancyPanel" class="right-form">
      <div class="right-form-header">
          <h5>Create Vacancy</h5>
          <button type="button" class="close-btn" id="closeVacancyPanel">&times;</button>
      </div>

      <div class="right-form-body">
        
          <div class="row">
              <div class="col-md-12">
                  <div class="card card-default">
                      <div class="card-header">
                          <h3 class="card-title">Job Details</h3>
                      </div>
                      <form action="<?= base_url('admin/saveVacancy') ?>" method="post">
                          <div class="card-body p-0">
                              <div class="bs-stepper">
                                  <div class="bs-stepper-header" role="tablist">
                                      <!-- your steps here -->
                                      <div class="step" data-target="#logins-part">
                                          <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" id="logins-part-trigger">
                                              <span class="bs-stepper-circle">1</span>
                                              <span class="bs-stepper-label">JOB INFO</span>
                                          </button>
                                      </div>
                                      <div class="line"></div>
                                      <div class="step" data-target="#information-part">
                                          <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                                              <span class="bs-stepper-circle">2</span>
                                              <span class="bs-stepper-label">SALARY INFO</span>
                                          </button>
                                      </div>
                                      <div class="line"></div>
                                      <div class="step" data-target="#skill-part">
                                          <button type="button" class="step-trigger" role="tab" aria-controls="skill-part" id="skill-part-trigger">
                                              <span class="bs-stepper-circle">3</span>
                                              <span class="bs-stepper-label">SKILL INFO</span>
                                          </button>
                                      </div>
                                  </div>
                                  <div class="bs-stepper-content">
                                      <!-- your steps content here -->
                                      <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                                          <div class="form-group">
                                              <label class="text-label">Job Title*</label>
                                              <div class="position-relative">
                                                  <input type="text" name="jobTitle" id="jobTitle"
                                                      class="form-control search-input"
                                                      placeholder="Type job title..." autocomplete="off" required>
                                                  <div class="dropdown-menu w-100" id="jobTitleDropdown"></div>
                                              </div>
                                          </div>

                                          <div class="form-group">
                                              <label class="text-label">Department*</label>
                                              <select name="department" id="department" class="form-control" required>
                                                  <option value="">Select Department</option>
                                                  <?php foreach ($department as $d): ?>
                                                      <option value="<?= $d['Did'] ?>">
                                                          <?= $d['Departmentname'] ?>
                                                      </option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </div>

                                          <div class="form-group">
                                              <label class="text-label">Role*</label>
                                              <div class="position-relative">
                                                  <input type="text" name="role" id="role"
                                                      class="form-control search-input"
                                                      placeholder="Type role..." autocomplete="off" required>
                                                  <div class="dropdown-menu w-100" id="roleDropdown"></div>
                                              </div>
                                          </div>

                                          <div class="form-group">
                                              <label class="text-label">Work Mode*</label>
                                              <input type="hidden" name="workMode" id="work_mode" required>

                                              <div class="d-flex gap-2">
                                                  <span class="work-mode badge badge-pill badge-outline-primary"
                                                      data-value="Onsite">
                                                      Onsite
                                                  </span>

                                                  <span class="work-mode badge badge-pill badge-outline-success"
                                                      data-value="Remote">
                                                      Remote
                                                  </span>

                                                  <span class="work-mode badge badge-pill badge-outline-info"
                                                      data-value="Hybrid">
                                                      Hybrid
                                                  </span>
                                              </div>
                                          </div>

                                          <div class="form-group">
                                              <label class="text-label">Employment Type*</label>
                                              <input type="hidden" name="employmentType" id="employment_type" required>


                                              <div class="d-flex flex-wrap gap-2">
                                                  <span class="emp-type badge badge-pill badge-outline-primary"
                                                      data-value="Full-Time">
                                                      Full-Time
                                                  </span>

                                                  <span class="emp-type badge badge-pill badge-outline-warning"
                                                      data-value="Part-Time">
                                                      Part-Time
                                                  </span>

                                                  <span class="emp-type badge badge-pill badge-outline-secondary"
                                                      data-value="Contract">
                                                      Contract
                                                  </span>

                                                  <span class="emp-type badge badge-pill badge-outline-dark"
                                                      data-value="Internship">
                                                      Internship
                                                  </span>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <label>Expiry Date:*</label>
                                              <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                  <input type="text"
                                                      name="ExpiryDate"
                                                      id="expiryDate"
                                                      class="form-control datetimepicker-input"
                                                      data-target="#reservationdate" />
                                                  <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                  </div>
                                              </div>
                                          </div>
                                          <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                                      </div>
                                      <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                                          <div class="form-group">
                                              <label>Location*</label>
                                              <div class="position-relative">
                                                  <input type="text" id="jobLocationInput" class="form-control search-input"
                                                      placeholder="Type location..." autocomplete="off">
                                                  <input type="hidden" name="jobLocation" id="jobLocation">
                                                  <div class="dropdown-menu w-100" id="jobLocationDropdown"></div>
                                              </div>
                                              <div class="chip-container mt-2" id="jobLocationChips"></div>
                                          </div>
                                          <div class="form-group">
                                              <div class="row">
                                                  <div class="col-md-6">
                                                      <label class="text-label">Min Experience*</label>
                                                      <select id="expMin" name="expMin" class="form-control" required>
                                                          <option value="">Min</option>
                                                      </select>
                                                  </div>

                                                  <div class="col-md-6">
                                                      <label class="text-label">Max Experience*</label>
                                                      <select id="expMax" name="expMax" class="form-control" required>
                                                          <option value="">Max</option>
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <div class="row">
                                                  <div class="col-md-6">
                                                      <label class="text-label">Min Salary*</label>
                                                      <select id="salaryMin" name="salaryMin" class="form-control" required>
                                                          <option value="">Min Salary</option>
                                                      </select>
                                                  </div>

                                                  <div class="col-md-6">
                                                      <label class="text-label">Max Salary*</label>
                                                      <select id="salaryMax" name="salaryMax" class="form-control" required>
                                                          <option value="">Max Salary</option>
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <label>Education*</label>
                                              <div class="position-relative">
                                                  <input type="text" id="educationInput" class="form-control search-input"
                                                      placeholder="Type education..." autocomplete="off">
                                                  <input type="hidden" name="education" id="education">
                                                  <div class="dropdown-menu w-100" id="educationDropdown"></div>
                                              </div>
                                              <div class="chip-container mt-2" id="educationChips"></div>
                                          </div>

                                          <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                          <button class="btn btn-primary" onclick="stepper.next()">Next</button>

                                      </div>
                                      <div id="skill-part" class="content" role="tabpanel" aria-labelledby="skill-part-trigger">
                                          <!-- text input -->
                                          <div class="form-group">
                                              <label class="text-label">Positions*</label>
                                              <div class="quantity-cart">
                                                  <span class="qty-btn minus">−</span>

                                                  <input type="text" class="qty-input" id="positions" name="positions" value="1" inputmode="numeric" pattern="[0-9]*" required>

                                                  <span class="qty-btn plus">+</span>
                                              </div>
                                          </div>

                                          <div class="form-group">
                                              <label>Skills*</label>
                                              <div class="position-relative">
                                                  <input type="text" id="skillsInput" class="form-control search-input"
                                                      placeholder="Type skill..." autocomplete="off">
                                                  <input type="hidden" name="skills" id="skills">
                                                  <div class="dropdown-menu w-100" id="skillsDropdown"></div>
                                              </div>
                                              <div class="chip-container mt-2" id="skillsChips"></div>
                                          </div>
                                          <div class="form-group">
                                              <label>Communication Language*</label>
                                              <div class="position-relative">
                                                  <input type="text" id="languageInput" class="form-control search-input"
                                                      placeholder="Type language..." autocomplete="off">
                                                  <input type="hidden" name="comLanguage" id="comLanguage">
                                                  <div class="dropdown-menu w-100" id="languageDropdown"></div>
                                              </div>
                                              <div class="chip-container mt-2" id="languageChips"></div>
                                          </div>
                                          <div class="form-group">
                                              <label>Job Description*</label>
                                              <textarea name="JD" id="JD" class="form-control" rows="4" placeholder="Enter job description" required></textarea>
                                          </div>

                                          <div class="form-group">
                                              <label>Roles & Responsibilities*</label>
                                              <textarea name="RR" id="RR" class="form-control" rows="4" placeholder="Enter roles and responsibilities" required></textarea>
                                          </div>

                                          <!-- ATS Score Breakdown Section -->
                                          <div class="card card-outline card-success mt-4">
                                              <div class="card-header">
                                                  <h5 class="card-title mb-0 font-weight-bold text-success">ATS Score Breakdown</h5>
                                              </div>
                                              <div class="card-body p-3">
                                                  <div class="row">
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Skills Match</label>
                                                          <input type="number" name="SkillScore" class="form-control ats-score-input" min="0" value="50" required>
                                                      </div>
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Education Match</label>
                                                          <input type="number" name="EducationScore" class="form-control ats-score-input" min="0" value="20" required>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Experience Match</label>
                                                          <input type="number" name="ExperienceScore" class="form-control ats-score-input" min="0" value="20" required>
                                                      </div>
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Projects & Achievements</label>
                                                          <input type="number" name="ProjectScore" class="form-control ats-score-input" min="0" value="5" required>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Certifications</label>
                                                          <input type="number" name="CertificationScore" class="form-control ats-score-input" min="0" value="10" required>
                                                      </div>
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Resume Quality</label>
                                                          <input type="number" name="ResumeQualityScore" class="form-control ats-score-input" min="0" value="5" required>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-md-6 form-group">
                                                          <label class="text-label">Role Fit / Domain Knowledge</label>
                                                          <input type="number" name="DomainKnowledgeScore" class="form-control ats-score-input" min="0" value="5" required>
                                                      </div>
                                                  </div>
                                                  <hr class="my-2">
                                                  <div class="d-flex justify-content-between align-items-center">
                                                      <h6 class="mb-0 font-weight-bold">Total ATS Marks:</h6>
                                                      <span class="h5 text-success font-weight-bold total-ats-marks">115</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                          <!-- <button class="btn btn-primary" onclick="stepper.previous()">Previous</button> -->
                                          <!--  <button type="button" onclick="alert(document.getElementById('education').value)">
        Test Education Value
    </button> -->
                                          <button type="submit" class="btn btn-primary">Submit</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- /.card-body -->
                      </form>
                  </div>
                  <!-- /.card -->
              </div>
          </div>
         

      </div>

      <!-- edit start -->

      <div id="editVacancyPanel" class="right-form">
          <form id="editVacancyForm" action="<?= base_url('admin/updateVacancy') ?>" method="post">

              <input type="hidden" name="jid" id="edit_jid">

              <div class="right-form-header">
                  <!-- <h5>Edit Vacancy</h5> -->
                  <h5>
                      Edit Vacancy
                      <!-- <small class="text-muted ml-2" id="editJobCodeText"></small> -->
                      <small id="editJobCodeText" class="badge badge-pill badge-info ml-2"></small>
                  </h5>
                  <button type="button" class="close-btn" id="closeEditVacancyPanel">&times;</button>
              </div>

              <div class="right-form-body">
                  <div class="bs-stepper">

                  <div class="bs-stepper-header">

                      <div class="step" data-target="#edit-logins-part">
                          <button type="button" class="step-trigger">
                              <span class="bs-stepper-circle">1</span>
                              <span class="bs-stepper-label">JOB INFO</span>
                          </button>
                      </div>

                      <div class="line"></div>

                      <div class="step" data-target="#edit-information-part">
                          <button type="button" class="step-trigger">
                              <span class="bs-stepper-circle">2</span>
                              <span class="bs-stepper-label">SALARY INFO</span>
                          </button>
                      </div>

                      <div class="line"></div>

                      <div class="step" data-target="#edit-skill-part">
                          <button type="button" class="step-trigger">
                              <span class="bs-stepper-circle">3</span>
                              <span class="bs-stepper-label">SKILL INFO</span>
                          </button>
                      </div>

                  </div>

                  <div class="bs-stepper-content">

                      <!-- STEP 1 -->
                      <div id="edit-logins-part" class="content">
                          <div class="form-group">
                              <label>Job Title</label>
                              <input type="text" id="edit_jobTitle" class="form-control" readonly>
                          </div>

                          <div class="form-group">
                              <label>Department</label>
                              <input type="text" id="edit_department" class="form-control" readonly>
                          </div>

                          <div class="form-group">
                              <label>Role</label>
                              <input type="text" id="edit_role" class="form-control" readonly>
                          </div>

                          <div class="form-group">
                              <label>Work Mode*</label>
                              <input type="hidden" name="workMode" id="edit_work_mode">

                              <div class="d-flex gap-2">
                                  <span class="edit-work-mode badge badge-pill badge-outline-primary" data-value="Onsite">Onsite</span>
                                  <span class="edit-work-mode badge badge-pill badge-outline-success" data-value="Remote">Remote</span>
                                  <span class="edit-work-mode badge badge-pill badge-outline-info" data-value="Hybrid">Hybrid</span>
                              </div>
                          </div>

                          <div class="form-group">
                              <label>Employment Type*</label>
                              <input type="hidden" name="employmentType" id="edit_employment_type">

                              <div class="d-flex flex-wrap gap-2">
                                  <span class="edit-emp-type badge badge-pill badge-outline-primary" data-value="Full-Time">Full-Time</span>
                                  <span class="edit-emp-type badge badge-pill badge-outline-warning" data-value="Part-Time">Part-Time</span>
                                  <span class="edit-emp-type badge badge-pill badge-outline-secondary" data-value="Contract">Contract</span>
                                  <span class="edit-emp-type badge badge-pill badge-outline-dark" data-value="Internship">Internship</span>
                              </div>
                          </div>

                          <div class="form-group">
                              <label>Expiry Date*</label>
                              <input type="date" name="ExpiryDate" id="edit_expiryDate" class="form-control">
                          </div>

                          <button type="button" class="btn btn-primary" onclick="editStepper.next()">Next</button>
                      </div>

                      <!-- STEP 2 -->
                      <div id="edit-information-part" class="content">
                          <!-- Salary -->
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6">
                                      <label>Min Salary*</label>
                                      <!-- <input type="number"name="salaryMin" id="edit_salaryMin" class="form-control"> -->


                                      <select id="edit_salaryMin" name="salaryMin" class="form-control">
                                          <option value="">Min Salary</option>
                                      </select>
                                  </div>

                                  <div class="col-md-6">
                                      <label>Max Salary*</label>
                                      <select id="edit_salaryMax" name="salaryMax" class="form-control">
                                          <option value="">Max Salary</option>
                                      </select>

                                  </div>
                              </div>
                          </div>
                          <!-- Experience -->
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6">
                                      <label>Min Experience*</label>
                                      <select id="edit_expMin" name="expMin" class="form-control">
                                          <option value="">Min</option>
                                      </select>
                                  </div>

                                  <div class="col-md-6">
                                      <label>Max Experience*</label>

                                      <select id="edit_expMax" name="expMax" class="form-control">
                                          <option value="">Max </option>
                                      </select>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label>Location*</label>

                              <div class="position-relative">
                                  <input type="text"
                                      id="edit_jobLocationInput"
                                      class="form-control"
                                      autocomplete="off">

                                  <div class="dropdown-menu w-100"
                                      id="edit_jobLocationDropdown"></div>
                              </div>

                              <input type="hidden"
                                  name="jobLocation"
                                  id="edit_jobLocation">

                              <div class="chip-container mt-2"
                                  id="edit_jobLocationChips"></div>
                          </div>
                          <div class="form-group">
                              <label>Education*</label>
                              <div class="position-relative">
                                  <input type="text" id="edit_educationInput" class="form-control">
                                  <div class="dropdown-menu w-100" id="edit_educationDropdown"></div>
                              </div>
                              <input type="hidden" name="education" id="edit_education">
                              <div class="chip-container mt-2" id="edit_educationChips"></div>
                          </div>

                          <button type="button" class="btn btn-primary" onclick="editStepper.previous()">Previous</button>
                          <button type="button" class="btn btn-primary" onclick="editStepper.next()">Next</button>
                      </div>

                      <!-- STEP 3 -->
                      <div id="edit-skill-part" class="content">

                          <div class="form-group">
                              <label>Positions*</label>
                              <input type="number" name="positions" id="edit_positions" class="form-control">
                          </div>

                          <div class="form-group">
                              <label>Skills*</label>
                              <div class="position-relative">
                                  <input type="text" id="edit_skillsInput" class="form-control">
                                  <div class="dropdown-menu w-100" id="edit_skillsDropdown"></div>
                              </div>
                              <div class="chip-container mt-2" id="edit_skillsChips"></div>
                          </div>

                          <div class="form-group">
                              <label>Communication Language*</label>
                              <div class="position-relative">
                                  <input type="text" id="edit_languageInput" class="form-control">
                                  <div class="dropdown-menu w-100" id="edit_languageDropdown"></div>
                              </div>
                              <input type="hidden" name="comLanguage" id="edit_comLanguage">
                              <div class="chip-container mt-2" id="edit_languageChips"></div>
                          </div>

                          <div class="form-group">
                              <label>Job Description*</label>
                              <textarea name="JD" id="edit_JD" class="form-control"></textarea>
                          </div>
                          <div class="form-group">
                              <label>Roles & Responsibilities*</label>
                              <textarea name="RR" id="edit_RR" class="form-control"></textarea>
                          </div>

                          <!-- ATS Score Breakdown Section -->
                          <div class="card card-outline card-success mt-4">
                              <div class="card-header">
                                  <h5 class="card-title mb-0 font-weight-bold text-success">ATS Score Breakdown</h5>
                              </div>
                              <div class="card-body p-3">
                                  <div class="row">
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Skills Match</label>
                                          <input type="number" name="SkillScore" id="edit_SkillScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Education Match</label>
                                          <input type="number" name="EducationScore" id="edit_EducationScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Experience Match</label>
                                          <input type="number" name="ExperienceScore" id="edit_ExperienceScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Projects & Achievements</label>
                                          <input type="number" name="ProjectScore" id="edit_ProjectScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Certifications</label>
                                          <input type="number" name="CertificationScore" id="edit_CertificationScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Resume Quality</label>
                                          <input type="number" name="ResumeQualityScore" id="edit_ResumeQualityScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6 form-group">
                                          <label class="text-label">Role Fit / Domain Knowledge</label>
                                          <input type="number" name="DomainKnowledgeScore" id="edit_DomainKnowledgeScore" class="form-control ats-score-input" min="0" required>
                                      </div>
                                  </div>
                                  <hr class="my-2">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <h6 class="mb-0 font-weight-bold">Total ATS Marks:</h6>
                                      <span class="h5 text-success font-weight-bold total-ats-marks">115</span>
                                  </div>
                              </div>
                          </div>

                          <button type="button" class="btn btn-primary" onclick="editStepper.previous()">Previous</button>
                          <button type="submit" class="btn btn-primary">Update</button>

                      </div>

                  </div>
              </div>
              </div>

          </form>
      </div>
      <!-- edit end -->



  </div>
  <!-- view modal -->
  <div class="modal fade" id="vacancyDetailsModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">

              <div class="modal-header">
                  <h5 class="modal-title">Vacancy Details</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-body" id="vacancyDetailsBody">
                  <div class="text-center">
                      <i class="fa fa-spinner fa-spin"></i> Loading...
                  </div>
              </div>

          </div>
      </div>
  </div>
  <!-- Overlay -->
  <div id="vacancyOverlay"></div>


  <!-- modal for alerts -->
   <!-- Job Status Confirm Modal -->
<div class="modal fade" id="jobStatusModal" tabindex="-1">
 <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">Confirm Action</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center">
        <p id="jobStatusMessage"></p>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" id="confirmJobStatus">Yes Continue</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="jobExistsModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-danger">
        <h5 class="modal-title">Duplicate Job</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center">
        <p>This Job Title already exists.</p>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>

    </div>
  </div>
</div>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const createStepper = document.querySelector('#vacancyPanel .bs-stepper');
          if (createStepper) {
              window.stepper = new Stepper(createStepper);
          }

          const editStepperEl = document.querySelector('#editVacancyPanel .bs-stepper');
          if (editStepperEl) {
              window.editStepper = new Stepper(editStepperEl);
          }
      });

      document.querySelectorAll('.emp-type').forEach(el => {
          el.addEventListener('click', function() {
              document.querySelectorAll('.emp-type').forEach(b => b.classList.remove('active'));
              this.classList.add('active');
              document.getElementById('employment_type').value = this.dataset.value;
          });
      });

      document.querySelectorAll('.work-mode').forEach(el => {
          el.addEventListener('click', function() {
              document.querySelectorAll('.work-mode').forEach(b => b.classList.remove('active'));
              this.classList.add('active');
              document.getElementById('work_mode').value = this.dataset.value;
          });
      });

      document.addEventListener('DOMContentLoaded', function() {

          const expMin = document.getElementById('expMin');
          const expMax = document.getElementById('expMax');

          for (let i = 0; i <= 20; i++) {
              expMin.add(new Option(i + ' Year' + (i > 1 ? 's' : ''), i));
          }
          expMin.add(new Option('20+ Years', '20+'));

          expMin.addEventListener('change', function() {
              expMax.innerHTML = '<option value="">Max</option>';
              if (this.value === '20+') {
                  expMax.add(new Option('30+ Years', '30+'));
                  return;
              }
              const min = parseInt(this.value);
              for (let i = min; i <= 30; i++) {
                  expMax.add(new Option(i + ' Year' + (i > 1 ? 's' : ''), i));
              }
              expMax.add(new Option('30+ Years', '30+'));
          });

          const salaryMin = document.getElementById('salaryMin');
          const salaryMax = document.getElementById('salaryMax');

          for (let i = 1; i <= 50; i++) {
              salaryMin.add(new Option(i + ' LPA', i));
          }
          salaryMin.add(new Option('50+ LPA', '50+'));

          salaryMin.addEventListener('change', function() {
              salaryMax.innerHTML = '<option value="">Max Salary</option>';
              if (this.value === '50+') {
                  salaryMax.add(new Option('100+ LPA', '100+'));
                  return;
              }
              const min = parseInt(this.value);
              for (let i = min; i <= 80; i++) {
                  salaryMax.add(new Option(i + ' LPA', i));
              }
              salaryMax.add(new Option('100+ LPA', '100+'));
          });

      });

      function populateEditExpMin() {
          const el = document.getElementById('edit_expMin');
          el.innerHTML = '<option value="">Min</option>';
          for (let i = 0; i <= 20; i++) {
              el.add(new Option(i + ' Year' + (i > 1 ? 's' : ''), i));
          }
          el.add(new Option('20+ Years', '20+'));
      }

      function populateEditSalMin() {
          const el = document.getElementById('edit_salaryMin');
          el.innerHTML = '<option value="">Min Salary</option>';
          for (let i = 1; i <= 50; i++) {
              el.add(new Option(i + ' LPA', i));
          }
          el.add(new Option('50+ LPA', '50+'));
      }
      $('#edit_salaryMin').on('change', function() {

          const min = parseInt(this.value);
          const maxEl = document.getElementById('edit_salaryMax');

          maxEl.innerHTML = '<option value="">Max Salary</option>';

          if (this.value === '50+') {
              maxEl.add(new Option('100+ LPA', '100+'));
              return;
          }

          for (let i = min + 1; i <= 80; i++) {
              maxEl.add(new Option(i + ' LPA', i));
          }

          maxEl.add(new Option('100+ LPA', '100+'));
      });


      $('#edit_expMin').on('change', function() {

          const min = parseInt(this.value);
          const maxEl = document.getElementById('edit_expMax');

          maxEl.innerHTML = '<option value="">Max</option>';

          if (this.value === '20+') {
              maxEl.add(new Option('30+ Years', '30+'));
              return;
          }

          for (let i = min + 1; i <= 30; i++) {
              maxEl.add(new Option(i + ' Year' + (i > 1 ? 's' : ''), i));
          }

          maxEl.add(new Option('30+ Years', '30+'));
      });
      // const minusBtn = document.querySelector('.minus');
      // const plusBtn  = document.querySelector('.plus');
      // const qtyInput = document.getElementById('positions');

      // function getValue() { return parseInt(qtyInput.value) || 1; }
      // function setValue(val) { qtyInput.value = val < 1 ? 1 : val; }

      // plusBtn.addEventListener('click',  () => setValue(getValue() + 1));
      // minusBtn.addEventListener('click', () => setValue(getValue() - 1));
      // qtyInput.addEventListener('blur',  () => setValue(getValue()));
      // qtyInput.addEventListener('input', () => { qtyInput.value = qtyInput.value.replace(/[^0-9]/g, ''); });

      $(document).ready(function() {
          $('#openVacancyPanel').on('click', function() {
              $('#vacancyPanel').addClass('open');
              $('#vacancyOverlay').addClass('show');
          });
          $('#closeVacancyPanel').on('click', function() {
              $('#vacancyPanel').removeClass('open');
              $('#vacancyOverlay').removeClass('show');
          });
          $('#closeEditVacancyPanel').on('click', function() {
              $('#editVacancyPanel').removeClass('open');
              $('#vacancyOverlay').removeClass('show');
          });
          $('#vacancyOverlay').on('click', function() {
              $('#vacancyPanel, #editVacancyPanel').removeClass('open');
              $('#vacancyOverlay').removeClass('show');
          });
      });

      initChipAutocomplete({
          inputId: 'jobLocationInput',
          dropdownId: 'jobLocationDropdown',
          chipsId: 'jobLocationChips',
          hiddenId: 'jobLocation',
          url: '<?= base_url("admin/searchLocation") ?>',
          key: 'JobLocation'
      });
      initChipAutocomplete({
          inputId: 'educationInput',
          dropdownId: 'educationDropdown',
          chipsId: 'educationChips',
          hiddenId: 'education',
          url: '<?= base_url("admin/searchEducation") ?>',
          key: 'EducationRequired'
      });
      initChipAutocomplete({
          inputId: 'skillsInput',
          dropdownId: 'skillsDropdown',
          chipsId: 'skillsChips',
          hiddenId: 'skills',
          url: '<?= base_url("admin/searchSkills") ?>',
          key: 'SkillName'
      });
      initChipAutocomplete({
          inputId: 'languageInput',
          dropdownId: 'languageDropdown',
          chipsId: 'languageChips',
          hiddenId: 'comLanguage',
          url: '<?= base_url("admin/searchLanguage") ?>',
          key: 'CommunicationLang'
      });

      initChipAutocomplete({
          inputId: 'edit_jobLocationInput',
          dropdownId: 'edit_jobLocationDropdown',
          chipsId: 'edit_jobLocationChips',
          hiddenId: 'edit_jobLocation',
          url: '<?= base_url("admin/searchLocation") ?>',
          key: 'JobLocation'
      });
      initChipAutocomplete({
          inputId: 'edit_educationInput',
          dropdownId: 'edit_educationDropdown',
          chipsId: 'edit_educationChips',
          hiddenId: 'edit_education',
          url: '<?= base_url("admin/searchEducation") ?>',
          key: 'EducationRequired'
      });
      initChipAutocomplete({
          inputId: 'edit_skillsInput',
          dropdownId: 'edit_skillsDropdown',
          chipsId: 'edit_skillsChips',
          url: '<?= base_url("admin/searchSkills") ?>',
          key: 'SkillName'
      });
      initChipAutocomplete({
          inputId: 'edit_languageInput',
          dropdownId: 'edit_languageDropdown',
          chipsId: 'edit_languageChips',
          hiddenId: 'edit_comLanguage',
          url: '<?= base_url("admin/searchLanguage") ?>',
          key: 'CommunicationLang'
      });

      document.querySelectorAll('.edit-emp-type').forEach(el => {
          el.addEventListener('click', function() {
              document.querySelectorAll('.edit-emp-type').forEach(b => b.classList.remove('active'));
              this.classList.add('active');
              document.getElementById('edit_employment_type').value = this.dataset.value;
          });
      });

      document.querySelectorAll('.edit-work-mode').forEach(el => {
          el.addEventListener('click', function() {
              document.querySelectorAll('.edit-work-mode').forEach(b => b.classList.remove('active'));
              this.classList.add('active');
              document.getElementById('edit_work_mode').value = this.dataset.value;
          });
      });

      $(document).on('click', '.editJobBtn', function() {

          const jid = $(this).data('id');
          $('#edit_jid').val(jid);

          $.post('<?= base_url("admin/getJobDetails") ?>', {
              jid: jid
          }, function(res) {

              const d = JSON.parse(res);
              // $('#editJobCodeText').text('(' + d.JobCode + ')');
              $('#editJobCodeText').text(d.JobCode);

              $('#edit_jobCode').val(d.JobCode ?? '');
              $('#edit_jobTitle').val(d.JobTitle ?? '');

              $('#edit_department').val(d.Departmentname ?? '');
              $('#edit_role').val(d.RoleSummary ?? '');

              $('#edit_positions').val(d.NoofOpenings ?? '');
              $('#edit_expiryDate').val((d.ExpiryDate || '').split(' ')[0]);
              $('#edit_JD').val(d.JobDescription ?? '');
              $('#edit_RR').val(d.Responsibilities ?? '');

              populateEditExpMin();
              populateEditSalMin();

              $('#edit_salaryMin').val(d.SalMin);
              $('#edit_expMin').val(d.ExpMin);

              const salMinVal = parseInt(d.SalMin) || 0;
              const salMaxEl = document.getElementById('edit_salaryMax');
              salMaxEl.innerHTML = '<option value="">Max Salary</option>';

              for (let i = salMinVal + 1; i <= 80; i++) {
                  salMaxEl.add(new Option(i + ' LPA', i));
              }
              salMaxEl.add(new Option('100+ LPA', '100+'));
              $('#edit_salaryMax').val(d.SalMax);

              const expMinVal = parseInt(d.ExpMin) || 0;
              const expMaxEl = document.getElementById('edit_expMax');
              expMaxEl.innerHTML = '<option value="">Max</option>';
              for (let i = expMinVal + 1; i <= 30; i++) {
                  expMaxEl.add(new Option(i + ' Year' + (i > 1 ? 's' : ''), i));
              }
              expMaxEl.add(new Option('30+ Years', '30+'));
              $('#edit_expMax').val(d.ExpMax);

              console.log('SalMin:', d.SalMin, '| SalMax:', $('#edit_salaryMax').val());
              console.log('ExpMin:', d.ExpMin, '| ExpMax:', $('#edit_expMax').val());

              const workMode = (d.WorkMode || '').toString().trim();
              const empType = (d.EmploymentType || '').toString().trim();

              $('.edit-work-mode').removeClass('active');
              $('.edit-emp-type').removeClass('active');

              $('.edit-work-mode').each(function() {
                  if ($(this).data('value').trim() === workMode) {
                      $(this).addClass('active');
                      $('#edit_work_mode').val(workMode);
                  }
              });

              $('.edit-emp-type').each(function() {
                  if ($(this).data('value').trim() === empType) {
                      $(this).addClass('active');
                      $('#edit_employment_type').val(empType);
                  }
              });

              preloadChips(d.JobLocation, 'edit_jobLocationChips', 'edit_jobLocation');
              preloadChips(d.EducationRequired, 'edit_educationChips', 'edit_education');
              preloadChips(d.Skills, 'edit_skillsChips', 'edit_skills');
              preloadChips(d.CommunicationLang, 'edit_languageChips', 'edit_comLanguage');

              $('#edit_SkillScore').val(d.SkillScore ?? 50);
              $('#edit_EducationScore').val(d.EducationScore ?? 20);
              $('#edit_ExperienceScore').val(d.ExperienceScore ?? 20);
              $('#edit_ProjectScore').val(d.ProjectScore ?? 5);
              $('#edit_CertificationScore').val(d.CertificationScore ?? 10);
              $('#edit_ResumeQualityScore').val(d.ResumeQualityScore ?? 5);
              $('#edit_DomainKnowledgeScore').val(d.DomainKnowledgeScore ?? 5);
              calculateTotalAtsMarks('#editVacancyPanel');

              $('#editVacancyPanel').addClass('open');
              $('#vacancyOverlay').addClass('show');
          });
      });

   let selectedJobId = '';
let selectedStatus = '';

$(document).on('click', '.jobStatusBtn', function () {

    selectedJobId = $(this).data('id');
    selectedStatus = $(this).data('status');

    let message = '';

    if (selectedStatus === 'Closed') {
        message = "Are you sure you want to close this job?";
    }
    else if (selectedStatus === 'On-Hold') {
        message = "Are you sure you want to put this job on hold?";
    }
    else if (selectedStatus === 'Open') {
        message = "Are you sure you want to reopen this job?";
    }
    else if (selectedStatus === 'Re-Open') {
        message = "Are you sure you want to reopen this job?";
    }
    // Note: 'Re-Open' status is kept for backward compatibility only
    else {
        message = "Are you sure you want to change this job status?";
    }

    $('#jobStatusMessage').text(message);

    $('#jobStatusModal').modal('show');

});


      $('#confirmJobStatus').click(function () {

    $.ajax({
        url: '<?= base_url("admin/updateJobStatus") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            jid: selectedJobId,
            status: selectedStatus
        },
        success: function(res) {

            $('#jobStatusModal').modal('hide');

            if (res.status === 'success') {
                toastr.success(res.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                toastr.error(res.message || 'Something went wrong');
            }

        },
        error: function() {
            toastr.error('Server error occurred');
        }
    });

});
      $('#editVacancyForm').submit(function(e) {
          e.preventDefault();
          $.ajax({
              url: '<?= base_url("admin/updateVacancy") ?>',
              type: 'POST',
              data: $(this).serialize(),
              dataType: 'json',
              success: function(res) {
                  if (res.status == 'success') {
                      toastr.success('Vacancy updated');
                      location.reload();
                  } else {
                      toastr.error('Update failed');
                  }
              }
          });
      });

      $(document).on('click', '.viewVacancyBtn', function() {
          let jid = $(this).data('id');
          $('#vacancyDetailsModal').modal('show');
          $('#vacancyDetailsBody').html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
          $.post('<?= base_url("admin/getJobDetails") ?>', {
              jid: jid
          }, function(res) {
              let d = JSON.parse(res);
              let html = `<div class="container-fluid">`;
              html += `<div class="card card-primary"><div class="card-header bg-primary"><h3 class="card-title">Basic Information</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button></div></div><div class="card-body"><div class="row"><div class="col-md-6"><p><b>Job Code:</b> ${d.JobCode}</p><p><b>Job Title:</b> ${d.JobTitle}</p><p><b>Department:</b> ${d.Departmentname}</p><p><b>Role:</b> ${d.RoleSummary}</p><p><b>Status:</b>
<span class="badge badge-pill
${d.JobStatus === 'Open' || d.JobStatus === 'Re-Open' ? 'badge-success' :
  d.JobStatus === 'Closed' ? 'badge-danger' :
  d.JobStatus === 'On-Hold' ? 'badge-warning' :
  d.JobStatus === 'Draft' ? 'badge-secondary' :
  d.JobStatus === 'Not Required' ? 'badge-dark' :
  'badge-primary'}">
${d.JobStatus}
</span>
</p></div><div class="col-md-6"><p><b>Posted By:</b> ${d.PostedByName}</p><p><b>Posted On:</b> ${d.PostedOn}</p><p><b>Expiry Date:</b> ${d.ExpiryDate}</p><p><b>Work Mode:</b> ${d.WorkMode}</p><p><b>Employment:</b> ${d.EmploymentType}</p><p><b>Language:</b> ${d.CommunicationLang}</p></div></div></div></div>`;
              html += `<div class="card card-info collapsed-card"><div class="card-header bg-info"><h3 class="card-title">Salary & Experience</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><div class="row"><div class="col-md-6"><p><b>Experience Required:</b> ${d.ExpMin ?? 0} - ${d.ExpMax ?? 0} Years</p></div><div class="col-md-6"><p><b>Salary:</b> ${d.SalMin ?? 0} - ${d.SalMax ?? 0} LPA</p></div></div></div></div>`;
              html += `<div class="card card-secondary collapsed-card"><div class="card-header bg-secondary"><h3 class="card-title">Location & Education</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><p><b>Job Location:</b> ${d.JobLocation}</p><p><b>Education Required:</b> ${d.EducationRequired}</p></div></div>`;
              html += `<div class="card card-warning collapsed-card"><div class="card-header bg-warning"><h3 class="card-title">Skills</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body"><p>${d.Skills}</p></div></div>`;
              html += `<div class="card card-dark collapsed-card"><div class="card-header bg-dark"><h3 class="card-title">Roles & Responsibilities</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body">${d.Responsibilities}</div></div>`;
              html += `<div class="card card-success collapsed-card"><div class="card-header bg-success"><h3 class="card-title">Job Description</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div><div class="card-body">${d.JobDescription}</div></div>`;
              
              let totalAts = (parseInt(d.SkillScore ?? 50) +
                              parseInt(d.EducationScore ?? 20) +
                              parseInt(d.ExperienceScore ?? 20) +
                              parseInt(d.ProjectScore ?? 5) +
                              parseInt(d.CertificationScore ?? 10) +
                              parseInt(d.ResumeQualityScore ?? 5) +
                              parseInt(d.DomainKnowledgeScore ?? 5));
              
              html += `<div class="card card-info collapsed-card">` +
                  `<div class="card-header bg-info"><h3 class="card-title">ATS Score Breakdown</h3><div class="card-tools"><button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div></div>` +
                  `<div class="card-body">` +
                  `<div class="row">` +
                  `<div class="col-md-6"><p><b>Skills Match:</b> ${d.SkillScore ?? 50}</p><p><b>Education Match:</b> ${d.EducationScore ?? 20}</p><p><b>Experience Match:</b> ${d.ExperienceScore ?? 20}</p><p><b>Projects & Achievements:</b> ${d.ProjectScore ?? 5}</p></div>` +
                  `<div class="col-md-6"><p><b>Certifications:</b> ${d.CertificationScore ?? 10}</p><p><b>Resume Quality:</b> ${d.ResumeQualityScore ?? 5}</p><p><b>Role Fit / Domain Knowledge:</b> ${d.DomainKnowledgeScore ?? 5}</p><hr class="my-1"><p class="font-weight-bold text-success"><b>Total ATS Marks:</b> ${totalAts}</p></div>` +
                  `</div></div></div>`;

              html += `</div>`;
              $('#vacancyDetailsBody').html(html);
          });
      });

      function preloadChips(values, chipsId, hiddenId) {
          if (!values) return;
          const arr = values.split(',');
          const chips = document.getElementById(chipsId);
          const hidden = hiddenId ? document.getElementById(hiddenId) : null;
          chips.innerHTML = '';
          arr.forEach(v => {
              v = v.trim();
              if (chipsId === 'edit_skillsChips') {
                  $('<input>').attr('type', 'hidden').attr('name', 'skills[]').val(v).appendTo('#editVacancyForm');
              }
              const chip = document.createElement('span');
              chip.className = 'badge badge-pill badge-primary mr-2 mb-2';
              chip.innerHTML = `${v} <span class="cursor-pointer">×</span>`;
              chip.querySelector('span').onclick = () => {
                  chip.remove();
                  if (chipsId === 'edit_skillsChips') {
                      $('#editVacancyForm input[name="skills[]"][value="' + v + '"]').remove();
                  }
                  if (hidden) {
                      hidden.value = [...chips.querySelectorAll('.badge')].map(x => x.textContent.replace('×', '').trim()).join(',');
                  }
              };
              chips.appendChild(chip);
          });
          if (hidden) {
              hidden.value = arr.join(',');
          }
      }

      function initChipAutocomplete(config) {
          const input = document.getElementById(config.inputId);
          const dropdown = document.getElementById(config.dropdownId);
          const chipsContainer = document.getElementById(config.chipsId);
          const hiddenInput = config.hiddenId ? document.getElementById(config.hiddenId) : null;

          function syncHidden() {
              if (!hiddenInput) return;
              hiddenInput.value = [...chipsContainer.querySelectorAll('.badge')].map(x => x.textContent.replace('×', '').trim()).join(',');
          }

          input.addEventListener('keyup', function() {
              const q = this.value.trim();
              if (q.length < 3) {
                  dropdown.style.display = 'none';
                  dropdown.innerHTML = '';
                  return;
              }
              fetch(`${config.url}?q=${encodeURIComponent(q)}`)
                  .then(res => res.json())
                  .then(data => {
                      dropdown.innerHTML = '';
                      if (!data.length) {
                          dropdown.innerHTML = '<span class="dropdown-item disabled">No results</span>';
                      } else {
                          data.forEach(item => {
                              const value = item[config.key];
                              const el = document.createElement('a');
                              el.className = 'dropdown-item';
                              el.textContent = value;
                              el.onclick = () => addChip(value);
                              dropdown.appendChild(el);
                          });
                      }
                      dropdown.style.display = 'block';
                  });
          });

          input.addEventListener('keydown', function(e) {
              if (e.key === 'Enter') {
                  e.preventDefault();
                  const value = input.value.trim();
                  if (value.length >= 2) addChip(value);
              }
          });


          function addChip(value) {
              if (!value) return;

              if (config.inputId === 'edit_skillsInput') {

                  if ($('input[name="skills[]"][value="' + value + '"]').length) return;
                  $('<input>').attr('type', 'hidden').attr('name', 'skills[]').val(value)
                      .appendTo(input.closest('form'));
              }

              if (config.inputId === 'skillsInput') {

                  const existing = [...chipsContainer.querySelectorAll('.badge')]
                      .map(x => x.textContent.replace('×', '').trim());
                  if (existing.includes(value)) return;
              }
              const chip = document.createElement('span');
              chip.className = 'badge badge-pill badge-primary mr-2 mb-2';
              chip.innerHTML = `${value} <span class="cursor-pointer">×</span>`;

              chip.querySelector('span').onclick = () => {
                  chip.remove();
                  if (config.inputId === 'edit_skillsInput') {
                      $('input[name="skills[]"][value="' + value + '"]').remove();
                  }
                  syncHidden();
              };
              chipsContainer.appendChild(chip);
              input.value = '';
              dropdown.style.display = 'none';
              syncHidden();
          }

          document.addEventListener('click', e => {
              if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                  dropdown.style.display = 'none';
              }
          });
      }
      // $(document).ready(function () {

      //   var table = $('#example1').DataTable();


      //     // Department filter
      //     $('#filterDepartment').on('change', function () {
      //         table.column(3).search(this.value).draw();
      //     });

      //     // Status filter
      //     $('#filterStatus').on('change', function () {
      //         table.column(8).search(this.value).draw();
      //     });

      //     var startDate = '';
      //     var endDate = '';

      //     $('#dateRange').daterangepicker({
      //         autoUpdateInput: false,
      //         locale: { cancelLabel: 'Clear' }
      //     });

      //     $('#dateRange').on('apply.daterangepicker', function (ev, picker) {
      //         startDate = picker.startDate.format('YYYY-MM-DD');
      //         endDate = picker.endDate.format('YYYY-MM-DD');
      //         $(this).val(startDate + ' - ' + endDate);
      //         table.draw();
      //     });

      //     $('#dateRange').on('cancel.daterangepicker', function () {
      //         $(this).val('');
      //         startDate = '';
      //         endDate = '';
      //         table.draw();
      //     });

      //     $.fn.dataTable.ext.search.push(function (settings, data) {

      //         if (!startDate || !endDate) return true;

      //         var postedDate = data[9]; // Posted On column
      //         if (!postedDate) return false;

      //         var posted = moment(postedDate);

      //         return posted.isBetween(startDate, endDate, null, '[]');
      //     });

      //     $('#resetFilters').on('click', function () {

      //         $('#filterDepartment').val('');
      //         $('#filterStatus').val('');
      //         $('#dateRange').val('');

      //         startDate = '';
      //         endDate = '';

      //         table.search('').columns().search('').draw();
      //     });

      // });
      $(function() {

          $('#dateRange').daterangepicker({
              autoUpdateInput: false,
              locale: {
                  format: 'YYYY-MM-DD',
                  cancelLabel: 'Clear'
              }
          });

          $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(
                  picker.startDate.format('YYYY-MM-DD') +
                  ' - ' +
                  picker.endDate.format('YYYY-MM-DD')
              );
          });

          $('#dateRange').on('cancel.daterangepicker', function() {
              $(this).val('');
          });

      });

      $(document).ready(function() {

          // Auto submit when department changes
        //   $('select[name="department"]').on('change', function() {
        //       $(this).closest('form').submit();
        //   });
        $('form[action="<?= base_url('admin/vacancies') ?>"] select[name="department"]').on('change', function() {
    $(this).closest('form').submit();
});

          // Auto submit when status changes
          $('select[name="status"]').on('change', function() {
              $(this).closest('form').submit();
          });

          // Auto submit when date selected
          $('#dateRange').on('apply.daterangepicker', function() {
              $(this).closest('form').submit();
          });

      });
      $(document).on('click', '#vacancyDetailsModal [data-card-widget="collapse"]', function() {

          let currentCard = $(this).closest('.card');

          if (currentCard.hasClass('collapsed-card')) {

              $('#vacancyDetailsModal .card').not(currentCard).each(function() {
                  if (!$(this).hasClass('collapsed-card')) {
                      $(this).CardWidget('collapse');
                  }
              });

          }

      });
      <?php if($this->session->flashdata('job_exists')){ ?>


$(document).ready(function(){
    $('#jobExistsModal').modal('show');
});


<?php } ?>
const minusBtn = document.querySelector('.minus');
const plusBtn  = document.querySelector('.plus');
const qtyInput = document.getElementById('positions');

function getValue() {
    return parseInt(qtyInput.value) || 1;
}

function setValue(val) {
    qtyInput.value = val < 1 ? 1 : val;
}

if (plusBtn && minusBtn) {
    plusBtn.addEventListener('click', () => setValue(getValue() + 1));
    minusBtn.addEventListener('click', () => setValue(getValue() - 1));
}

qtyInput.addEventListener('input', () => {
    qtyInput.value = qtyInput.value.replace(/[^0-9]/g, '');
});

// Calculate dynamic ATS marks total
function calculateTotalAtsMarks(containerSelector) {
    let total = 0;
    $(containerSelector).find('.ats-score-input').each(function() {
        let val = parseInt($(this).val()) || 0;
        if (val < 0) {
            val = 0;
            $(this).val(0);
        }
        total += val;
    });
    $(containerSelector).find('.total-ats-marks').text(total);
}

// Bind live total updates and validate inputs for non-negative
$(document).on('input change keyup', '.ats-score-input', function() {
    let val = $(this).val();
    if (val !== '' && parseInt(val) < 0) {
        $(this).val(0);
    }
    if ($(this).closest('#vacancyPanel').length) {
        calculateTotalAtsMarks('#vacancyPanel');
    } else if ($(this).closest('#editVacancyPanel').length) {
        calculateTotalAtsMarks('#editVacancyPanel');
    }
});
  </script>