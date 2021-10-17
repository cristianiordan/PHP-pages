<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php Confirm_Login(); ?>
<?php
$SearchQueryParameter = $_GET["id"];
if (isset($_POST['Submit'])) {
    $PostTitle = $_POST['PostTitle'];
    $Category = $_POST['Category'];
    $Image = $_FILES['Image']['name'];
    $Target = "Upload/" . basename($_FILES['Image']['name']);
    $PostText = $_POST['PostDescription'];
    $Admin = "Cristi";
    date_default_timezone_set("Europe/Bucharest");
    $CurrentTime = time();
    $DateTime = strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);

    if (empty($PostTitle)) {
        $_SESSION["ErrorMessage"] = "Title can't be empty";
        Redirect_to("Posts.php");
    } elseif (strlen($PostTitle) < 5) {
        $_SESSION["ErrorMessage"] = "Title should be greater than 5 characters";
        Redirect_to("Posts.php");
    } elseif (strlen($PostText) > 9999) {
        $_SESSION["ErrorMessage"] = "Description should be less than 1000 characters";
        Redirect_to("Posts.php");
    } else {
        //Query to update post in DB when everything is fine
        global $ConnectingDB;
        if (!empty($Image)) {
            $sql = "
                UPDATE posts 
                SET 
                title = '$PostTitle', 
                category = '$Category', 
                image = '$Image', 
                post = '$PostText'
                WHERE
                id = '$SearchQueryParameter'
            ";
        } else {
            $sql = "
                UPDATE posts 
                SET 
                title = '$PostTitle', 
                category = '$Category',  
                post = '$PostText'
                WHERE
                id = '$SearchQueryParameter'
            ";
        }

        $Execute = $ConnectingDB->query($sql);
        move_uploaded_file($_FILES['Image']['tmp_name'], $Target);

        if ($Execute) {
            $_SESSION["SuccessMessage"] = "Post Updated Successfully";
            Redirect_to("Posts.php");
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong.Try Again !";
            Redirect_to("Posts.php");
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
    <title>Edit Post</title>
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
                    <h1><i class="fas fa-edit" style="color:#27aae1;"></i>Edit Post</h1>
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
                // Fetching the existing content
                global $ConnectingDB;
                $sql = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                    $TitleToBeUpdated = $DataRows["title"];
                    $CategoryToBeUpdated = $DataRows["category"];
                    $ImageToBeUpdated = $DataRows["image"];
                    $PostToBeUpdated = $DataRows["post"];
                }
                ?>
                <form action="EditPost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title"><span class="FieldInfo">Post Title:</span></label>
                                <input value="<?php echo $TitleToBeUpdated; ?>" class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here">
                            </div>
                            <div class="form-group">
                                <span class="FieldInfo">Existing Category : </span>
                                <?php echo $CategoryToBeUpdated; ?>
                                <br>
                                <label for="CategoryTitle"><span class="FieldInfo">Choose Category:</span></label>
                                <select class="form-control" id="CategoryTitle" name="Category">
                                    <?php
                                    //Fetching all the categories from category table
                                    global $ConnectingDB;
                                    $sql = "SELECT id,title FROM category";
                                    $stmt = $ConnectingDB->query($sql);
                                    while ($DataRows = $stmt->fetch()) {
                                        $Id = $DataRows['id'];
                                        $CategoryName = $DataRows['title'];
                                    ?>
                                        <option><?php echo $CategoryName; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <span class="FieldInfo">Existing Image : </span>
                                <img class="mb-2" src="Upload/<?php echo $ImageToBeUpdated; ?>" width="170px" height="70px">

                                <br>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="Image" id="imageSelect">
                                    <label for="imageSelect" class="custom-file-label">Select Image</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Post"><span class="FieldInfo">Post:</span></label>
                                <textarea class="form-control" name="PostDescription" id="Post" cols="30" rows="10">
                                    <?php echo $PostToBeUpdated; ?>
                                </textarea>
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