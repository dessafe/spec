<?php
// Include your database connection file
include('db_connection.php');

// Initialize the faculty variable
$faculty = null;
$error = ''; // To store error messages

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $faculty_id = $_GET['id'];
} else {
    die("Faculty ID is missing.");
}

// Query to fetch the faculty details from the 'faculty' table using the id
$query = "SELECT * FROM faculty WHERE idno = ?";
$stmt = $link->prepare($query); // Prepare the SQL statement
$stmt->bind_param("s", $faculty_id); // Bind the faculty ID as a string parameter
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result of the query

// Fetch the data as an associative array if available
if ($result->num_rows > 0) {
    $faculty = $result->fetch_assoc();
} else {
    $error = "Faculty not found.";
    exit;
}

$stmt->close(); // Close the prepared statement

// Process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated values from the form
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $sex = $_POST['sex'];
    $addrs = $_POST['addrs'];
    $cpno = $_POST['cpno'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $faculty_stat = $_POST['faculty_stat'];
    $email = $_POST['email'];
    $passw = $_POST['passw'];

    // SQL query to update the faculty data
    $update_query = "UPDATE faculty SET fname = ?, mname = ?, lname = ?, sex = ?, addrs = ?, cpno = ?, department = ?, position = ?, faculty_stat = ?, email = ?, passw = ? WHERE idno = ?";
    $stmt = $link->prepare($update_query);
    $stmt->bind_param("ssssssssssss", $fname, $mname, $lname, $sex, $addrs, $cpno, $department, $position, $faculty_stat, $email, $passw, $faculty_id);

    if ($stmt->execute()) {
        // Redirect to ahomepage.php on successful update
        header("Location: fview.php");
        exit();
    } else {
        // If update fails, show an error message
        $error = "Error updating faculty details. Please try again.";
    }

    $stmt->close(); // Close the statement after execution
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Faculty Details</h2>

        <!-- Show error message if exists -->
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form for editing faculty -->
        <form action="" method="POST">
            <input type="hidden" name="idno" value="<?php echo htmlspecialchars($faculty['idno']); ?>">

            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($faculty['fname']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="mname" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="mname" name="mname" value="<?php echo htmlspecialchars($faculty['mname']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($faculty['lname']); ?>" required>
            </div>

            <!-- Sex Dropdown -->
            <div class="mb-3">
                <label for="sex" class="form-label">Sex</label>
                <select class="form-control" id="sex" name="sex">
                    <option value="Male" <?php echo ($faculty['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($faculty['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="addrs" class="form-label">Address</label>
                <input type="text" class="form-control" id="addrs" name="addrs" value="<?php echo htmlspecialchars($faculty['addrs']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="cpno" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="cpno" name="cpno" value="<?php echo htmlspecialchars($faculty['cpno']); ?>" required>
            </div>

            <!-- Department Dropdown -->
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <select class="form-control" id="department" name="department">
                    <option value="Engineering Department" <?php echo ($faculty['department'] == 'Engineering Department') ? 'selected' : ''; ?>>Engineering Department</option>
                    <option value="IT Department" <?php echo ($faculty['department'] == 'IT Department') ? 'selected' : ''; ?>>IT Department</option>
                    <option value="Math Department" <?php echo ($faculty['department'] == 'Math Department') ? 'selected' : ''; ?>>Math Department</option>
                </select>
            </div>

            <!-- Position Dropdown -->
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <select class="form-control" id="position" name="position">
                    <option value="Dean" <?php echo ($faculty['position'] == 'Dean') ? 'selected' : ''; ?>>Dean</option>
                    <option value="Department Head" <?php echo ($faculty['position'] == 'Department Head') ? 'selected' : ''; ?>>Department Head</option>
                    <option value="Faculty" <?php echo ($faculty['position'] == 'Faculty') ? 'selected' : ''; ?>>Faculty</option>
                </select>
            </div>

            <!-- Faculty Status Dropdown -->
            <div class="mb-3">
                <label for="faculty_stat" class="form-label">Faculty Status</label>
                <select class="form-control" id="faculty_stat" name="faculty_stat">
                    <option value="Active" <?php echo ($faculty['faculty_stat'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="Maintenance" <?php echo ($faculty['faculty_stat'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($faculty['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="passw" class="form-label">Password</label>
                <input type="password" class="form-control" id="passw" name="passw" value="<?php echo htmlspecialchars($faculty['passw']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Faculty</button>
        </form>
    </div>

    <!-- Optional: Add Bootstrap JS and FontAwesome for icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <!-- Modal for error -->
    <?php if ($error): ?>
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $error; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Show the modal if there's an error
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        </script>
    <?php endif; ?>
</body>
</html>
