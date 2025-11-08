<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CyberSecure Inc. Client Dashboard</title>

  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="cyber.css">

</head>
<body>

    <input type="hidden" name="client_id" value="<?php echo $_POST['client_id'] ?? ''; ?>">

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-lock-fill me-2"></i> CYBERSECURE INC.
            </a>
        </div>
    </nav>


    <div class="container my-5">
        <div class="row mb-4">
        <div class="col text-center">
            <h2 class="fw-bold">Client Intelligence Portal</h2>
            <p class="text-muted">Monitoring and managing secured client operations</p>
        </div>
        </div>

        <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="input-group">
            <input type="text" class="form-control" id="searchClient" placeholder="Search client by name or ID...">
            <button class="btn btn-primary" onclick="clientRecords()">Search</button>
            </div>
        </div>
        </div>

        <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Active Client Records</h5>
            <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th scope="col">Client ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Industry</th>
                    <th scope="col">Country</th>
                    <th scope="col">Registration Date</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <div id="loader-overlay">
                    <div class="loader"></div>
                    <p class="loader-text">Processing records...</p>
                </div>

                <tbody id="clientTable">
                <!-- AJAX data -->
                </tbody>
            </table>
            </div>
        </div>
        </div>

        <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content bg-light text-dark">
                <div class="modal-header border-bottom border-primary">
                    <h5 class="modal-title" id="clientModalLabel">Client Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                    <!-- Populated by JS -->
                    </div>
                </div>
                <div class="modal-footer border-top border-primary">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: var(--card-bg); color: var(--text-color); border: 1px solid var(--accent-color);">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this client and all its associated records?</p>
                    <div id="modal-loader" style="display: none; text-align: center; margin-top: 20px;">
                    <div class="loader"></div>
                    <p class="loader-text">Deleting records...</p>
                    </div>
                </div>
                
                <div class="modal-footer" id="modal-footer-buttons">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
                
                </div>
            </div>
        </div>

    </div>

    <div class="footer">
        © 2025 CyberSecure Inc. | Interface by Tunmise
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function clientRecords(page = 1) {
            const name = $('#searchClient').val();

            $.ajax({
                method: "POST",
                url: "extract_clients.php",
                data: { name: name, page: page },
                beforeSend: function() {
                    $("#loader-overlay").fadeIn(200);
                },
                success: function(response) {
                    $("#clientTable").html(response);
                },
                complete: function() {
                    $("#loader-overlay").fadeOut(300);
                }
            });
        }

        $(document).on('click', '.btn-view', function() {
            let clientId = $(this).closest('tr').data('id');

            $.ajax({
                method: "POST",
                url: "view_client.php",
                data: { client_id: clientId },
                dataType: "json",
                beforeSend: function() {
                    $("#loader-overlay").fadeIn(200);
                },
                success: function(res) {
                    if(res.error) {
                        alert(res.error);
                        return;
                    }

                    // Start building modal HTML
                    let html = `<h5>Client Info</h5>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item"><strong>ID:</strong> ${res.client.client_id}</li>
                                    <li class="list-group-item"><strong>Name:</strong> ${res.client.client_name}</li>
                                    <li class="list-group-item"><strong>Industry:</strong> ${res.client.industry}</li>
                                    <li class="list-group-item"><strong>Country:</strong> ${res.client.country}</li>
                                    <li class="list-group-item"><strong>City:</strong> ${res.client.city}</li>
                                    <li class="list-group-item"><strong>Address:</strong> ${res.client.address}</li>
                                    <li class="list-group-item"><strong>Registered:</strong> ${res.client.registration_date}</li>
                                </ul>`;

                    // Contacts
                    html += `<h5>Contacts</h5>`;
                    if(res.contacts.length > 0){
                        html += '<ul class="list-group mb-3">';
                        res.contacts.forEach(c => {
                            html += `<li class="list-group-item">${c.contact_name} - ${c.email} - ${c.phone} - ${c.position}</li>`;
                        });
                        html += '</ul>';
                    } else {
                        html += '<p class="text-muted mb-3">No contacts found.</p>';
                    }

                    // Projects & Contracts
                    html += `<h5>Projects & Contracts</h5>`;
                    if(res.projects.length > 0){
                        html += `<div class="accordion" id="accordionProjects">`;

                        res.projects.forEach((p, index) => {
                            html += `<div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="heading${index}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#project${index}" aria-expanded="false" aria-controls="project${index}">
                                                <strong>Project:</strong> ${p.project_name} (${p.status})
                                            </button>
                                        </h2>
                                        <div id="project${index}" class="accordion-collapse collapse" aria-labelledby="heading${index}" data-bs-parent="#accordionProjects">
                                            <div class="accordion-body">
                                                <p><strong>Start:</strong> ${p.start_date} | <strong>End:</strong> ${p.end_date || 'Ongoing'}</p>
                                                <p><strong>Description:</strong> ${p.description}</p>`;

                            if(p.contracts.length > 0){
                                p.contracts.forEach((c, cIndex) => {
                                    html += `<div class="card mb-2 p-2 border rounded bg-light text-dark">
                                                <strong>Contract ID:</strong> ${c.contract_id} | 
                                                <strong>Signed:</strong> ${c.signed_date} | 
                                                <strong>Amount:</strong> ${c.amount} ${c.currency}<br>
                                                <strong>Terms:</strong> ${c.terms}`;

                                    if(c.invoices.length > 0){
                                        html += `<ul class="list-group list-group-flush mt-2">`;
                                        c.invoices.forEach(i => {
                                            html += `<li class="list-group-item">Invoice ${i.invoice_id}: ${i.issue_date} | Due: ${i.due_date} | Amount: ${i.amount} | Status: ${i.status}</li>`;
                                        });
                                        html += `</ul>`;
                                    }

                                    html += `</div>`; // End contract card
                                });
                            } else {
                                html += '<p class="text-muted">No contracts found.</p>';
                            }

                            html += `</div></div>`; // End project collapse
                        });

                        html += `</div>`; // End accordion
                    } else {
                        html += '<p class="text-muted">No projects found.</p>';
                    }

                    // Insert HTML into modal
                    $("#modalContent").html(html);

                    // Show modal
                    var clientModal = new bootstrap.Modal(document.getElementById('clientModal'));
                    clientModal.show();
                },
                complete: function() {
                    $("#loader-overlay").fadeOut(300);
                }
            });
        });


        let clientToDelete = 0;
        let rowToDelete = null;

        $(document).on('click', '.btn-delete', function() {
            clientToDelete = $(this).closest('tr').data('id');
            rowToDelete = $(this).closest('tr');
            if(clientToDelete){
                $('#modal-loader').hide();                
                $('#modal-footer-buttons').show();     
                $('#deleteModal').modal('show');
            }
        });

        $('#confirmDeleteBtn').on('click', function() {
            if(!clientToDelete) return;

            $('#modal-footer-buttons').hide();
            $('#modal-loader').show();

            $.ajax({
                method: "POST",
                url: "delete_client.php",
                data: { client_id: clientToDelete },
                dataType: "json",
                success: function(response) {
                    $('#modal-loader').hide(); 
                    $('#modal-footer-buttons').show();

                    if(response.success){
                        if(rowToDelete) rowToDelete.remove();
                        $('#deleteModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Client and all associated records have been removed.',
                            background: '#1e1e2f',
                            color: '#00ffc6'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                            background: '#1e1e2f',
                            color: '#ff6b6b'
                        });
                    }
                },
                error: function() {
                    $('#modal-loader').hide();
                    $('#modal-footer-buttons').show();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Request failed. Try again.',
                        background: '#1e1e2f',
                        color: '#ff6b6b'
                    });
                }
            });
        });



        $(document).on('click', '.page-btn', function(){
            const page = $(this).data('page');
            clientRecords(page);
        });


        $(document).ready(function() {
            clientRecords();
        });



    </script>
</body>
</html>
