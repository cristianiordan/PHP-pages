<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
Confirm_Login(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/styles.css">
    <title>Comments</title>
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
                    <h1><i class="fas fa-comments" style="color:#27aae1;"></i> Manage Comments</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER END -->
    <!-- Main Area Start -->
    <section class="container py-2 mb-4">
        <div class="row" style="min-height: 30px;">
            <div class="col-lg-12" style="min-height: 400px;">
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <h2>Un-Approved Comments</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date&Time</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Approve</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments WHERE status='OFF' ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CommentId = $DataRows["id"];
                        $DateTimeOfComment = $DataRows["datetime"];
                        $CommenterName = $DataRows["name"];
                        $CommentContent = $DataRows["comment"];
                        $CommentPostId = $DataRows["post_id"];
                        $SrNo++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                                <td><?php echo htmlentities($CommenterName); ?></td>
                                <td><?php echo htmlentities($CommentContent); ?></td>
                                <td><a class="btn btn-success" href="ApproveComment.php?id=<?php echo $CommentId ?>">Approve</a></td>
                                <td><a class="btn btn-danger" href="DeleteComment.php?id=<?php echo $CommentId ?>">Delete</a></td>
                                <td style="min-width: 140px;"><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId ?>" target="_blank">Live Preview</a></td>
                            </tr>
                        <?php
                    }
                        ?>
                        </tbody>
                </table>
                <h2>Approved Comments</h2>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Date&Time</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Dis-Approve</th>
                            <th>Delete</th>
                            <th>Details</th>
                        </tr>
                    </thead>

                    <?php
                    global $ConnectingDB;
                    $sql = "SELECT * FROM comments WHERE status='ON' ORDER BY id desc";
                    $Execute = $ConnectingDB->query($sql);
                    $SrNo = 0;
                    while ($DataRows = $Execute->fetch()) {
                        $CommentId = $DataRows["id"];
                        $DateTimeOfComment = $DataRows["datetime"];
                        $CommenterName = $DataRows["name"];
                        $CommentContent = $DataRows["comment"];
                        $CommentPostId = $DataRows["post_id"];
                        $SrNo++;
                    ?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlentities($SrNo); ?></td>
                                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                                <td><?php echo htmlentities($CommenterName); ?></td>
                                <td><?php echo htmlentities($CommentContent); ?></td>
                                <td style="min-width: 140px;"><a class="btn btn-warning" href="DisApproveComment.php?id=<?php echo $CommentId ?>">Dis-Approve</a></td>
                                <td><a class="btn btn-danger" href="DeleteComment.php?id=<?php echo $CommentId ?>">Delete</a></td>
                                <td style="min-width: 140px;"><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId ?>" target="_blank">Live Preview</a></td>
                            </tr>
                        <?php
                    }
                        ?>
                        </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- Main Area End -->
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