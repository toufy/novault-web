<?php

if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
    $data = json_decode(file_get_contents('php://input'), true);
    include '../config.php';
    if (null !== $data) {
        $uname = $data['uname'];
        $sql = "select uid from users where uname='$uname'";
        $user_id = mysqli_fetch_assoc(mysqli_query($con, $sql))['uid'];
        $service_name = $data['service_name'];
        $sql = "delete from $user_id".'_'."$uname where entry_name=?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 's', $service_name);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            mysqli_close($con);
            http_response_code(400);
            exit("couldn't delete entry");
        }
        mysqli_close($con);
        http_response_code(200);
        exit('entry deleted');
    }
}
?>

