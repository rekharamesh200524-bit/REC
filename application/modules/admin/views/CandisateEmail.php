<?php 
$action = strtolower($action);
?>
<?php 
if(!empty($candidatelist)) {
    //$Buid = urlencode(base64_encode(base64_encode(base64_encode(base64_encode($RegMail[0]['Buid'])))));
//    echo "<pre>candidatelist"; print_r($candidatelist); exit;
} else {
    redirect($this->config->item('base_url').'admin/index');
}
?>

<!DOCTYPE html>
<html>
<head>
   <title><?php echo ($action == 'rejected') ? 'Application Update' : 'Shortlisted Email'; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: system-ui, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 6px;
            overflow: hidden;
        }
        .header {
            background-color: #3F51B5;
            padding: 25px;
            text-align: center;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
            color: #555555;
            font-size: 14px;
            line-height: 24px;
        }
        .content p {
            margin: 0 0 15px;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background-color: #4caf50;
            color: #ffffff;
            padding: 12px 28px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            display: inline-block;
        }
        .footer {
            padding: 20px 30px;
            background-color: #fafafa;
            color: #777777;
            font-size: 12px;
            line-height: 20px;
        }
    </style>
</head>

<body>

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="padding: 30px 10px;">
            <div class="container">
                
                <!-- Header -->
   <div class="header">
<?php if($action == 'rejected'){ ?>
    Application Update
<?php } elseif($action == 'offer'){ ?>
    Offer Update
<?php } else { ?>
    <?php echo $jobTitle; ?> - Shortlisted
<?php } ?>
</div>

                <!-- Content -->
<div class="content">

<p>Dear <strong><?php echo $candidatelist->Fullname; ?></strong>,</p>

<?php if($action == 'shortlisted'){ ?>

    <p>Good day!</p>

    <p>
        We are pleased to inform you that your profile has been shortlisted 
        for the position.
    </p>

    <p>
        Based on your experience and skills, we would like to invite you 
        to the next stage of the selection process.
    </p>

    <p><strong>Interview Details:</strong></p>
    <p>
   Date: <?php echo date('d M Y', strtotime($interviewDate)); ?><br>

Mode: <?php echo ucfirst($interviewMode); ?>
    </p>

<?php } elseif($action == 'rejected'){ ?>

    <p>Thank you for applying with us.</p>

    <p>
        After careful consideration, we regret to inform you that your profile 
        has not been selected for further rounds.
    </p>

    <p>
        We appreciate your time and effort and encourage you to apply again in future.
    </p>

<?php } elseif($action == 'offer'){ ?>

    <p>Congratulations!</p>

    <p>
        We are pleased to inform you that you have been selected 
        and we are happy to extend an offer to you.
    </p>

    <p>
        Our HR team will contact you with further details regarding 
        your joining and offer process.
    </p>

<?php } ?>

<p>
    If you have any questions, feel free to reach out.
</p>

</div>

                <!-- Footer -->
                <div class="footer">
                    <p>
                        For any assistance, feel free to reach us at  
                        <strong>info@inetcsc.com</strong> or call us at <strong>+91 44 4400 666</strong>.
                    </p>

                    <p>
                        Best regards,<br>
                        <strong>HR Team</strong>
                    </p>

                    <img src="cid:I-NET" alt="Company Logo">
                </div>

            </div>
        </td>
    </tr>
</table>

</body>
</html>
