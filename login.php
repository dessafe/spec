
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="assets/css/login.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>SpecSnap</title>
    <link rel="icon" href="assets/img/logo1.png">
  </head>
  <body>
  <?php
session_start();

if (isset($_POST['create'])) {
    header('refresh:0;URL=csign.php');
}

if (isset($_GET["register"])) {
    if ($_GET["register"] == 'success') {
        echo '<h1 class="text-success">Email Successfully verified, Registration Process Completed...</h1>';
    }
}

if (isset($_POST['login'])) {
    require "db_connection.php";

    $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    if (empty($email)) {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Email is required',
          confirmButtonColor: '#bf5b5b',
        });
        </script>";
    } else if (empty($password)) {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Password is required',
          confirmButtonColor: '#bf5b5b',
        });
        </script>";
    } else {
        $sqlCheckAdmin = "SELECT * FROM administ WHERE email = ? AND passw = ?";
        $stmtAdmin = mysqli_prepare($link, $sqlCheckAdmin);
        mysqli_stmt_bind_param($stmtAdmin, "ss", $email, $password);
        mysqli_stmt_execute($stmtAdmin);
        $resultCheckAdmin = mysqli_stmt_get_result($stmtAdmin);

        if ($resultCheckAdmin !== false && $resultCheckAdmin->num_rows > 0) {
            $row = $resultCheckAdmin->fetch_assoc();
            $_SESSION["lname"] = $row['lname'];
            $_SESSION["user"] = $row['fname'];
            $_SESSION["QR"] = $row['idno'];
            header('Location: ahomepage.php');
            exit();
        }

        $sql = "SELECT * FROM faculty WHERE email = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result !== false && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['faculty_stat'] === 'Active' && $row['passw'] === $password) {
                $_SESSION["lname"] = $row['lname'];
                $_SESSION["user"] = $row['fname'];
                $_SESSION["QR"] = $row['idno'];
                // Insert faculty login into logz table with current date, hour, and minute
                $logzSql = "INSERT INTO logz (idno, fname, lname, login) VALUES (?, ?, ?, CONCAT(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s'), ':00'))";
                $stmtLogz = mysqli_prepare($link, $logzSql);
                mysqli_stmt_bind_param($stmtLogz, "sss", $row['idno'], $row['fname'], $row['lname']);
                mysqli_stmt_execute($stmtLogz);
                header('Location: faculty.php');
                exit();
            } else if ($row['faculty_stat'] === 'Inactive') {
                echo "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Your account is inactive. Please contact administrator.',
                  confirmButtonColor: '#bf5b5b',
                });
                </script>";
            } else {
                echo "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Incorrect email or password',
                  confirmButtonColor: '#bf5b5b',
                });
                </script>";
            }
        } else {
            echo "<script>
            Swal.fire({
              icon: 'error',
              title: 'Invalid email or password',
              confirmButtonColor: '#bf5b5b',
            });
            </script>";
        }
    }
}
?>

<div class="container">
  <div class="forms-container">
    <div class="signin-signup">
      <form action="" class="sign-in-form" method="post">
        <h2 class="title">Sign in</h2>
        <div class="input-field">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Email Address" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" />
        </div>
        <div class="input-field">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Password" name="password" />
        </div>
        <button type="submit" name="login" class="btn solid"> Login</button>
      </form>
    </div>
  </div>

  <div class="panels-container">
    <div class="panel left-panel">
      <div class="content">
        <a href="index.php"><img src="assets/img/logo1.png" alt="" class="logo"></a>
        <h4>Welcome to SpecSnap!</h4>
        <p>
          Effortlessly manage hardware specifications with our intuitive system. Scan, access, and track vital information instantly with QR codes linked to our comprehensive database.
        </p>
      </div>
      <img src="assets/img/device.svg" class="image" alt="" />
    </div>
  </div>
</div>

  </body>
</html>
