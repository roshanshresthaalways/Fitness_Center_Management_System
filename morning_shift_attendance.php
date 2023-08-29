<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "fitness_center_management_system";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['markAttendance']) && isset($_POST['selectedMembers']) && is_array($_POST['selectedMembers'])) {
        $selectedMembers = $_POST['selectedMembers'];

        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");

        foreach ($selectedMembers as $memberID) {
            $insertQuery = "INSERT INTO morning_shift (MemberID) VALUES ('$memberID')";
            $conn->query($insertQuery);
        }

        echo "Members added to morning shift successfully.";
    }

    if (isset($_POST['removeFromMorningShift']) && isset($_POST['removeMembers']) && is_array($_POST['removeMembers'])) {
        $removeMembers = $_POST['removeMembers'];

        foreach ($removeMembers as $memberID) {
            $removeQuery = "DELETE FROM morning_shift WHERE MemberID = '$memberID'";
            $conn->query($removeQuery);
        }

        echo "Members removed from morning shift successfully.";
    }
}

$sql = "SELECT member.MemberID, member.FirstName, member.LastName FROM member LEFT JOIN morning_shift ON member.MemberID = morning_shift.MemberID WHERE morning_shift.MemberID IS NULL";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Morning Shift Members</title>
    <!-- Add your stylesheet and other head elements here -->
</head>
<body>
    <h1>Morning Shift Members</h1>

    <h2>Add Members to Morning Shift</h2>
    <form action="morning_shift_attendance.php" method="post">
        <table>
            <tr>
                <th>Member ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Add to Morning Shift</th>
            </tr>

            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['MemberID']}</td>
                            <td>{$row['FirstName']}</td>
                            <td>{$row['LastName']}</td>
                            <td><input type='checkbox' name='selectedMembers[]' value='" . $row['MemberID'] . "'></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>All members are already in the morning shift.</td></tr>";
            }
            ?>

        </table>
        <input type="submit" name="markAttendance" value="Add to Morning Shift">
    </form>

    <h2>Remove Members from Morning Shift</h2>
    <form action="morning_shift_attendance.php" method="post">
        <table>
            <tr>
                <th>Member ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Remove</th>
            </tr>

            <?php
            $sql = "SELECT member.MemberID, member.FirstName, member.LastName FROM member INNER JOIN morning_shift ON member.MemberID = morning_shift.MemberID";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['MemberID']}</td>
                            <td>{$row['FirstName']}</td>
                            <td>{$row['LastName']}</td>
                            <td><input type='checkbox' name='removeMembers[]' value='" . $row['MemberID'] . "'></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No member data found.</td></tr>";
            }
            ?>

        </table>
        <input type="submit" name="removeFromMorningShift" value="Remove from Morning Shift">
    </form>

    <a href="dashboard.html">Go back to Dashboard</a>
</body>
</html>
