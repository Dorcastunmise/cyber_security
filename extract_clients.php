<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "cybersecure_inc";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$name = isset($_POST['name']) ? $_POST['name'] : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 5; 
$offset = ($page - 1) * $limit;

$sql = "SELECT client_id, client_name, industry, country, registration_date  
        FROM clients 
        WHERE client_name LIKE ? OR client_id LIKE ? 
        ORDER BY client_id ASC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$search = "%$name%";
$stmt->bind_param("ssii", $search, $search, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '
            <tr data-id="' . htmlspecialchars($row['client_id']) . '">
                <td>' . htmlspecialchars($row['client_id']) . '</td>
                <td>' . htmlspecialchars($row['client_name']) . '</td>
                <td>' . htmlspecialchars($row['industry']) . '</td>
                <td>' . htmlspecialchars($row['country']) . '</td>
                <td>' . htmlspecialchars($row['registration_date']) . '</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary btn-view">View</button>
                    <button class="btn btn-sm btn-outline-danger btn-delete">Delete</button>
                </td>
            </tr>
        ';
    }
} else {
    $output = '<tr><td colspan="6" class="text-center text-muted">No client records found.</td></tr>';
}

$totalResult = $conn->prepare("SELECT COUNT(*) as total FROM clients WHERE client_name LIKE ? OR client_id LIKE ?");
$totalResult->bind_param("ss", $search, $search);
$totalResult->execute();
$totalResult->bind_result($totalRecords);
$totalResult->fetch();
$totalPages = ceil($totalRecords / $limit);

$pagination = '<tr><td colspan="6" class="text-center">';
for ($i = 1; $i <= $totalPages; $i++) {
    $pagination .= '<button class="btn btn-sm btn-outline-info mx-1 page-btn" data-page="'.$i.'">'.$i.'</button>';
}
$pagination .= '</td></tr>';

echo $output . $pagination;

$conn->close();
?>
