<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $SearchQueryParameter = $_GET["id"]; ?>
<?php
if (isset($_POST['Submit'])) {
    $Name = $_POST["CommenterName"];
    $Email = $_POST["CommenterEmail"];
    $Comment = $_POST["CommenterThoughts"];
    date_default_timezone_set("Europe/Bucharest");
    $CurrentTime = time();
    $DateTime = strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);


    if (empty($Name) || empty($Email) || empty($Comment)) {
        $_SESSION["ErrorMessage"] = "All fields must be filled out";
        Redirect_to("FullPost.php?id=$SearchQueryParameter");
    } elseif (strlen($Comment) > 500) {
        $_SESSION["ErrorMessage"] = "Comment length should be less than 500 characters";
        Redirect_to("FullPost.php?id=$SearchQueryParameter");
    } else {
        //Query to insert comment in DB when everything is fine
        global $ConnectingDB;
        $sql = "INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
        $sql .= "VALUES(:dateTime,:name,:email,:comment,'Pending','OFF',:postIdFromURL)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':dateTime', $DateTime);
        $stmt->bindValue(':name', $Name);
        $stmt->bindValue(':email', $Email);
        $stmt->bindValue(':comment', $Comment);
        $stmt->bindValue(':postIdFromURL', $SearchQueryParameter);
        $Execute = $stmt->execute();

        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Comment Submitted Successfully";
            Redirect_to("FullPost.php?id=$SearchQueryParameter");
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong.Try Again !";
            Redirect_to("FullPost.php?id=$SearchQueryParameter");
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
    <title>Full Post Page</title>
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
                        <a href="Blog.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="Blog.php" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Features</a>
                    </li>

                </ul>
                <ul class="navbar-nav ml-auto">
                    <form class="form-inline d-none d-sm-block" action="Blog.php">
                        <div class="form-group">
                            <input class="form-control mr-2" type="text" name="Search" placeholder="Search here">
                            <button class="btn btn-primary" name="SearchButton">Go</button>
                        </div>
                    </form>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height: 10px; background: #27aae1;"></div>
    <!-- NAVBAR END -->

    <!-- HEADER -->
    <div class="container">
        <div class="row mt-4">
            <!-- Main Area Start -->
            <div class="col-sm-8 ">
                <h1>Responsive CMS Blog</h1>
                <h1 class="lead">Complete blog using PHP</h1>
                <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                <?php
                global $ConnectingDB;
                if (isset($_GET["SearchButton"])) {
                    $Search = $_GET["Search"];
                    $sql = "
                        SELECT * FROM posts 
                        WHERE 
                        datetime LIKE :search 
                        OR 
                        category LIKE :search 
                        OR
                        title LIKE :search 
                        OR
                        post LIKE :search
                    ";
                    $stmt = $ConnectingDB->prepare($sql);
                    $stmt->bindValue(':search', '%' . $Search . '%');

                    $stmt->execute();
                    ///The default SQL query
                } else {
                    $PostIdFromURL = $_GET["id"];
                    if (!isset($PostIdFromURL)) {
                        $_SESSION["ErrorMessage"] = "Bad Request!";
                        Redirect_to("Blog.php");
                    }
                    $sql = "SELECT * FROM posts WHERE id= '$PostIdFromURL'";
                    $stmt = $ConnectingDB->query($sql);
                    $Result = $stmt->rowCount();
                    if ($Result != 1) {
                        $_SESSION["ErrorMessage"] = "Bad Request!";
                        Redirect_to("Blog.php?page=1");
                    }
                }
                while ($DataRows = $stmt->fetch()) {
                    $PostId = $DataRows["id"];
                    $DateTime = $DataRows["datetime"];
                    $PostTitle = $DataRows["title"];
                    $Category = $DataRows["category"];
                    $Admin = $DataRows["author"];
                    $Image = $DataRows["image"];
                    $PostDescription = $DataRows["post"];

                ?>
                    <div class="card">
                        <img src="Upload/<?php echo htmlentities($Image); ?>" style="max-height: 450px;" class="img-fluid card-img-top">
                        <div class="card-body">
                            <h4 class="card-title">
                                <?php
                                echo htmlentities($PostTitle);
                                ?>
                            </h4>
                            <small class="text-muted">
                                Category:
                                <span class="text-dark"><a href="Blog.php?category=<?php echo htmlentities($Category); ?>"><?php echo htmlentities($Category); ?></a></span>
                                & Written by
                                <span class="text-dark"><a href="Profile.php?username=<?php echo htmlentities($Admin); ?>"><?php echo htmlentities($Admin); ?></a></span>
                                On
                                <span class="text-dark"><?php echo htmlentities($DateTime); ?></span>
                            </small>
                            <hr>
                            <p class="card-text">
                                <?php
                                echo htmlentities($PostDescription);
                                ?>
                            </p>

                        </div>
                    </div>
                <?php
                };
                ?>
                <!-- Comment Part Start -->
                <!-- Fetching existing comment START -->
                <span class="FieldInfo">Comments</span>
                <br><br>
                <?php
                global $ConnectingDB;
                $sql = "SELECT * FROM comments WHERE post_id='$SearchQueryParameter' AND status='ON'";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                    $CommentDate = $DataRows['datetime'];
                    $CommenterName = $DataRows['name'];
                    $CommentContent = $DataRows['comment'];

                ?>
                    <div>
                        <div class="media ">
                            <img width="30px" height="30px" class="d-block img-fluid" src="images/comment.png" alt="">
                            <div class="media-body ml-2">
                                <h6 class="lead"><?php echo $CommenterName; ?></h6>
                                <p class="small"><?php echo $CommentDate; ?></p>
                                <p><?php echo $CommentContent ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php
                };
                ?>
                <!-- Fetching existing comment END -->

                <div>
                    <form method="post" action="FullPost.php?id=<?php echo $SearchQueryParameter; ?>">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="FieldInfo">Share your thoughts about this post</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input class="form-control" type="text" name="CommenterName" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                        </div>
                                        <input class="form-control" type="email" name="CommenterEmail" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="CommenterThoughts" class="form-control" cols="80" rows="6"></textarea>
                                </div>
                                <div>
                                    <button class="btn btn-primary" name="Submit" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Comment Part End -->
            </div>
            <!-- Main Area End -->
            <?php
            require_once("Footer.php");
            ?>