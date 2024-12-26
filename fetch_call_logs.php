<?php
include 'includes/db.php'; // Database connection

$call_type_id = isset($_GET['call_type_id']) ? $_GET['call_type_id'] : '';

$query = "
    SELECT cl.id, st.service_type, ct.call_type, cl.date, cl.time, cl.contact_number, cl.count, cl.name, cl.age, cl.location
    FROM call_logs cl
    JOIN service_types st ON cl.service_type_id = st.id
    JOIN call_types ct ON cl.call_type_id = ct.id
";

if ($call_type_id) {
    $query .= " WHERE cl.call_type_id = '$call_type_id'";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['service_type']}</td>
                <td>{$row['call_type']}</td>
                <td>{$row['date']}</td>
                <td>{$row['time']}</td>
                <td>{$row['contact_number']}</td>
                <td>{$row['count']}</td>
                <td>{$row['name']}</td>
                <td>{$row['age']}</td>
                <td>{$row['location']}</td>
                <td>
                    <button class='btn btn-info btn-sm'>Edit</button>
                    <button class='btn btn-danger btn-sm'>Delete</button>
                </td>
              </tr>";
        $no++;
    }
} else {
    echo "<tr><td colspan='11' class='text-center'>No records found</td></tr>";
}
?>
