<?php
$employee_det = $this->session->userdata('logged_in');

if (empty($employee_det)) {
    redirect($this->config->item('base_url').'admin/index');
}

$theme_path = $this->config->item('theme_locations').$this->config->item('active_template');
?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
     <div class="card-header">
    <h3 class="card-title mb-0">Role Permissions</h3>
      </div>

      <div class="card-body">

        <!-- ROLE DROPDOWN -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label><strong>Select Role</strong></label>
            <select id="roleDropdown" class="form-control">
              <option value="">Select Role</option>
              <?php foreach($roles as $r){ ?>
               <option value="<?= $r['Erid']; ?>" <?= (isset($selectedRole) && $selectedRole == $r['Erid']) ? 'selected' : ''; ?>>
                  <?= $r['RoleName']; ?>
                </option>
              <?php } ?>
            </select>
          </div>
        </div>

        <hr>
<?php
$parents = [];
$children = [];

// Separate parent & child
foreach ($menus as $menu) {
    if ($menu['ParentId'] == 0) {
        $parents[] = $menu;
    } else {
        $children[$menu['ParentId']][] = $menu;
    }
}
?>
        <!-- MENU CHECKBOX AREA -->
        <div class="row">

<?php foreach($parents as $parent){ ?>

    <!-- Parent Menu -->
  <div class="col-md-12 mt-3 mb-2">
    <div class="custom-control custom-checkbox">

        <input type="checkbox"
               class="custom-control-input menuCheckbox parentMenu"
               value="<?= $parent['IHMid']; ?>"
               id="menu_<?= $parent['IHMid']; ?>">

        <label class="custom-control-label font-weight-bold"
               for="menu_<?= $parent['IHMid']; ?>">
            <?= $parent['MenuName']; ?>
        </label>

    </div>
</div>

    <!-- Child Menus -->
    <?php if(isset($children[$parent['IHMid']])){ ?>
        <?php foreach($children[$parent['IHMid']] as $child){ ?>

            <div class="col-md-12 mb-2">
                <div class="custom-control custom-checkbox ml-5">
                    <input type="checkbox"
       class="custom-control-input menuCheckbox childMenu"
       data-parent="<?= $parent['IHMid']; ?>"
       value="<?= $child['IHMid']; ?>"
       id="menu_<?= $child['IHMid']; ?>">

                    <label class="custom-control-label"
                           for="menu_<?= $child['IHMid']; ?>">
                        <?= $child['MenuName']; ?>
                    </label>
                </div>
            </div>

        <?php } ?>
    <?php } ?>

<?php } ?>

</div>

        <div class="mt-4 text-right">
          <button class="btn btn-primary" id="savePermissions">
            <i class="fas fa-save"></i> Save Permissions
          </button>
        </div>

      </div>

    </div>

  </div>
</section>

<!-- modal for confirmation -->
 <!-- Save Permission Confirm Modal -->
<div class="modal fade" id="savePermissionModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">Confirm Save</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center">
        <p>Are you sure you want to save these permissions?</p>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="confirmSavePermissions">
          Yes Save
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

    // =====================
    // LOAD PERMISSIONS
    // =====================
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

    // =====================
    // ROLE CHANGE
    // =====================
    roleDropdown.addEventListener('change', function(){
        var roleId = this.value;
        if(roleId != ""){
            loadPermissions(roleId);
        }
    });

    // =====================
    // AUTO LOAD ROLE FROM URL
    // =====================
    var urlParams = new URLSearchParams(window.location.search);
    var selectedRole = urlParams.get('role');

    if(selectedRole){
        roleDropdown.value = selectedRole;
        loadPermissions(selectedRole);
    }

    // =====================
    // OPEN CONFIRM MODAL
    // =====================
    saveBtn.addEventListener('click', function(){

        var roleId = roleDropdown.value;

        if(roleId == ""){
            toastr.warning("Please select a role first.");
            return;
        }

        $('#savePermissionModal').modal('show');

    });

    // =====================
    // PARENT → CHILD CHECK
    // =====================
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

    // =====================
    // SAVE PERMISSIONS
    // =====================
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