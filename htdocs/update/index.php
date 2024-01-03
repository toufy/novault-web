<?php

if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
    $data = json_decode(file_get_contents('php://input'), true);
    include '../config.php';
    if (null !== $data) {
        $uname = $data['uname'];
        $sql = "select uid from users where uname='$uname'";
        $results = mysqli_query($con, $sql);
        $user_info = mysqli_fetch_assoc($results);
        $user_id = $user_info['uid'];
        $service_name = $data['service_name'];
        $service_uname = $data['service_uname'];
        $service_passwd = $data['service_passwd'];
        $sql = "update $user_id".'_'."$uname set entry_username=?,entry_password=? "
            .'where entry_name=?';
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $service_uname, $service_passwd, $service_name);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            mysqli_close($con);
            http_response_code(400);
            exit('failed to update entry');
        }
        mysqli_close($con);
        http_response_code(200);
        exit('entry updated');
    }
}
?>

