<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cybersecure_inc";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
if (!$client_id) { echo json_encode(['error' => 'Invalid client ID']); exit; }

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("DELETE invoices FROM invoices 
        JOIN contracts ON invoices.contract_id = contracts.contract_id 
        JOIN projects ON contracts.project_id = projects.project_id 
        WHERE projects.client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE contracts FROM contracts 
        JOIN projects ON contracts.project_id = projects.project_id 
        WHERE projects.client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM projects WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM contacts WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM clients WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true]);

} catch(Exception $e) {
    $conn->rollback();
    echo json_encode(['error' => 'Failed to delete client: ' . $e->getMessage()]);
}

$conn->close();
?>
