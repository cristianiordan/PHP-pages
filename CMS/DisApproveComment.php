<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
if (isset($_GET["id"])) {
    $SearchQueryParameter = $_GET["id"];
    $Admin = $_SESSION["AdminName"];
    global $ConnectingDB;
    $sql = "UPDATE comments SET STATUS='OFF', approvedby = '$Admin' WHERE id='$SearchQueryParameter'";
    $Execute = $ConnectingDB->query($sql);
    if ($Execute) {
        $_SESSION["SuccessMessage"] = "Comment Dis-Approved Succesfully !";
        Redirect_to("Comments.php");
    } else {
        $_SESSION["ErrorMessage"] = "Something went wrong! Try again!";
        Redirect_to("Comments.php");
    }
}
?>