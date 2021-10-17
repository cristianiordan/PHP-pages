<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/styles.css">
    <title>Blog Page</title>
    <style>
        .heading {
            font-family: Bitter, Georgia, "Times New Roman", Times, serif;
            font-weight: bold;
            color: #005e90;
        }

        .heading:hover {
            color: #0090db;
        }
    </style>
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
                ///SQL query when search button is active
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
                    ///Query when pagination is active
                } elseif (isset($_GET["page"])) {
                    $Page = $_GET["page"];
                    if ($Page <= 0) {
                        $ShowPostFrom = 0;
                    } else {
                        $ShowPostFrom = ($Page * 5) - 5;
                    }
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT $ShowPostFrom,5";
                    $stmt = $ConnectingDB->query($sql);
                    ///Query when category is active
                } elseif (isset($_GET["category"])) {
                    $Category = $_GET["category"];
                    $sql = "SELECT * FROM posts WHERE category = :categoryName ORDER BY id desc";
                    $stmt = $ConnectingDB->prepare($sql);
                    $stmt->bindValue(':categoryName', $Category);
                    $stmt->execute();
                } else {
                    ///The default SQL query
                    $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,3";
                    $stmt = $ConnectingDB->query($sql);
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
                            <span class="badge badge-dark text-light" style="float: right;">Comments <?php echo ApproveCommentsAccordingToPosts($PostId); ?></span>
                            <hr>
                            <p class="card-text">
                                <?php
                                if (strlen($PostDescription) > 150) {
                                    $PostDescription = substr($PostDescription, 0, 150) . "...";
                                }
                                echo htmlentities($PostDescription);
                                ?>
                            </p>
                            <a href="FullPost.php?id=<?php echo $PostId; ?>" style="float: right;">
                                <span class="btn btn-info">Read More >> </span>
                            </a>
                        </div>
                    </div>
                    <br>
                <?php
                };
                ?>
                <!-- Pagination -->
                <nav>
                    <ul class="pagination pagination-lg">
                        <!-- Creating Backward Button -->
                        <?php
                        if (isset($Page)) {
                            if ($Page > 1) {
                        ?>
                                <li class="page-item">
                                    <a href="Blog.php?page=<?php echo $Page - 1; ?>" class="page-link">&laquo;</a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        global $ConnectingDB;
                        $sql = "SELECT COUNT(*) FROM posts";
                        $stmt = $ConnectingDB->query($sql);
                        $RowPagination = $stmt->fetch();
                        $TotalPosts = array_shift($RowPagination);
                        $PostPagination = $TotalPosts / 5;
                        $PostPagination = ceil($PostPagination);
                        for ($i = 1; $i <= $PostPagination; $i++) {
                            if (isset($Page)) {
                                if ($i == $Page) {
                        ?>
                                    <li class="page-item active">
                                        <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                    </li>
                                <?php
                                } else {
                                ?>
                                    <li class="page-item">
                                        <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                                    </li>
                        <?php
                                }
                            }
                        }
                        ?>
                        <!-- Creating Forward Button -->
                        <?php
                        if (isset($Page) || !empty($Page)) {
                            if ($Page + 1 <= $PostPagination) {
                        ?>
                                <li class="page-item">
                                    <a href="Blog.php?page=<?php echo $Page + 1; ?>" class="page-link">&raquo;</a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </nav>

            </div>
            <!-- Main Area End -->
            <?php
            require_once("Footer.php");
            ?>