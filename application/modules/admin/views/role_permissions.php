<?php
$ci = &get_instance();
$employee_det = (isset($this) && isset($this->session)) ? $this->session->userdata('logged_in') : (isset($ci->session) ? $ci->session->userdata('logged_in') : []);

if (empty($employee_det)) {
    redirect($this->config->item('base_url').'admin/index');
}

$theme_path = (isset($this) && isset($this->config)) ? $this->config->item('theme_locations').$this->config->item('active_template') : '';

$parents = [];
$children = [];

// Separate parent & child
foreach ($menus as $menu) {
    if (empty($menu['ParentId']) || $menu['ParentId'] == 0) {
        $parents[] = $menu;
    } else {
        $children[$menu['ParentId']][] = $menu;
    }
}
?>

<style>
/* ROLE PERMISSIONS PREMIUM STYLING */
.role-select-box {
  background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
  border: 1px solid #cbd5e1;
  border-radius: 14px;
  padding: 20px 24px;
}

.perm-module-card {
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  background: #ffffff;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
  transition: all 0.3s ease;
  overflow: hidden;
  height: 100%;
}

.perm-module-card:hover {
  box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
  border-color: #cbd5e1;
}

.perm-module-header {
  background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
  padding: 16px 20px;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.perm-module-body {
  padding: 18px 20px;
}

.perm-item-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  margin-bottom: 10px;
  transition: all 0.25s ease;
}

.perm-item-row:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
  transform: translateX(4px);
}

.perm-item-label {
  display: flex;
  align-items: center;
  gap: 12px;
  font-weight: 600;
  color: #1e293b;
  font-size: 14px;
  cursor: pointer;
  margin-bottom: 0;
  width: 100%;
}

.perm-icon-pill {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  background: #e0e7ff;
  color: #4338ca;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  flex-shrink: 0;
}

/* Custom iOS Switch Styling */
.custom-switch .custom-control-label::before {
  width: 44px !important;
  height: 24px !important;
  border-radius: 20px !important;
  background-color: #cbd5e1 !important;
  border: none !important;
  top: -2px !important;
}

.custom-switch .custom-control-label::after {
  width: 18px !important;
  height: 18px !important;
  border-radius: 50% !important;
  background-color: #ffffff !important;
  top: 1px !important;
  left: -40px !important;
  transition: transform 0.25s ease, background-color 0.25s ease !important;
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::before {
  background-color: #6366f1 !important;
}

.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  transform: translateX(20px) !important;
}
</style>

<section class="content pt-3 pb-4">
  <div class="container-fluid">
    <div class="card card-primary card-outline shadow-sm border-0" style="border-radius:16px;">
      
      <!-- CARD HEADER -->
      <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <div>
            <h3 class="card-title font-weight-bold mb-1 text-dark d-flex align-items-center">
              <i class="fas fa-user-shield text-primary mr-2"></i> Role Access & Permissions Engine
            </h3>
            <p class="text-muted small mb-0">Manage system module access and feature permissions for organizational roles.</p>
          </div>

          <div class="d-flex gap-2 mt-2 mt-sm-0">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill font-weight-bold mr-2" id="selectAllBtn">
              <i class="fas fa-check-double mr-1"></i> Select All
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill font-weight-bold mr-2" id="clearAllBtn">
              <i class="fas fa-undo mr-1"></i> Clear All
            </button>
            <button class="btn btn-primary btn-sm rounded-pill font-weight-bold px-4 shadow-sm" id="savePermissions">
              <i class="fas fa-save mr-1"></i> Save Permissions
            </button>
          </div>
        </div>
      </div>

      <div class="card-body p-4">

        <!-- ROLE SELECTION BAR -->
        <div class="role-select-box mb-4">
          <div class="row align-items-center">
            <div class="col-md-6 col-sm-12">
              <label class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                <i class="fas fa-user-tag text-primary mr-2"></i> Select Role to Configure
              </label>
              <select id="roleDropdown" class="form-control form-control-lg font-weight-bold shadow-sm" style="border-radius:12px;">
                <option value="">-- Choose Organizational Role --</option>
                <?php foreach($roles as $r){ ?>
                 <option value="<?= $r['Erid']; ?>" <?= (isset($selectedRole) && $selectedRole == $r['Erid']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($r['RoleName']); ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-6 col-sm-12 mt-3 mt-md-0 text-md-right">
              <span class="badge badge-light border px-3 py-2 text-muted font-weight-normal" style="border-radius:20px; font-size:13px;">
                <i class="fas fa-info-circle text-info mr-1"></i> Changes apply immediately to users in selected role.
              </span>
            </div>
          </div>
        </div>

        <!-- MODULE PERMISSIONS GRID CARDS -->
        <div class="row">
          <?php foreach($parents as $parent): ?>
            <?php 
              $pId = $parent['IHMid'];
              $pName = $parent['MenuName'];
              $pNameLower = strtolower($pName);
              
              // Icon mapping
              $parentIcon = 'fas fa-folder';
              if (strpos($pNameLower, 'dashboard') !== false) $parentIcon = 'fas fa-tachometer-alt';
              elseif (strpos($pNameLower, 'admin') !== false) $parentIcon = 'fas fa-cogs';
              elseif (strpos($pNameLower, 'vaccancy') !== false || strpos($pNameLower, 'vacancy') !== false) $parentIcon = 'fas fa-briefcase';
              elseif (strpos($pNameLower, 'interview') !== false) $parentIcon = 'fas fa-calendar-alt';
              elseif (strpos($pNameLower, 'analytics') !== false || strpos($pNameLower, 'history') !== false) $parentIcon = 'fas fa-chart-line';

              $hasChildren = isset($children[$pId]) && !empty($children[$pId]);
            ?>

            <div class="col-lg-6 col-md-12 mb-4">
              <div class="perm-module-card">
                
                <!-- PARENT MODULE HEADER -->
                <div class="perm-module-header">
                  <div class="d-flex align-items-center">
                    <i class="<?= $parentIcon; ?> mr-2 text-info" style="font-size:18px;"></i>
                    <h5 class="font-weight-bold mb-0 text-white" style="font-size:16px; font-family:'Outfit',sans-serif;">
                      <?= htmlspecialchars($pName); ?>
                    </h5>
                  </div>

                  <div class="custom-control custom-switch">
                    <input type="checkbox"
                           class="custom-control-input menuCheckbox parentMenu"
                           value="<?= $pId; ?>"
                           id="menu_<?= $pId; ?>">
                    <label class="custom-control-label" for="menu_<?= $pId; ?>"></label>
                  </div>
                </div>

                <!-- CHILD MODULES BODY -->
                <div class="perm-module-body">
                  <?php if($hasChildren): ?>
                    <?php foreach($children[$pId] as $child): ?>
                      <?php 
                        $cId = $child['IHMid'];
                        $cName = $child['MenuName'];
                        $cNameLower = strtolower($cName);

                        $childIcon = 'fas fa-circle-notch';
                        if (strpos($cNameLower, 'user') !== false) $childIcon = 'fas fa-users-cog';
                        elseif (strpos($cNameLower, 'department') !== false) $childIcon = 'fas fa-building';
                        elseif (strpos($cNameLower, 'stage') !== false) $childIcon = 'fas fa-layer-group';
                        elseif (strpos($cNameLower, 'permission') !== false || strpos($cNameLower, 'role') !== false) $childIcon = 'fas fa-user-shield';
                        elseif (strpos($cNameLower, 'vaccancy') !== false || strpos($cNameLower, 'vacancy') !== false) $childIcon = 'fas fa-list-alt';
                        elseif (strpos($cNameLower, 'requested') !== false) $childIcon = 'fas fa-clipboard-list';
                        elseif (strpos($cNameLower, 'approved') !== false) $childIcon = 'fas fa-check-circle';
                        elseif (strpos($cNameLower, 'interview') !== false) $childIcon = 'fas fa-calendar-check';
                        elseif (strpos($cNameLower, 'candidate') !== false) $childIcon = 'fas fa-user-tie';
                      ?>

                      <div class="perm-item-row">
                        <label class="perm-item-label" for="menu_<?= $cId; ?>">
                          <span class="perm-icon-pill"><i class="<?= $childIcon; ?>"></i></span>
                          <span><?= htmlspecialchars($cName); ?></span>
                        </label>

                        <div class="custom-control custom-switch">
                          <input type="checkbox"
                                 class="custom-control-input menuCheckbox childMenu"
                                 data-parent="<?= $pId; ?>"
                                 value="<?= $cId; ?>"
                                 id="menu_<?= $cId; ?>">
                          <label class="custom-control-label" for="menu_<?= $cId; ?>"></label>
                        </div>
                      </div>

                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="text-muted small py-2 text-center">
                      <i class="fas fa-check-circle text-success mr-1"></i> Direct main page module (No sub-menus).
                    </div>
                  <?php endif; ?>
                </div>

              </div>
            </div>

          <?php endforeach; ?>
        </div>

      </div>

    </div>
  </div>
</section>

<!-- SAVE PERMISSION CONFIRM MODAL -->
<div class="modal fade" id="savePermissionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">

      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-save mr-2"></i> Confirm Permission Update</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center py-4">
        <div class="mb-3">
          <i class="fas fa-shield-alt text-primary" style="font-size:48px;"></i>
        </div>
        <h6 class="font-weight-bold text-dark">Save Updated Permissions?</h6>
        <p class="text-muted small mb-0">Are you sure you want to update the system module access permissions for the selected role?</p>
      </div>

      <div class="modal-footer justify-content-center bg-light">
        <button class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" id="confirmSavePermissions">
          Yes, Save Permissions
        </button>
      </div>

    </div>
  </div>
</div>

<script>
var base_url = "<?= base_url(); ?>";

document.addEventListener('DOMContentLoaded', function(){

    var roleDropdown = document.getElementById('roleDropdown');
    var saveBtn      = document.getElementById('savePermissions');

    // LOAD PERMISSIONS
    function loadPermissions(roleId){

        document.querySelectorAll('.menuCheckbox').forEach(function(cb){
            cb.checked = false;
        });

        var formData = new FormData();
        formData.append('roleId', roleId);

        fetch(base_url + "admin/getRolePermissions", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            data.forEach(function(menuId){
                var cb = document.querySelector('.menuCheckbox[value="'+menuId+'"]');
                if(cb) cb.checked = true;
            });
        })
        .catch(err => console.error('Load error:', err));
    }

    // ROLE CHANGE
    roleDropdown.addEventListener('change', function(){
        var roleId = this.value;
        if(roleId != ""){
            loadPermissions(roleId);
        }
    });

    // AUTO LOAD ROLE FROM URL
    var urlParams = new URLSearchParams(window.location.search);
    var selectedRole = urlParams.get('role');

    if(selectedRole){
        roleDropdown.value = selectedRole;
        loadPermissions(selectedRole);
    }

    // OPEN CONFIRM MODAL
    saveBtn.addEventListener('click', function(){
        var roleId = roleDropdown.value;
        if(roleId == ""){
            toastr.warning("Please select a role first.");
            return;
        }
        $('#savePermissionModal').modal('show');
    });

    // PARENT → CHILD CHECK
    document.querySelectorAll('.parentMenu').forEach(function(parent){
        parent.addEventListener('change', function(){
            let parentId = this.value;
            document.querySelectorAll('.childMenu').forEach(function(child){
                if(child.dataset.parent == parentId){
                    child.checked = parent.checked;
                }
            });
        });
    });

    // SELECT ALL BUTTON
    var selectAllBtn = document.getElementById('selectAllBtn');
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function(){
            document.querySelectorAll('.menuCheckbox').forEach(function(cb){
                cb.checked = true;
            });
        });
    }

    // CLEAR ALL BUTTON
    var clearAllBtn = document.getElementById('clearAllBtn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(){
            document.querySelectorAll('.menuCheckbox').forEach(function(cb){
                cb.checked = false;
            });
        });
    }

    // SAVE PERMISSIONS
    document.getElementById('confirmSavePermissions').addEventListener('click', function(){

        var roleId = roleDropdown.value;
        var formData = new FormData();
        formData.append('roleId', roleId);

        document.querySelectorAll('.menuCheckbox:checked').forEach(function(cb){
            formData.append('menus[]', cb.value);
        });

        fetch(base_url + "admin/saveRolePermissions", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            $('#savePermissionModal').modal('hide');

            if (data.status === 'success') {
                toastr.success('Role permissions updated successfully.');
            } else {
                toastr.error(data.msg || 'Something went wrong.');
            }

            setTimeout(function(){
                window.location.href = base_url + "admin/RolePermissions?role=" + roleId;
            }, 1200);

        })
        .catch(err => {
            console.error('Save error:', err);
            toastr.error("Something went wrong! Please try again.");
        });

    });

});
</script>