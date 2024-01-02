<?php

if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
    include '../config.php';
    $uname = $_POST['uname'];
    $upasswd = $_POST['upasswd'];
    $sql = 'select * from users where uname=?';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $uname);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($results) > 0) {
        $_SESSION['signup_err'] = 'username exists';
    }
    if (!isset($_SESSION['signup_err'])) {
        $hashed_pass = password_hash($upasswd, PASSWORD_BCRYPT);
        $sql = 'insert into users values (NULL,?,?)';
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $uname, $hashed_pass);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);
        if ($results) {
            $sql = "select uid from users where uname='$uname'";
            $uid = mysqli_fetch_assoc(mysqli_query($con, $sql))['uid'];
            $sql = "create table `$uid".'_'."$uname` ("
                .'`entry_iv` varchar(255),'
                .'`entry_name` varchar(255) not null,'
                .'`entry_username` varchar(255),'
                .'`entry_password` varchar(255)'
                .')';
            $results = mysqli_query($con, $sql);
            if ($results) {
                echo "log in with the username '$uname' and your password through the novault app";
                exit;
            } else {
                $_SESSION['signup_err'] = "couldn't create user table";
                $sql = "delete from users where uname='$uname'";
                mysqli_query($con, $sql);
            }
        } else {
            $_SESSION['signup_err'] = "couldn't create user $uname";
        }
    }
    mysqli_close($con);
}
echo <<<HTML
<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1.0"
		/>
		<title>novault - signup</title>
		<link
			rel="stylesheet"
			href="../css/style.css"
		/>
	</head>
	<body>
HTML;
if (isset($_SESSION['signup_err'])) {
    $err = $_SESSION['signup_err'];
    echo "<div style='background-color: #b71c1c;color:#ffffff;padding: 0.5rem;'>signup error: $err</div>";
}
echo <<<HTML
		<div class="body-wrapper">
			<form
				method="post"
				class="signup-form"
			>
				<div class="header-wrapper">
					<img
						src="../img/logo.svg"
						alt=""
					/>
					<h1>novault</h1>
				</div>
				<table class="signup-table">
					<tr>
						<td>
							<label for="uname">username</label>
						</td>
					</tr>
					<tr>
						<td>
							<input
								type="text"
								name="uname"
							/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="upasswd">password</label>
						</td>
					</tr>
					<tr>
						<td>
							<input
								type="password"
								name="upasswd"
							/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="conf_upasswd">confirm password</label>
						</td>
					</tr>
					<tr>
						<td>
							<input
								type="password"
								name="conf_upasswd"
							/>
						</td>
					</tr>
				</table>
				<input
					type="submit"
					value="sign up"
				/>
			</form>
		</div>
	</body>
	<script src="../scripts/script.js"></script>
</html>
HTML;
