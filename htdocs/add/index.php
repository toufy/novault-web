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
        $service_iv = $data['service_iv'];
        $service_name = $data['service_name'];
        $service_uname = $data['service_uname'];
        $service_passwd = $data['service_passwd'];
        $sql = "insert into $user_id".'_'."$uname values(?,?,?,?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $service_iv, $service_name, $service_uname, $service_passwd);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            mysqli_close($con);
            http_response_code(400);
            exit('failed to add entry');
        }
        mysqli_close($con);
        http_response_code(200);
        exit('new entry added');
    }
}
?>

