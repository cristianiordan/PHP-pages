 <!-- Side Area Start -->

 <div class="col-sm-4">
     <div class="card mt-4">
         <div class="card-body">
             <img src="images/starter.png" class="d-block img-fluid mb-3" alt="">
             <div class="text-center">
                 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
             </div>
         </div>
     </div>
     <br>
     <div class="card">
         <div class="card-header bg-dark text-light">
             <h2 class="lead">Sign Up!</h2>
         </div>
         <div class="card-body">
             <button type="button" name="button" class="mb-4 btn btn-success btn-block text-center text-white">
                 Join The Forum
             </button>
             <button type="button" name="button" class="mb-4 btn btn-danger btn-block text-center text-white">
                 Login
             </button>
             <div class="input-group mb-3">
                 <input type="text" class="form-control" name="" placeholder="Enter your email" value="">
                 <div class="input-group-append">
                     <button type="button" name="button" class="btn btn-primary btn-sm text-center text-white">
                         Subscribe Now
                     </button>
                 </div>
             </div>
         </div>
     </div>
     <br>
     <div class="card">
         <div class="card-header bg-primary text-light">
             <h2 class="lead">Categories</h2>
         </div>
         <div class="card-body">
             <?php
                global $ConnectingDB;
                $sql = "SELECT * FROM category ORDER BY id desc";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                    $CategoryId = $DataRows["id"];
                    $CategoryName = $DataRows["title"];
                ?>
                 <a href="Blog.php?category=<?php echo $CategoryName ?>">
                     <span class="heading"><?php echo $CategoryName; ?></span>
                 </a>
                 <br>
             <?php
                }
                ?>
         </div>
     </div>
     <br>
     <div class="card">
         <div class="card-header bg-info text-white">
             <h2 class="lead">Recent Posts</h2>
         </div>
         <div class="card-body">
             <?php
                global $ConnectingDB;
                $sql = "Select * FROM posts ORDER BY id desc LIMIT 0,5";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                    $Id = $DataRows["id"];
                    $Title =  $DataRows["title"];
                    $DateTime = $DataRows["datetime"];
                    $Image = $DataRows["image"];

                ?>
                 <div class="media">
                     <img src="Upload/<?php echo htmlentities($Image); ?>" width="90" height="100" class="d-block img-fluid align-self-start" alt="">
                     <div class="media-body ml-2">
                         <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
                             <h6 class="lead"><?php echo htmlentities($Title); ?></h6>
                         </a>
                         <p class="small"><?php echo htmlentities($DateTime); ?></p>
                     </div>
                 </div>
                 <hr>
             <?php
                }
                ?>
         </div>
     </div>
 </div>
 <!-- Side Area End -->
 </div>
 </div>
 <!-- HEADER END -->
 <br>
 <!-- FOOTER -->
 <footer class="bg-dark text-white">
     <div class="container">
         <div class="row">
             <div class="col">
                 <p class="lead text-center">Theme By | Iordan Cristian | <span id="year"></span> &copy; ----All rights reserved</p>
             </div>
         </div>
     </div>
 </footer>
 <div style="height: 10px; background: #27aae1;"></div>

 <!-- FOOTER END -->
 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 <script>
     $('#year').text(new Date().getFullYear());
 </script>
 </body>

 </html>