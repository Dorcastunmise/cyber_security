<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cybersecure_inc";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;
if (!$client_id) { echo json_encode(['error' => 'Invalid client ID']); exit; }

// Fetch client info
$clientStmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
$clientStmt->bind_param("i", $client_id);
$clientStmt->execute();
$clientResult = $clientStmt->get_result();
$client = $clientResult->fetch_assoc();
if (!$client) { echo json_encode(['error' => 'Client not found']); exit; }

// Fetch contacts
$contactsStmt = $conn->prepare("SELECT * FROM contacts WHERE client_id = ?");
$contactsStmt->bind_param("i", $client_id);
$contactsStmt->execute();
$contactsResult = $contactsStmt->get_result();
$contacts = [];
while ($row = $contactsResult->fetch_assoc()) {
    $contacts[] = $row;
}

// Fetch projects
$projectsStmt = $conn->prepare("SELECT * FROM projects WHERE client_id = ?");
$projectsStmt->bind_param("i", $client_id);
$projectsStmt->execute();
$projectsResult = $projectsStmt->get_result();
$projects = [];
while ($row = $projectsResult->fetch_assoc()) {
    $project_id = $row['project_id'];
    
    // Fetch contracts for this project
    $contractsStmt = $conn->prepare("SELECT * FROM contracts WHERE project_id = ?");
    $contractsStmt->bind_param("i", $project_id);
    $contractsStmt->execute();
    $contractsResult = $contractsStmt->get_result();
    $contracts = [];
    while ($contract = $contractsResult->fetch_assoc()) {
        $contract_id = $contract['contract_id'];

        // Fetch invoices for this contract
        $invoicesStmt = $conn->prepare("SELECT * FROM invoices WHERE contract_id = ?");
        $invoicesStmt->bind_param("i", $contract_id);
        $invoicesStmt->execute();
        $invoicesResult = $invoicesStmt->get_result();
        $invoices = [];
        while ($invoice = $invoicesResult->fetch_assoc()) {
            $invoices[] = $invoice;
        }
        $contract['invoices'] = $invoices;
        $contracts[] = $contract;
    }
    $row['contracts'] = $contracts;
    $projects[] = $row;
}

$response = [
    'client' => $client,
    'contacts' => $contacts,
    'projects' => $projects
];

echo json_encode($response);

$conn->close();
