<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login(); ?>
<?php
if (isset($_POST['Submit'])) {
    $Username = $_POST['Username'];
    $Name = $_POST['Name'];
    $Password = $_POST['Password'];
    $ConfirmPassword = $_POST['ConfirmPassword'];
    $Admin = $_SESSION["UserName"];
    date_default_timezone_set("Europe/Bucharest");
    $CurrentTime = time();
    $DateTime = strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);

    if (empty($Username) || empty($Password) || empty($ConfirmPassword)) {
        $_SESSION["ErrorMessage"] = "All fields must be filled out";
        Redirect_to("Admins.php");
    } elseif (strlen($Password) < 4) {
        $_SESSION["ErrorMessage"] = "Password should be greater than 3 characters";
        Redirect_to("Admins.php");
    } elseif ($Password !== $ConfirmPassword) {
        $_SESSION["ErrorMessage"] = "Password and Confirm Password should match";
        Redirect_to("Admins.php");
    } elseif (CheckUserNameExistsOrNot($Username)) {
        $_SESSION["ErrorMessage"] = "Username Exists. Try another one";
        Redirect_to("Admins.php");
    } else {
        //Query to insert new admin in DB when everything is fine
        global $ConnectingDB;
        $sql = "INSERT INTO admins(datetime,username,password,aname,addedby)";
        $sql .= "VALUES(:dateTime,:userName,:password,:aName,:adminName)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':userName', $Username);
        $stmt->bindValue(':password', $Password);
        $stmt->bindValue(':aName', $Name);
        $stmt->bindValue(':adminName', $Admin);
        $Execute = $stmt->execute();

        if ($Execute) {
            $_SESSION["SuccessMessage"] = "New Admin with the name of " . $Name . " added Successfully";
            Redirect_to("Admins.php");
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong.Try Again !";
            Redirect_to("Admins.php");
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
    <title>Admin Page</title>
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
                    <h1><i class="fas fa-user" style="color:#27aae1;"></i> Manage Admins</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER END -->

    <!-- Main Area -->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10" style="min-height: 400px;">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <form action="Admins.php" method="post">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h1>Add new Admin</h1>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="Username"><span class="FieldInfo">Username:</span></label>
                                <input class="form-control" type="text" name="Username" id="Username">
                            </div>
                            <div class="form-group">
                                <label for="Name"><span class="FieldInfo">Name:</span></label>
                                <input class="form-control" type="text" name="Name" id="Name">
                                <small class="text-muted">Optional</small>
                            </div>
                            <div class="form-group">
                                <label for="Password"><span class="FieldInfo">Password:</span></label>
                                <input class="form-control" type="password" name="Password" id="Password">
                            </div>
                            <div class="form-group">
                                <label for="ConfirmPassword"><span class="FieldInfo">Confirm Password:</span></label>
                                <input class="form-control" type="password" name="ConfirmPassword" id="ConfirmPassword">
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
                <h2>Existing Admins</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date&Time</th>
                            <th>Username</th>
                            <th>Admin Name</th>
                            <th>Added by</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM admins ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $AdminId = $DataRows["id"];
                        $DateTime = $DataRows["datetime"];
                        $AdminUsername = $DataRows["username"];
                        $AdminName = $DataRows["aname"];
                        $AddedBy = $DataRows["addedby"];
                        $SrNo++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($DateTime); ?></td>
                                <td><?php echo htmlentities($AdminUsername); ?></td>
                                <td><?php echo htmlentities($AdminName); ?></td>
                                <td><?php echo htmlentities($AddedBy); ?></td>
                                <td><a class="btn btn-danger" href="DeleteAdmin.php?id=<?php echo $AdminId ?>">Delete</a></td>
                            </tr>
                        <?php
                    }
                        ?>
                        </tbody>
                </table>
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