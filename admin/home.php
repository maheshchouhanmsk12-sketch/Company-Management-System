<?php
$services_cnt = 0;
$clients_cnt = 0;
$testimonials_cnt = 0;
$messages_cnt = 0;

if(isset($conn) && $conn instanceof mysqli){
    $r1 = @$conn->query("SELECT count(*) FROM services");
    if($r1) $services_cnt = $r1->fetch_row()[0];
    
    $r2 = @$conn->query("SELECT count(*) FROM clients");
    if($r2) $clients_cnt = $r2->fetch_row()[0];

    $r3 = @$conn->query("SELECT count(*) FROM testimonials");
    if($r3) $testimonials_cnt = $r3->fetch_row()[0];

    $r4 = @$conn->query("SELECT count(*) FROM messages");
    if($r4) $messages_cnt = $r4->fetch_row()[0];
}
?>

<?php if(!isset($conn) || !$conn): ?>
<div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-left: 5px solid #f59e0b;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h5 class="font-weight-bold"><i class="icon fas fa-exclamation-triangle text-dark"></i> Aiven Database Setup Kya Hai & Isko Kaise Connect Karein?</h5>
    <p class="mb-2">
        Aapki website Render par successfully live run ho rahi hai aur aap Default Administrator session me hain. Lekin <strong>Aiven Cloud MySQL Database</strong> connect hona baaki hai taaki aapka naya data (services, clients, messages) cloud me permanently save ho sake.
    </p>
    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
        <a href="<?php echo base_url ?>db_setup.php" target="_blank" class="btn btn-dark btn-sm font-weight-bold mr-2">
            <i class="fas fa-database text-warning mr-1"></i> Open 1-Click Database Setup (db_setup.php)
        </a>
        <span class="text-muted small">Aiven Console se <strong>Service URI</strong> copy karke is tool me paste karein aur 1-click me connect & import karein.</span>
    </div>
</div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="font-weight-bold mb-1">Welcome back, <?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?>!</h2>
                        <p class="text-muted mb-0">Manage your company website content, services, clients, and inquiries from one centralized dashboard.</p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="<?php echo base_url ?>index.php" target="_blank" class="btn btn-outline-primary mr-2">
                            <i class="fas fa-external-link-alt mr-1"></i> View Live Site
                        </a>
                        <a href="<?php echo base_url ?>db_setup.php" class="btn btn-outline-success">
                            <i class="fas fa-database mr-1"></i> DB Health Check
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Services</span>
                <span class="info-box-number"><?php echo $services_cnt ?></span>
                <a href="<?php echo base_url ?>admin/index.php?page=services" class="small text-muted">Manage Services <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Clients</span>
                <span class="info-box-number"><?php echo $clients_cnt ?></span>
                <a href="<?php echo base_url ?>admin/index.php?page=clients" class="small text-muted">Manage Clients <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-quote-left"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Testimonials</span>
                <span class="info-box-number"><?php echo $testimonials_cnt ?></span>
                <a href="<?php echo base_url ?>admin/index.php?page=testimonials" class="small text-muted">Manage Reviews <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-envelope-open-text"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Inquiries</span>
                <span class="info-box-number"><?php echo $messages_cnt ?></span>
                <a href="<?php echo base_url ?>admin/index.php?page=inquiries" class="small text-muted">View Messages <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links & System Information -->
<div class="row mt-2">
    <div class="col-md-7">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-tasks mr-2 text-primary"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <a href="<?php echo base_url ?>admin/index.php?page=services/manage" class="btn btn-outline-primary btn-block text-left p-3">
                            <i class="fas fa-plus-circle text-primary mr-2"></i> <strong>Add New Service</strong>
                            <p class="small text-muted mb-0 mt-1">Publish a service with description and image</p>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <a href="<?php echo base_url ?>admin/index.php?page=clients/manage" class="btn btn-outline-success btn-block text-left p-3">
                            <i class="fas fa-plus-circle text-success mr-2"></i> <strong>Add New Client</strong>
                            <p class="small text-muted mb-0 mt-1">Showcase a partner or trusted business</p>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <a href="<?php echo base_url ?>admin/index.php?page=system_info" class="btn btn-outline-info btn-block text-left p-3">
                            <i class="fas fa-sliders-h text-info mr-2"></i> <strong>Website Settings</strong>
                            <p class="small text-muted mb-0 mt-1">Update banner, logo, and company info</p>
                        </a>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <a href="<?php echo base_url ?>admin/index.php?page=contact" class="btn btn-outline-warning btn-block text-left p-3">
                            <i class="fas fa-address-book text-warning mr-2"></i> <strong>Contact Details</strong>
                            <p class="small text-muted mb-0 mt-1">Configure address, phone, and social handles</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-server mr-2 text-info"></i>Environment Status</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-valign-middle mb-0">
                    <tbody>
                        <tr>
                            <td>PHP Version</td>
                            <td><span class="badge badge-success"><?php echo phpversion(); ?></span></td>
                        </tr>
                        <tr>
                            <td>Database Status</td>
                            <td>
                                <?php if(isset($conn) && $conn instanceof mysqli): ?>
                                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Connected</span>
                                <?php else: ?>
                                    <span class="badge badge-warning"><i class="fas fa-exclamation-circle mr-1"></i> Disconnected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Uploads Directory</td>
                            <td>
                                <?php if(is_writable(base_app.'uploads')): ?>
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Writable</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times mr-1"></i> Not Writable</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Application Base URL</td>
                            <td class="text-truncate" style="max-width: 180px;" title="<?php echo base_url ?>">
                                <code><?php echo base_url ?></code>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
