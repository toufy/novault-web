<?php
if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
    $data = json_decode(file_get_contents('php://input'), true);
    include '../config.php';
    if (null !== $data) {
        $uname = $data['uname'];
        $upasswd = $data['upasswd'];
        $sql = 'select * from users where uname=?';
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 's', $uname);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);
        if (0 === mysqli_num_rows($results)) {
            mysqli_close($con);
            http_response_code(400);
            exit("user doesn't exist");
        }
        $user_info = mysqli_fetch_assoc($results);
        $user_password = $user_info['upasswd'];
        if (!password_verify($upasswd, $user_password)) {
            mysqli_close($con);
            http_response_code(400);
            exit('invalid password');
        }
        http_response_code(200);
        mysqli_close($con);
    }
}
?>

