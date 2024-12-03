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
            const orgName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            
            // Show loading state
            const membersModal = document.getElementById('members-modal');
            const membersList = document.getElementById('members-list');
            membersList.innerHTML = '<p>Loading members...</p>';
            membersModal.style.display = 'block';

            // Fetch members data
            fetch(`get_org_members.php?org_id=${orgId}`)
                .then(response => response.text())
                .then(data => {
                    membersList.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                    membersList.innerHTML = '<p class="error">Error loading members</p>';
                });
        }
    }

    const viewPaymentsBtns = document.getElementsByClassName('view-payments-btn');
    for (let btn of viewPaymentsBtns) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            const paymentsModal = document.getElementById('payments-modal');
            const paymentsList = document.getElementById('payments-list');
            
            // Show loading state
            paymentsList.innerHTML = '<div class="loading">Loading...</div>';
            paymentsModal.style.display = 'block';

            // Fetch payments data
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => response.text())
                .then(data => {
                    paymentsList.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                    paymentsList.innerHTML = '<div class="error-message">Error loading fees</div>';
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
    const orgForm = document.getElementById('add-org-form');
    if (orgForm) {
        orgForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_organization_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    this.reset();
                    document.getElementById('org-modal').style.display = 'none';
                    location.reload(); // Reload to show new organization
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
            
            const formData = new FormData(this);
            
            fetch('add_member_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    this.reset();
                    document.getElementById('add-member-modal').style.display = 'none';
                } else {
                    alert(data.message || 'Failed to add member');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the member');
            });
        });
    }

    const paymentForm = document.getElementById('add-payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_payment_handler.php', {
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

    // Update the delete organization functionality
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
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        row.remove(); // Remove the row from the table
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

    // Handle delete member
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('delete-member-btn')) {
            const memberId = e.target.dataset.id;
            if (confirm('Are you sure you want to delete this member?')) {
                fetch('delete_member.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${memberId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        e.target.closest('tr').remove();
                        alert(data.message);
                    } else {
                        alert(data.message || 'Error deleting member');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the member');
                });
            }
        }
    });

    // Handle delete payment
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('delete-payment-btn')) {
            const paymentId = e.target.dataset.id;
            if (confirm('Are you sure you want to delete this payment?')) {
                fetch('delete_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${paymentId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        e.target.closest('tr').remove();
                        alert(data.message);
                    } else {
                        alert(data.message || 'Error deleting payment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the payment');
                });
            }
        }
    });

    // Update the view fees functionality
    const viewFeesBtns = document.getElementsByClassName('view-fees-btn');
    for (let btn of viewFeesBtns) {
        btn.onclick = function() {
            const orgId = this.dataset.id;
            
            // Show loading state
            const feesModal = document.getElementById('fees-modal');
            const feesList = document.getElementById('fees-list');
            feesList.innerHTML = '<p>Loading fees...</p>';
            feesModal.style.display = 'block';

            // Fetch fees data
            fetch(`get_org_fees.php?org_id=${orgId}`)
                .then(response => response.text())
                .then(data => {
                    feesList.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                    feesList.innerHTML = '<p class="error">Error loading fees</p>';
                });
        }
    }

    // Debug: Log when the script loads
    console.log('Organizations.js loaded');

    const viewPaymentBtns = document.querySelectorAll('.view-fees-btn');
    
    // Debug: Log number of buttons found
    console.log('Found view payment buttons:', viewPaymentBtns.length);

    viewPaymentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orgId = this.getAttribute('data-id');
            console.log('Clicked view payments for organization:', orgId); // Debug log

            // Show loading state
            const feesModal = document.getElementById('fees-modal');
            const feesList = document.getElementById('fees-list');
            
            if (feesList) {
                feesList.innerHTML = '<p>Loading fees...</p>';
            }
            
            if (feesModal) {
                feesModal.style.display = 'block';
            }

            // Fetch fees data with explicit URL
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => {
                    console.log('Response status:', response.status); // Debug log
                    return response.text();
                })
                .then(data => {
                    console.log('Received data:', data); // Debug log
                    if (feesList) {
                        feesList.innerHTML = data;
                    } else {
                        console.error('fees-list element not found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching fees:', error);
                    if (feesList) {
                        feesList.innerHTML = '<p class="error">Error loading fees</p>';
                    }
                });
        });
    });

    // Close modal functionality
    const closeButtons = document.querySelectorAll('.close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.view-fees-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orgId = this.dataset.id;
            console.log('Clicked organization ID:', orgId); // Debug log
            
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => {
                    console.log('Response status:', response.status); // Debug log
                    return response.text();
                })
                .then(data => {
                    console.log('Received data:', data); // Debug log
                    document.querySelector('.payment-table tbody').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Add event listener for delete fee buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-fee')) {
            const button = e.target.closest('.delete-fee');
            const feeId = button.dataset.feeId;
            
            if (confirm('Are you sure you want to delete this fee?')) {
                fetch('delete_fee.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `fee_id=${feeId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        button.closest('tr').remove();
                        alert('Fee deleted successfully');
                    } else {
                        alert(data.message || 'Error deleting fee');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the fee');
                });
            }
        }
    });

    // Edit member handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-member')) {
            const button = e.target.closest('.edit-member');
            const memberId = button.dataset.id;
            console.log('Edit member clicked, ID:', memberId);

            // Get the modal
            const modal = document.getElementById('edit-member-modal');
            
            // Store the current organization ID for refreshing
            const orgId = document.querySelector('[data-org-id]').dataset.orgId;
            modal.dataset.orgId = orgId;
            
            // Fetch member details
            fetch(`get_member_details.php?id=${memberId}`)
                .then(response => response.json())
                .then(member => {
                    // Populate the form
                    document.getElementById('edit-member-id').value = member.id;
                    document.getElementById('edit-student-id').value = member.student_id;
                    document.getElementById('edit-student-name').value = member.student_name;
                    document.getElementById('edit-course').value = member.course;
                    document.getElementById('edit-year').value = member.year;
                    document.getElementById('edit-position').value = member.position;
                    
                    // Show the modal
                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading member details');
                });
        }
    });

    // Handle form submission
    document.getElementById('edit-member-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const modal = document.getElementById('edit-member-modal');
        const orgId = modal.dataset.orgId;

        fetch('update_member.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the members table
                fetch(`get_org_members.php?org_id=${orgId}`)
                    .then(response => response.text())
                    .then(html => {
                        const membersList = document.getElementById('members-list');
                        if (membersList) {
                            membersList.innerHTML = html;
                        }
                        // Close the modal
                        modal.style.display = 'none';
                        alert('Member updated successfully');
                    })
                    .catch(error => {
                        console.error('Error refreshing table:', error);
                        alert('Member updated but error refreshing display');
                    });
            } else {
                alert(data.message || 'Error updating member');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating member');
        });
    });

    // Close modal when clicking the X
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('edit-member-modal').style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('edit-member-modal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Helper function to refresh members list
    function refreshMembersList(orgId) {
        fetch(`get_org_members.php?org_id=${orgId}`)
            .then(response => response.text())
            .then(data => {
                const membersList = document.querySelector('#members-list');
                if (membersList) {
                    membersList.innerHTML = data;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error refreshing members list');
            });
    }

    // Edit fee handler for both view details and edit buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-fee') || e.target.closest('.view-fee-details')) {
            e.preventDefault();
            const button = e.target.closest('.edit-fee') || e.target.closest('.view-fee-details');
            const feeId = button.dataset.feeId;
            console.log('Edit/View fee clicked, ID:', feeId);

            // Get the modal
            const modal = document.getElementById('edit-fee-modal');
            
            // Store the current organization ID for refreshing
            const orgId = document.querySelector('.fees-table').dataset.orgId;
            modal.dataset.orgId = orgId;
            
            // Fetch fee details
            fetch(`get_fee_details.php?id=${feeId}`)
                .then(response => response.json())
                .then(fee => {
                    console.log('Fee details:', fee);
                    // Populate the form
                    document.getElementById('edit-fee-id').value = fee.fee_id;
                    document.getElementById('edit-fee-name').value = fee.fee_name;
                    document.getElementById('edit-amount').value = fee.amount;
                    document.getElementById('edit-due-date').value = fee.due_date;
                    document.getElementById('edit-description').value = fee.description || '';
                    document.getElementById('edit-status').value = fee.status;
                    
                    // Show the modal
                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading fee details');
                });
        }
    });

    // Handle fee form submission
    document.getElementById('edit-fee-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const modal = document.getElementById('edit-fee-modal');
        const orgId = modal.dataset.orgId;

        // Debug: Log form data
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        fetch('update_fee.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data); // Debug log
            if (data.success) {
                // Refresh the fees table
                const paymentsList = document.querySelector('.payments-content');
                if (paymentsList) {
                    fetch(`get_org_payments.php?org_id=${orgId}`)
                        .then(response => response.text())
                        .then(html => {
                            paymentsList.innerHTML = html;
                            // Close the modal
                            modal.style.display = 'none';
                            alert(data.message || 'Fee updated successfully');
                        })
                        .catch(error => {
                            console.error('Error refreshing table:', error);
                            alert('Fee updated but error refreshing display');
                        });
                } else {
                    console.error('Could not find payments-content element');
                    alert('Fee updated but could not refresh display');
                }
            } else {
                alert(data.message || 'Error updating fee');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the fee. Please check the console for details.');
        });
    });

    // Close modal when clicking the X
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('edit-fee-modal').style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('edit-fee-modal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Add this function to manually refresh the fees list
    function refreshFeesList(orgId) {
        const paymentsList = document.querySelector('.payments-content');
        if (paymentsList) {
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => response.text())
                .then(html => {
                    paymentsList.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error refreshing fees list:', error);
                });
        }
    }

    // Add this to check if the event listeners are properly attached
    console.log('Edit handlers initialized');
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Number of edit member buttons:', document.querySelectorAll('.edit-member').length);
        console.log('Number of edit fee buttons:', document.querySelectorAll('.edit-fee').length);
    });

    // Edit fee form submission
    document.getElementById('edit-fee-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('update_fee.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById('edit-fee-modal').style.display = 'none';
                // Refresh the fees list
                const orgId = document.getElementById('edit-fee-org-id').value;
                refreshFeesList(orgId);
            } else {
                alert(data.message || 'Error updating fee');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the fee');
        });
    });

    // Helper function to refresh lists
    function refreshFeesList(orgId) {
        fetch(`get_org_payments.php?org_id=${orgId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('payments-list').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error refreshing fees list');
            });
    }

    // Edit fee handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-fee')) {
            const button = e.target.closest('.edit-fee');
            const feeId = button.dataset.feeId;
            console.log('Edit fee clicked, ID:', feeId);

            // Get the modal
            const modal = document.getElementById('edit-fee-modal');
            
            // Store the current organization ID for refreshing
            const orgId = document.querySelector('.fees-table').dataset.orgId;
            modal.dataset.orgId = orgId;
            
            // Fetch fee details
            fetch(`get_fee_details.php?id=${feeId}`)
                .then(response => response.json())
                .then(fee => {
                    // Populate the form
                    document.getElementById('edit-fee-id').value = fee.fee_id;
                    document.getElementById('edit-fee-name').value = fee.fee_name;
                    document.getElementById('edit-amount').value = fee.amount;
                    document.getElementById('edit-due-date').value = fee.due_date;
                    document.getElementById('edit-status').value = fee.status;
                    
                    // Show the modal
                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading fee details');
                });
        }
    });

    // Handle fee form submission
    document.getElementById('edit-fee-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const modal = document.getElementById('edit-fee-modal');
        const orgId = modal.dataset.orgId;

        fetch('update_fee.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the fees table
                fetch(`get_org_payments.php?org_id=${orgId}`)
                    .then(response => response.text())
                    .then(html => {
                        const feesList = document.getElementById('payments-list');
                        if (feesList) {
                            feesList.innerHTML = html;
                        }
                        // Close the modal
                        modal.style.display = 'none';
                        alert('Fee updated successfully');
                    })
                    .catch(error => {
                        console.error('Error refreshing table:', error);
                        alert('Fee updated but error refreshing display');
                    });
            } else {
                alert(data.message || 'Error updating fee');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating fee');
        });
    });
}); 

