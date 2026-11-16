<?php require_once('config.php'); ?>
<?php 
if(isset($_SESSION['msg_status'])){
   $msg_status = $_SESSION['msg_status'];
   unset($_SESSION['msg_status']);
}
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['full_name'])){
   if(isset($conn) && $conn instanceof mysqli){
       $data = '';
       foreach($_POST as $k => $v){
          $v = $conn->real_escape_string($v);
          if(!empty($data)) $data .= " , ";
          $data .= " `{$k}` = '{$v}' ";
       }
       $sql  = "INSERT INTO `messages` set {$data}";
       $save = $conn->query($sql);
       if($save){
          $msg_status = "success";
          foreach($_POST as $k => $v){
             unset($_POST[$k]);
          }
          $_SESSION['msg_status'] = $msg_status;
          header('location:'.($_SERVER['HTTP_REFERER'] ?? base_url . 'index.php'));
          exit;
       }else{
          $msg_status = "failed";
       }
   }
}

$contact = [
    'address' => $_settings->info('address') ?: '123 Business Avenue, Tech Park',
    'mobile' => $_settings->info('contact') ?: '+1 (555) 234-5678',
    'email' => $_settings->info('email') ?: 'contact@company.com',
    'facebook' => 'https://facebook.com',
    'twitter' => 'https://twitter.com',
    'linkin' => 'https://linkedin.com'
];
if(isset($conn) && $conn instanceof mysqli){
    $c_qry = @$conn->query("SELECT * FROM contacts");
    if($c_qry && $c_qry instanceof mysqli_result && $c_qry->num_rows > 0){
        while($row = $c_qry->fetch_assoc()){
            if(!empty($row['meta_value'])){
                $contact[$row['meta_field']] = $row['meta_value'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
  <body>

   <!-- Header Section -->
   <header id="home" style="background: #161415 url('<?php echo get_banner_url() ?>') no-repeat center center; background-size: cover;">

      <nav id="nav-wrap">

         <a class="mobile-btn" href="#nav-wrap" title="Show navigation">Show navigation</a>
         <a class="mobile-btn" href="#" title="Hide navigation">Hide navigation</a>

         <ul id="nav" class="nav">
            <li class="current"><a class="smoothscroll" href="#home">Home</a></li>
            <li><a class="smoothscroll" href="#about">About</a></li>
            <li><a class="smoothscroll" href="#resume">Services</a></li>
            <li><a class="smoothscroll" href="#clients">Clients</a></li>
            <li><a class="smoothscroll" href="#testimonials">Testimonials</a></li>
            <li><a class="smoothscroll" href="#contact_us">Contact Us</a></li>
            <li class="admin-login-link"><a href="<?php echo base_url ?>admin/login.php"><i class="fas fa-lock mr-1"></i> Admin Login</a></li>
         </ul>

      </nav>

      <div class="row banner">
         <div class="banner-text">
            <h1 class="responsive-headline"><?php echo htmlspecialchars($_settings->info('name')) ?></h1>
            <h3><?php echo stripslashes($_settings->info('welcome_message')) ?></h3>
            <hr />
            <ul class="social">
               <li><a target="_blank" href="<?php echo $contact['facebook'] ?: 'https://facebook.com' ?>"><i class="fab fa-facebook-f"></i></a></li>
               <li><a target="_blank" href="<?php echo $contact['twitter'] ?: 'https://twitter.com' ?>"><i class="fab fa-twitter"></i></a></li>
               <li><a target="_blank" href="mailto:<?php echo $contact['email'] ?: 'contact@company.com' ?>"><i class="fas fa-envelope"></i></a></li>
               <li><a target="_blank" href="<?php echo $contact['linkin'] ?: 'https://linkedin.com' ?>"><i class="fab fa-linkedin-in"></i></a></li>
            </ul>
         </div>
      </div>

      <p class="scrolldown">
         <a class="smoothscroll" href="#about"><i class="fas fa-chevron-circle-down"></i></a>
      </p>

   </header>


   <!-- About Section -->
   <section id="about" style="background:#f8fafc !important; padding: 90px 0;">

      <div class="row">

         <div class="three columns text-center">
            <img class="profile-pic" src="<?php echo get_logo_url() ?>" alt="<?php echo htmlspecialchars($_settings->info('name')) ?>" />
         </div>

         <div class="nine columns main-col">

            <h2>About Us</h2>
            <div id="about_me">
               <?php 
               if(file_exists("about.html")) {
                   include "about.html"; 
               } else {
                   echo "<p>We are a forward-thinking company dedicated to providing premium solutions, operational excellence, and transformative services to our clients worldwide.</p>";
               }
               ?>
            </div>

         </div>

      </div>

   </section>


   <!-- Services Section -->
   <section id="resume">
      <div class="row education">

         <div class="three columns header-col">
            <h1><span>Services</span></h1>
         </div>

         <div class="nine columns main-col">
          <?php 
          $services = [];
          if(isset($conn) && $conn instanceof mysqli){
              $e_qry = @$conn->query("SELECT * FROM services order by title asc");
              if($e_qry && $e_qry instanceof mysqli_result && $e_qry->num_rows > 0){
                  while($r = $e_qry->fetch_assoc()){
                      $services[] = $r;
                  }
              }
          }
          if(empty($services)){
              $services = [
                  [
                      'title' => 'Web Design & Application Development',
                      'file_path' => 'uploads/1628734760_2.jpg',
                      'description' => 'Custom high-performance web applications, responsive corporate websites, and enterprise systems built for speed and security.'
                  ],
                  [
                      'title' => 'Strategic Business & IT Consulting',
                      'file_path' => 'uploads/1628735292_download.jpg',
                      'description' => 'Comprehensive consulting solutions to optimize company operations, improve digital presence, and accelerate business growth.'
                  ]
              ];
          }
          foreach($services as $row):
          ?>
            <div class="row item mb-4">
               <div class="twelve columns">
                  <h3><?php echo htmlspecialchars($row['title'] ?? '') ?></h3>
                  <hr>
                  <img src="<?php echo validate_image($row['file_path'] ?? '') ?>" alt="" class="img-fluid service-img-view">
                  <p>
                  <?php echo stripslashes(html_entity_decode($row['description'] ?? '')) ?>
                  </p>
               </div>
            </div>
          <?php endforeach; ?>

         </div>

      </div>
   </section>

   <!-- Clients Section -->
   <section id="clients" style="padding-top:5rem; padding-bottom:5rem; background: #f1f5f9;">

      <div class="row">

         <div class="twelve columns collapsed text-center mb-4">
            <h1 style="color: #0f172a; margin-bottom: 2rem;">Businesses & Companies Who Trusted Us</h1>

            <div id="portfolio-wrapper" class="bgrid-quarters s-bgrid-thirds cf">
               <?php 
               $clients = [];
               if(isset($conn) && $conn instanceof mysqli){
                   $p_qry = @$conn->query("SELECT * FROM clients order by company_name asc");
                   if($p_qry && $p_qry instanceof mysqli_result && $p_qry->num_rows > 0){
                       while($r = $p_qry->fetch_assoc()){
                           $clients[] = $r;
                       }
                   }
               }
               if(empty($clients)){
                   $clients = [
                       [
                           'id' => 101,
                           'company_name' => 'Company 101',
                           'file_path' => 'uploads/1628739180_logo.jpg',
                           'description' => 'Long term corporate partner in business technology, logistics and enterprise data management.'
                       ],
                       [
                           'id' => 102,
                           'company_name' => 'Company 102',
                           'file_path' => 'uploads/1628739360_download.jpg',
                           'description' => 'Global digital consulting and product innovation partner trusted across multiple industries.'
                       ]
                   ];
               }
               foreach($clients as $row):
               ?>
                 <div class="columns portfolio-item">
                    <div class="item-wrap">
                       <a href="#modal-<?php echo $row['id'] ?>" title="">
                          <img alt="<?php echo htmlspecialchars($row['company_name'] ?? '') ?>" src="<?php echo validate_image($row['file_path']) ?>">
                          <div class="overlay">
                             <div class="portfolio-item-meta">
                                <h5 class="truncate-1"><?php echo htmlspecialchars($row['company_name'] ?? '') ?></h5>
                             </div>
                          </div>
                          <div class="link-icon"><i class="fas fa-eye"></i></div>
                       </a>
                    </div>
                </div>
              <?php endforeach; ?>

            </div>

         </div>

          <?php foreach($clients as $row): ?>
         <div id="modal-<?php echo $row['id'] ?>" class="popup-modal mfp-hide">
            <img class="img-fluid client-logo-modal" src="<?php echo validate_image($row['file_path']) ?>" alt="" />
            <div class="description-box">
              <h4><?php echo htmlspecialchars($row['company_name'] ?? '') ?></h4>
              <p><?php echo stripslashes(html_entity_decode($row['description'] ?? '')) ?></p>
            </div>
            <div class="link-box">
               <a class="popup-modal-dismiss btn btn-secondary">Close</a>
            </div>
         </div>
         <?php endforeach; ?>

      </div>

   </section> 

   <!-- Testimonials Section -->
   <section id="testimonials">
      <div class="filter-div"></div>
      <div class="text-container">

         <div class="row">

            <div class="two columns header-col">
               <h1><span>Client Testimonials</span></h1>
            </div>

            <div class="ten columns flex-container">
               <div class="flexslider">
                  <ul class="slides" id='testimonial-quotes'>
                     <?php 
                     $testimonials = [];
                     if(isset($conn) && $conn instanceof mysqli){
                         $t_qry = @$conn->query("SELECT * FROM testimonials order by RAND() ");
                         if($t_qry && $t_qry instanceof mysqli_result && $t_qry->num_rows > 0){
                             while($r = $t_qry->fetch_assoc()){
                                 $testimonials[] = $r;
                             }
                         }
                     }
                     if(empty($testimonials)){
                         $testimonials = [
                             [
                                 'message_from' => 'Claire Blake, VP in Accounting & Finance',
                                 'file_path' => 'uploads/1628741365_avatar.jpg',
                                 'message' => 'The team delivered on time with unmatched quality. Their expertise in management systems significantly streamlined our daily business operations.'
                             ],
                             [
                                 'message_from' => 'George Wilson, CFO of Sample Inc.',
                                 'file_path' => 'uploads/1628745074_ava.jpg',
                                 'message' => 'Outstanding service and technical execution. We have seen a 40% increase in workflow productivity since rolling out this platform.'
                             ]
                         ];
                     }
                     foreach($testimonials as $row):
                        $msg = html_entity_decode($row['message']);
                     ?>
                     <li>
                        <blockquote>
                           <p><?php echo $msg ?></p>
                           <div style="display:flex;align-items:center;">
                              <img src="<?php echo validate_image($row['file_path']) ?>" class="testimonials-avatar" alt="">
                              <cite><?php echo htmlspecialchars($row['message_from'] ?? '') ?></cite>
                           </div>
                        </blockquote>
                     </li>
                     <?php endforeach; ?>
                  </ul>
               </div>
            </div>

         </div>

       </div>

   </section> 

   <!-- Contact Section -->
   <section id="contact_us">
      <div class="d-flex">
         <form action="" id="" style="width:100%" method="POST">
         <div class="col-lg-12">
            <h4 class="text-center text-light mb-4 font-weight-bold">Get In Touch With Us</h4>
               <div class="d-flex">
                  <div class="col-lg-6">
                     <div class="form-group mb-3">
                        <label for="full_name" class="control-label text-light">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Your name" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : "" ?>">
                     </div>
                     <div class="form-group mb-3">
                        <label for="email" class="control-label text-light">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@company.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "" ?>">
                     </div>
                     <div class="form-group mb-3">
                        <label for="contact_no" class="control-label text-light">Phone Number</label>
                        <input type="text" class="form-control" id="contact_no" name="contact" placeholder="+1 (555) 000-0000" required value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : "" ?>">
                     </div>
                     <div class="form-group mb-3">
                        <label for="subject" class="control-label text-light">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Inquiry subject" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : "" ?>">
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group mb-3">
                        <label for="message" class="control-label text-light">Your Message</label>
                        <textarea name="message" id="message" cols="30" rows="11" class="form-control" placeholder="Write your message here..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : "" ?></textarea>
                     </div>
                  </div>
               </div>
               <div class='text-center my-3'>
                  <?php if(isset($msg_status) && $msg_status =='success'): ?>
                     <span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Message Successfully Sent. We will contact you shortly!</span>
                  <?php elseif(isset($msg_status) && $msg_status =='failed'): ?>
                     <span class="text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Message Sending Failed. Please try again.</span>
                  <?php endif; ?>
               </div>
               <div class="text-center my-3">
                  <button class="btn btn-primary btn-lg px-5 py-2" style="background:#00bcd4; border:none; font-weight:bold; border-radius:30px;">
                     <i class="fas fa-paper-plane mr-2"></i>Send Message
                  </button>
               </div>
               <div class="mt-5 text-center">
                  <h3 class="text-light mb-3">Our Office Information</h3>
                  <p class="address text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                     <span class="text-light"><i class="fas fa-map-marker-alt text-primary mr-2"></i> <?php echo htmlspecialchars($contact['address']) ?></span><br>
                     <span class="text-light"><i class="fas fa-phone text-primary mr-2"></i> <?php echo htmlspecialchars($contact['mobile']) ?></span><br>
                     <span class="text-light"><i class="fas fa-envelope text-primary mr-2"></i> <?php echo htmlspecialchars($contact['email']) ?></span>
                  </p>
               </div>
            </div>
         </form>
      </div>
   </section>
   <?php require_once('inc/footer.php') ?>
  </body>
</html>
