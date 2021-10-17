<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login(); ?>
<?php
// Fetching the existing admin data
$AdminId = $_SESSION["UserId"];
global $ConnectingDB;
$sql = "SELECT * FROM admins WHERE id = '$AdminId'";
$stmt = $ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()) {
    $ExistingName = $DataRows["aname"];
    $ExistingUsername = $DataRows["username"];
    $ExistingHeadline = $DataRows["aheadline"];
    $ExistingBio = $DataRows["abio"];
    $ExistingImage = $DataRows["aimage"];
}
// Fetching the existing admin data end

if (isset($_POST['Submit'])) {
    $AName = $_POST['Name'];
    $AHeadline = $_POST['Headline'];
    $ABio = $_POST['Bio'];
    $Image = $_FILES['Image']['name'];
    $Target = "Images/" . basename($_FILES['Image']['name']);

    if (strlen($AHeadline) > 30) {
        $_SESSION["ErrorMessage"] = "Headline should be less than 30 characters";
        Redirect_to("MyProfile.php");
    } elseif (strlen($ABio) > 500) {
        $_SESSION["ErrorMessage"] = "Bio should be less than 500 characters";
        Redirect_to("MyProfile.php");
    } else {
        //Query to update Admin data in DB when everything is fine
        global $ConnectingDB;
        if (!empty($Image)) {
            $sql = "
                UPDATE admins 
                SET 
                aname = '$AName', 
                aheadline = '$AHeadline', 
                abio = '$ABio',
                aimage = '$Image' 
                WHERE
                id = '$AdminId'
            ";
        } else {
            $sql = "
                UPDATE admins 
                SET 
                aname = '$AName', 
                aheadline = '$AHeadline', 
                abio = '$ABio' 
                WHERE
                id = '$AdminId'
            ";
        }

        $Execute = $ConnectingDB->query($sql);
        move_uploaded_file($_FILES['Image']['tmp_name'], $Target);

        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Details updated Successfully";
            Redirect_to("MyProfile.php");
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong.Try Again !";
            Redirect_to("MyProfile.php");
        }
    }
}

//Ending of Submit Button If Condition
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/styles.css">
    <title>My Profile</title>
</head>

<body>
    <!-- NAVBAR -->
    <div style="height: 10px; background: #27aae1;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="#" class="navbar-brand">example.com</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarcollapseCMS" aria-controls="navbarcollapseCMS" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarcollapseCMS">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="MyProfile.php" class="nav-link"><i class="text-success fas fa-user"></i> My profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="Dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="Posts.php" class="nav-link">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a href="Categories.php" class="nav-link">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a href="Admins.php" class="nav-link">Manage Admins</a>
                    </li>
                    <li class="nav-item">
                        <a href="Comments.php" class="nav-link">Comments</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="navbar-nav">
                        <a href="Logout.php" class="nav-link text-danger">
                            <i class="fas fa-user-times"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height: 10px; background: #27aae1;"></div>
    <!-- NAVBAR END -->

    <!-- HEADER -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fas fa-user text-success mr-2"></i>@<?php echo $ExistingUsername; ?></h1>
                    <small><?php echo $ExistingHeadline; ?></small>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER END -->

    <!-- Main Area -->
    <section class="container py-2 mb-4">
        <div class="row">
            <!-- Left Area -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h3><?php echo $ExistingName ?></h3>
                    </div>
                    <div class="card-body">
                        <img class="block img-fluid mb-3" src="Images/<?php echo $ExistingImage; ?>" alt="">
                        <div>
                            <?php echo $ExistingBio; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Area -->
            <div class="col-md-9" style="min-height: 400px;">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <form action="MyProfile.php" method="post" enctype="multipart/form-data">
                    <div class="card bg-dark text-light">
                        <div class="card-header bg-secondary text-light">
                            <h4>Edit Profie</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" type="text" name="Name" placeholder="Your name">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Headline" name="Headline">
                                <small class="text-muted">Add a professional headline</small>
                                <span class="text-danger">Not more than 30 characters</span>
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Bio" class="form-control" name="Bio" cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="Image" id="imageSelect">
                                    <label for="imageSelect" class="custom-file-label">Select Image</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <a class="btn btn-warning btn-block" href="Dashboard.php"><i class="fas fa-arrow-left"></i>Back to Dashboard</a>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <button class="btn btn-block btn-success" type="submit" name="Submit">
                                        <i class="fas fa-check"></i> Publish
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- End Main Area -->

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