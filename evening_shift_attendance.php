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
        $currentTime = date("H:i:s"); // Get the current time

        foreach ($selectedMembers as $memberID) {
            $insertQuery = "INSERT INTO attendance (MemberID, attendanceDate, `TIME`) VALUES ('$memberID', '$currentDate', '$currentTime')";
            $conn->query($insertQuery);
        }

        echo "Attendance marked successfully.";
    }
}

$eveningShiftMembersSql = "SELECT member.MemberID, member.FirstName, member.LastName FROM member INNER JOIN evening_shift ON member.MemberID = evening_shift.MemberID";
$eveningShiftMembersResult = $conn->query($eveningShiftMembersSql);

if ($eveningShiftMembersResult && $eveningShiftMembersResult->num_rows > 0) {
    echo '<form action="evening_shift_attendance.php" method="post">';
    echo '<table>
            <tr>
                <th>Member ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Mark Attendance</th>
            </tr>';

    while ($row = $eveningShiftMembersResult->fetch_assoc()) {
        echo "<tr>
                <td>{$row['MemberID']}</td>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td><input type='checkbox' name='selectedMembers[]' value='" . $row['MemberID'] . "'></td>
              </tr>";
    }

    echo '</table>';
    echo '<input type="submit" name="markAttendance" value="Mark Attendance">';
    echo '</form>';
} else {
    echo "<p>No member data found.</p>";
}

$conn->close();
?>

<a href="dashboard.html">Go back to Dashboard</a>
</body>
</html>
