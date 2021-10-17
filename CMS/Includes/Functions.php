<?php require_once("Includes/DB.php"); ?>
<?php
function Redirect_to($New_Location)
{
    header("Location:" . $New_Location);
    exit;
}

function CheckUserNameExistsOrNot($UserName)
{
    global $ConnectingDB;
    $sql = "SELECT username FROM admins WHERE username=:userName";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':userName', $UserName);
    $stmt->execute();
    $Result = $stmt->rowCount();
    if ($Result == 1) {
        return true;
    } else {
        return false;
    }
}

function Login_Attempt($Username, $Password)
{
    global $ConnectingDB;
    $sql = "SELECT * FROM admins WHERE username = :userName AND password = :passWord LIMIT 1";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':userName', $Username);
    $stmt->bindValue(':passWord', $Password);
    $stmt->execute();
    $Result = $stmt->rowCount();
    if ($Result = 1) {
        return $Found_Account = $stmt->fetch();
    } else {
        return null;
    }
}

function Confirm_Login()
{
    if (isset($_SESSION["UserId"])) {
        return true;
    } else {
        $_SESSION["ErrorMessage"] = "Login Required !";
        Redirect_to("Login.php");
    }
}

function TotalComments()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM comments";
    $stmt = $ConnectingDB->query($sql);
    $TotalRows = $stmt->fetch();
    $TotalComments = array_shift($TotalRows);
    echo $TotalComments;
}

function TotalAdmins()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM admins";
    $stmt = $ConnectingDB->query($sql);
    $TotalRows = $stmt->fetch();
    $TotalAdmins = array_shift($TotalRows);
    echo $TotalAdmins;
}

function TotalCategories()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM category";
    $stmt = $ConnectingDB->query($sql);
    $TotalRows = $stmt->fetch();
    $TotalCategories = array_shift($TotalRows);
    echo $TotalCategories;
}

function TotalPosts()
{
    global $ConnectingDB;
    $sql = "SELECT COUNT(*) FROM posts";
    $stmt = $ConnectingDB->query($sql);
    $TotalRows = $stmt->fetch();
    $TotalPosts = array_shift($TotalRows);
    echo $TotalPosts;
}

function ApproveCommentsAccordingToPosts($PostId)
{
    global $ConnectingDB;
    $sqlApprove = "SELECT COUNT(*) FROM comments WHERE post_id = '$PostId' AND status = 'ON'";
    $stmtApprove = $ConnectingDB->query($sqlApprove);
    $RowsTotalApprove = $stmtApprove->fetch();
    $TotalApprove = array_shift($RowsTotalApprove);
    return $TotalApprove;
}

function UnApproveCommentsAccordingToPosts($PostId)
{
    global $ConnectingDB;
    $sqlUnApprove = "SELECT COUNT(*) FROM comments WHERE post_id = '$PostId' AND status = 'OFF'";
    $stmtUnApprove = $ConnectingDB->query($sqlUnApprove);
    $RowsTotalUnApprove = $stmtUnApprove->fetch();
    $TotalUnApprove = array_shift($RowsTotalUnApprove);
    return $TotalUnApprove;
}
