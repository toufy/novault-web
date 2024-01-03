<?php

if (isset($_SERVER['REQUEST_METHOD']) && 'GET' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_GET['data'])) {
        include '../config.php';
        $encoded_data = $_GET['data'];
        $json_data = json_decode(urldecode($encoded_data), true);
        $uname = $json_data['uname'];
        $sql = "select * from users where uname='$uname'";
        $results = mysqli_query($con, $sql);
        $uid = mysqli_fetch_assoc($results)['uid'];
        $sql = "select * from $uid".'_'."$uname";
        $results = mysqli_query($con, $sql);
        if (!$results) {
            mysqli_close($con);
            http_response_code(400);
            exit('no passwords saved in your vault. create some.');
        }
        $data_arr = [];
        while ($row = mysqli_fetch_assoc($results)) {
            $data_arr[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($data_arr);
        mysqli_close($con);
        http_response_code(200);
        exit;
    }
}
http_response_code(400);
exit('no data provided');
?>

