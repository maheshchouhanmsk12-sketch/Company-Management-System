<?php
  // require_once('sess_auth.php');
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  	<title><?php echo ($_settings->info('title') ? $_settings->info('title').' | ' : '') . ($_settings->info('name') ?: 'Company Management System'); ?></title>
    <link rel="icon" href="<?php echo function_exists('get_logo_url') ? get_logo_url() : validate_image($_settings->info('logo')) ?>" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- FontAwesome Free Icons -->
    <link rel="stylesheet" href="<?php echo base_url ?>plugins/fontawesome-free/css/all.min.css">

    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url ?>profile_asset/css/default.css">
    <link rel="stylesheet" href="<?php echo base_url ?>profile_asset/css/layout.css">
    <link rel="stylesheet" href="<?php echo base_url ?>profile_asset/css/media-queries.css">
    <link rel="stylesheet" href="<?php echo base_url ?>profile_asset/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo base_url ?>dist/css/custom.css">

    <!-- Scripts -->
    <script src="<?php echo base_url ?>profile_asset/js/modernizr.js"></script>

<style>
  .col-lg-12{
    width:100%;
    padding: 4rem 2rem;
  }
  .col-lg-6{
    width:50%;
    padding: 1.5rem;
  }
  .d-flex{
    display:flex;
    width:100%;
    flex-wrap: wrap;
  }
  .form-control{
    width: 100% !important;
    background: #1e293b;
    border: 1px solid #334155;
    color: #fff;
    padding: 10px 14px;
    border-radius: 6px;
  }
  .form-control:focus {
    background: #0f172a;
    border-color: #00bcd4;
    color: #fff;
    outline: none;
  }
  .text-light{
    color: white;
  }
  .text-success{
    color: #28a745;
  }
  .text-danger{
    color: #dc3545;
  }
</style>
</head>
