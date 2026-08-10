<!DOCTYPE html>
<html lang="en">
<?php
    $employee_det = $this->session->userdata('logged_in');
         $currentUrl = strtolower(uri_string());
         if (!isset($currentUrlArray)) {
             $currentUrlArray = ['parent' => null, 'child' => null];
         }
         $isActive   = !empty($currentUrlArray['child'])
              && strtolower($currentUrlArray['child']['Menuurl']) === $currentUrl;
       // echo "<pre>cur_view isActive"; print_r($employee_det); exit;       
     if(empty($employee_det)) { redirect($this->config->item('base_url').'admin/index'); }
?>
 
<?php $theme_path = $this->config->item('theme_locations').$this->config->item('active_template'); ?>

<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INET - RECRUITMENT PORTAL</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <!-- daterange picker -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/daterangepicker/daterangepicker.css">

  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet"
    href="<?=$theme_path?>/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
     <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- BS Stepper -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/bs-stepper/css/bs-stepper.min.css">

   <!-- dropzonejs -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/dropzone/min/dropzone.min.css">
    <!-- Toastr -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$theme_path?>/assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?=$theme_path?>/css/canvas-modal.css">
  <link rel="stylesheet" href="<?=$theme_path?>/css/custom-style.css">

 <script src="<?= $theme_path ?>/js/jquery-1.8.2.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $theme_path ?>/js/jquery-ui.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader hrms-preloader">
    <div class="radar-loader">
      <i class="fas fa-users radar-icon"></i>
    </div>
    <div class="radar-text">Analyzing</div>
  </div>     
 <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     

      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" id="notifDropdownToggle">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="notifBadge" style="display:none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><span id="notifCountText">0</span> Unread Notifications</span>
          <div class="dropdown-divider"></div>
          <div id="notifListContainer" style="max-height: 300px; overflow-y: auto;">
             <!-- Injected via AJAX -->
             <a href="#" class="dropdown-item text-center text-muted">Loading...</a>
          </div>
          <div class="dropdown-divider"></div>
          <a href="javascript:void(0);" class="dropdown-item dropdown-footer" id="markAllReadBtn">Mark All as Read</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>

      <li class="nav-item">
  <a href="<?= base_url('admin/logout'); ?>" class="nav-link text-danger">
    <i class="fas fa-sign-out-alt"></i> Logout
  </a>
 </li>
    <!--added by reka -->
    </ul>
  </nav>
  <!-- /.navbar -->


<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="<?=$theme_path?>/assets/dist/img/favicon.png" alt="AdminLTE Logo"  style="opacity: .8">
      <span class="brand-text font-weight-thick">HRMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
     <!--  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?=$theme_path?>/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $employee_det['EmpName'];?></a>
         </div>
      </div>
 -->
      <!-- SidebarSearch Form -->
      <div class="form-inline mt-3 pb-3 mb-3 d-flex">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
          
            <?php if (!empty($menuTree)): ?>
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <?php foreach ($menuTree as $parent): ?>
                        <?php 
                        // Determine if this parent menu item should be active and expanded
                        $isParentActive = false;
                        if (!empty($currentUrlArray['parent']) && $currentUrlArray['parent']['IHMid'] == $parent['IHMid']) {
                            $isParentActive = true;
                        } else {
                            // Fallback: check if any child of this parent is active
                            if (!empty($parent['children']) && !empty($currentUrlArray['child'])) {
                                foreach ($parent['children'] as $child) {
                                    if ($child['IHMid'] == $currentUrlArray['child']['IHMid']) {
                                        $isParentActive = true;
                                        break;
                                    }
                                }
                            }
                        }
                        ?>

                        <?php if (!empty($parent['children'])): ?>
                            <!-- Parent with submenu -->
                            <li class="nav-item <?= $isParentActive ? 'menu-open' : ''; ?>">
                                <a href="#" class="nav-link">
                                    <?php 
                                    $parentIcon = !empty($parent['MenuIcon']) ? trim($parent['MenuIcon']) : '';
                                    if ($parentIcon === 'fa-dashboard') {
                                        $parentIcon = 'fa-tachometer-alt';
                                    }
                                    if (!empty($parentIcon)) {
                                        if (strpos($parentIcon, ' ') === false) {
                                            $parentIcon = 'fas ' . $parentIcon;
                                        }
                                    } else {
                                        $parentIcon = 'fas fa-folder';
                                    }
                                    ?>
                                    <i class="nav-icon <?= $parentIcon; ?>"></i>
                                    <p>
                                        <?= $parent['MenuName']; ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    <?php foreach ($parent['children'] as $child): ?>
                                        <?php 
                                        $isChildActive = !empty($currentUrlArray['child']) && $currentUrlArray['child']['IHMid'] == $child['IHMid'];
                                        ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url($child['Menuurl']); ?>" class="nav-link <?= $isChildActive ? 'active' : ''; ?>">
                                                <?php 
                                                $childIcon = !empty($child['MenuIcon']) ? trim($child['MenuIcon']) : '';
                                                if ($childIcon === 'fa-dashboard') {
                                                    $childIcon = 'fa-tachometer-alt';
                                                }
                                                if (!empty($childIcon)) {
                                                    if (strpos($childIcon, ' ') === false) {
                                                        $childIcon = 'fas ' . $childIcon;
                                                    }
                                                } else {
                                                    $childIcon = 'far fa-circle';
                                                }
                                                ?>
                                                <i class="<?= $childIcon; ?> nav-icon"></i>
                                                <p><?= $child['MenuName']; ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                        <?php else: ?>
                            <!-- Single menu item -->
                            <?php 
                            $isSingleActive = (!empty($currentUrlArray['child']) && $currentUrlArray['child']['IHMid'] == $parent['IHMid'])
                                || (strtolower($parent['Menuurl']) === $currentUrl);
                            ?>
                            <li class="nav-item">
                                <a href="<?= base_url($parent['Menuurl']); ?>" class="nav-link <?= $isSingleActive ? 'active' : ''; ?>">
                                    <?php 
                                    $parentIcon = !empty($parent['MenuIcon']) ? trim($parent['MenuIcon']) : '';
                                    if ($parentIcon === 'fa-dashboard') {
                                        $parentIcon = 'fa-tachometer-alt';
                                    }
                                    if (!empty($parentIcon)) {
                                        if (strpos($parentIcon, ' ') === false) {
                                            $parentIcon = 'fas ' . $parentIcon;
                                        }
                                    } else {
                                        $parentIcon = 'fas fa-th';
                                    }
                                    ?>
                                    <i class="nav-icon <?= $parentIcon; ?>"></i>
                                    <p><?= $parent['MenuName']; ?></p>
                                </a>
                            </li>
                        <?php endif; ?>

                    <?php endforeach; ?>

                </ul>
                <?php endif; ?>
             
       </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <!-- Page Title -->
            <div class="col-sm-6">
              <h1 class="m-0">
                <?= !empty($currentUrlArray['child'])
                    ? $currentUrlArray['child']['MenuName']
                    : (!empty($currentUrlArray['parent'])
                        ? $currentUrlArray['parent']['MenuName']
                        : 'Dashboard'); ?>
              </h1>
            </div>

            <!-- Breadcrumb -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <!-- Home -->
                <li class="breadcrumb-item">
                  <a href="<?= base_url('admin/dashboard'); ?>">Home</a>
                </li>

                <!-- Parent -->
                <?php if (!empty($currentUrlArray['parent'])): ?>
                  <li class="breadcrumb-item">
                    <?php if ($currentUrlArray['parent']['MenuUrl'] !== '#'): ?>
                      <a href="<?= base_url($currentUrlArray['parent']['MenuUrl']); ?>">
                        <?= $currentUrlArray['parent']['MenuName']; ?>
                      </a>
                    <?php else: ?>
                      <?= $currentUrlArray['parent']['MenuName']; ?>
                    <?php endif; ?>
                  </li>
                <?php endif; ?>

                <!-- Child -->
                <?php if (!empty($currentUrlArray['child'])): ?>
                  <li class="breadcrumb-item active">
                    <?= $currentUrlArray['child']['MenuName']; ?>
                  </li>
                <?php endif; ?>

              </ol>
              </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
      </div>
    <!-- /.content-header -->

     <?php echo $content;?>

  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    Copyright   <script> document.write(new Date().getFullYear());
                </script> Designed &amp; Developed by <a href="https://www.i-net.in/" target="_blank">I-NET Secure Labs Pvt. Ltd</a>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS --> 
<!-- jQuery -->
  <script src="<?=$theme_path?>/assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?=$theme_path?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <!-- overlayScrollbars -->
<script src="<?=$theme_path?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

  <!-- Select2 -->
  <script src="<?=$theme_path?>/assets/plugins/select2/js/select2.full.min.js"></script>
  <!-- Bootstrap4 Duallistbox -->
  <script src="<?=$theme_path?>/assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
  <!-- InputMask -->
  <script src="<?=$theme_path?>/assets/plugins/moment/moment.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- date-range-picker -->
  <script src="<?=$theme_path?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap color picker -->
  <script src="<?=$theme_path?>/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="<?=$theme_path?>/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Bootstrap Switch -->
  <script src="<?=$theme_path?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

  <!-- dropzonejs -->
  <script src="<?=$theme_path?>/assets/plugins/dropzone/min/dropzone.min.js"></script>
  <!-- Toastr -->
<script src="<?=$theme_path?>/assets/plugins/toastr/toastr.min.js"></script>
  <!-- BS-Stepper -->
  <script src="<?=$theme_path?>/assets/plugins/bs-stepper/js/bs-stepper.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?=$theme_path?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/jszip/jszip.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="<?=$theme_path?>/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
   <script src="<?= $theme_path ?>/assets/plugins/chart.js/Chart.min.js"></script>

                <!---->
  <!-- AdminLTE App -->
  <script src="<?= $theme_path ?>/assets/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
<script src="<?=$theme_path?>/assets/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=$theme_path?>/assets/dist/js/pages/dashboard2.js"></script>
<script>
    var base_url = "<?= base_url(); ?>";
</script>
<script>
$(function () {

  <?php if ($this->session->flashdata('success')): ?>
    toastr.success("<?= $this->session->flashdata('success'); ?>");
  <?php endif; ?>

  <?php if ($this->session->flashdata('error')): ?>
    toastr.error("<?= $this->session->flashdata('error'); ?>");
  <?php endif; ?>

  <?php if ($this->session->flashdata('info')): ?>
    toastr.info("<?= $this->session->flashdata('info'); ?>");
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    toastr.warning("<?= $this->session->flashdata('warning'); ?>");
  <?php endif; ?>

});
</script>
<script src="<?=$theme_path?>/js/custom-script.js"></script>
</html>