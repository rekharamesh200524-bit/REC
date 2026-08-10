<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="Cache-control" content="public"/>
        <meta http-equiv="expires" content="Tue, 29 Sep 2015 1:00:00 GMT" />
        <?php $theme_path = $this->config->item('theme_locations').$this->config->item('active_template'); ?>
		<link href="<?=$theme_path?>/images/favicon.png" rel="shortcut icon">
       <title>INET - HRMS | Login - 2026</title>
        <!-- <link type="text/css" href="<?= $theme_path; ?>/css/style.default_login.css" rel="stylesheet"> -->
        <script type="text/javascript" src="<?= $theme_path; ?>/js/jquery-1.11.1.min.js"></script>
        <link href="<?=$theme_path?>/css/jquery-ui.css" rel="stylesheet" />
        <script type="text/javascript" src="<?= $theme_path; ?>/js/jquery-ui.js"></script>

 
    </head>

   <body class="h-100">
       <div class="authincation h-100">
             	<?php echo $content; ?>
 
        </div>
       
    </body>
</html>
