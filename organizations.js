document.addEventListener('DOMContentLoaded', function() {
    const addOrgBtn = document.getElementById('add-org-btn');
    const orgModal = document.getElementById('org-modal');
    const membersModal = document.getElementById('members-modal');
    const paymentsModal = document.getElementById('payments-modal');
    const addMemberModal = document.getElementById('add-member-modal');
    const addPaymentModal = document.getElementById('add-payment-modal');
    const closeBtns = document.getElementsByClassName('close');

    addOrgBtn.onclick = function() {
        orgModal.style.display = 'block';
    }

    for (let closeBtn of closeBtns) {
        closeBtn.onclick = function() {
            orgModal.style.display = 'none';
            membersModal.style.display = 'none';
            paymentsModal.style.display = 'none';
            addMemberModal.style.display = 'none';
            addPaymentModal.style.display = 'none';
        }
    }

    window.onclick = function(e) {
        if (e.target === orgModal) {
            orgModal.style.display = 'none';
        }
        if (e.target === membersModal) {
            membersModal.style.display = 'none';
        }
        if (e.target === paymentsModal) {
            paymentsModal.style.display = 'none';
        }
        if (e.target === addMemberModal) {
            addMemberModal.style.display = 'none';
        }
        if (e.target === addPaymentModal) {
            addPaymentModal.style.display = 'none';
        }
    }

    const addMemberBtns = document.getElementsByClassName('add-member-btn');
    for (let btn of addMemberBtns) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            const orgName = this.dataset.name;
            document.getElementById('member-org-id').value = orgId;
            document.getElementById('org-name-display').textContent = orgName;
            addMemberModal.style.display = 'block';
        }
    }

    const viewMembersBtns = document.getElementsByClassName('view-members-btn');
    for (let btn of viewMembersBtns) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            fetch(`get_org_members.php?org_id=${orgId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('members-list').innerHTML = data;
                    membersModal.style.display = 'block';
                });
        }
    }

    const viewPaymentsBtns = document.getElementsByClassName('view-payments-btn');
    for (let btn of viewPaymentsBtns) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('payments-list').innerHTML = data;
                    paymentsModal.style.display = 'block';
                });
        }
    }

    const addPaymentBtns = document.getElementsByClassName('add-payment-btn');
    for (let btn of addPaymentBtns) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            const orgName = this.dataset.name;
            document.getElementById('payment-org-id').value = orgId;
            document.getElementById('payment-org-name').textContent = orgName;
            
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];
            
            addPaymentModal.style.display = 'block';
        }
    }

    // Add table row highlighting
    const tableRows = document.querySelectorAll('#org-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseout', function() {
            this.style.backgroundColor = '';
        });
    });

    // Form submissions
    const orgForm = document.getElementById('org-form');
    if (orgForm) {
        orgForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_organization.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    this.reset();
                    document.getElementById('org-modal').style.display = 'none';
                    // Reload the page to show the new organization
                    location.reload();
                } else {
                    alert(data.message || 'Failed to add organization');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the organization');
            });
        });
    }

    const memberForm = document.getElementById('add-member-form');
    if (memberForm) {
        memberForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add form handling here when database is ready
            alert('Member added successfully!');
            this.reset();
            document.getElementById('add-member-modal').style.display = 'none';
        });
    }

    const paymentForm = document.getElementById('add-payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_organization_payment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Payment added successfully');
                    // Close the modal
                    document.getElementById('add-payment-modal').style.display = 'none';
                    // Reset the form
                    this.reset();
                    // Refresh the payments list if the payments modal is open
                    const paymentsModal = document.getElementById('payments-modal');
                    if (paymentsModal.style.display === 'block') {
                        refreshPaymentsList(formData.get('org_id'));
                    }
                } else {
                    alert(data.message || 'Failed to add payment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the payment');
            });
        });
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#org-table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Delete organization functionality
    const deleteButtons = document.getElementsByClassName('delete-org-btn');
    for (let btn of deleteButtons) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            const row = this.closest('tr');
            
            if (confirm('Are you sure you want to delete this organization?')) {
                const formData = new FormData();
                formData.append('org_id', orgId);
                
                fetch('delete_organization.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Remove the row from the table
                        row.remove();
                        alert(data.message);
                    } else {
                        alert(data.message || 'Error deleting organization');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the organization');
                });
            }
        }
    }

    // Add this function to refresh the payments list
    function refreshPaymentsList(orgId) {
        fetch(`get_org_payments.php?org_id=${orgId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('payments-list').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error refreshing payments list');
            });
    }

    $(document).ready(function() {
    $("#org-form").submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        // Get form data
        var orgName = $("#org_name").val();
        var orgID = $("#org_id").val();

        // Send data to the server using AJAX
        $.ajax({
            url: "add_organization_handler.php", // Create a new PHP file for handling the request
            method: "POST",
            data: {
                org_name: orgName,
                org_id: orgID
            },
            success: function(response) {
                if (response == 'success') {
                    alert('Organization added successfully!');
                    location.reload(); // Reload the page to reflect the new organization
                } else {
                    alert('Failed to add organization.');
                }
            },
            error: function() {
                alert('Error occurred. Please try again.');
            }
        });
    });
});
}); 