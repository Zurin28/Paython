document.addEventListener('DOMContentLoaded', function () {
    const addOrgBtn = document.getElementById('add-org-btn');
    const orgModal = document.getElementById('org-modal');
    const membersModal = document.getElementById('members-modal');
    const paymentsModal = document.getElementById('payments-modal');
    const addMemberModal = document.getElementById('add-member-modal');
    const addPaymentModal = document.getElementById('add-payment-modal');
    const closeBtns = document.getElementsByClassName('close');
    const paymentForm = document.getElementById('add-payment-form');

    addOrgBtn.onclick = function () {
        orgModal.style.display = 'block';
    }

    for (let closeBtn of closeBtns) {
        closeBtn.onclick = function () {
            orgModal.style.display = 'none';
            membersModal.style.display = 'none';
            paymentsModal.style.display = 'none';
            addMemberModal.style.display = 'none';
            addPaymentModal.style.display = 'none';
        }
    }

    window.onclick = function (e) {
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
        btn.onclick = function () {
            const orgId = this.dataset.id;
            const orgName = this.dataset.name;
            document.getElementById('member-org-id').value = orgId;
            document.getElementById('org-name-display').textContent = orgName;
            addMemberModal.style.display = 'block';
        }
    }

    const viewMembersBtns = document.getElementsByClassName('view-members-btn');
    for (let btn of viewMembersBtns) {
        btn.onclick = function () {
            const orgId = this.dataset.id;
            const orgName = this.dataset.name;

            // Update the modal header with organization name
            document.getElementById('org-name-header').textContent = orgName;

            // Show the modal
            document.getElementById('view-members-modal').style.display = 'block';

            // Fetch members data
            fetch(`get_org_members.php?org_id=${orgId}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('members-table-body');
                    tbody.innerHTML = ''; // Clear existing content

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No members found</td></tr>';
                        return;
                    }

                    data.forEach((member, index) => {
                        const row = `
                            <tr>
                                <td style="border: 1px solid #ddd;">${index + 1}</td>
                                <td style="border: 1px solid #ddd;">${member.StudentID}</td>
                                <td style="border: 1px solid #ddd;">${member.first_name} ${member.last_name}</td>
                                <td style="border: 1px solid #ddd;">${member.WmsuEmail}</td>
                                <td style="border: 1px solid #ddd;">${member.Position}</td>
                                <td style="border: 1px solid #ddd;">
                                    <button class="btn btn-danger remove-member-btn" 
                                        data-student-id="${member.StudentID}" 
                                        data-org-id="${orgId}"
                                        data-org-name="${orgName}">
                                        <i class="fas fa-user-minus"></i> Remove
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });

                    // Call the function to attach listeners after populating the table
                    attachRemoveButtonListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('members-table-body').innerHTML =
                        '<tr><td colspan="6" class="text-center">Error loading members</td></tr>';
                });
        }
    }

    // Add event listener for remove buttons after populating the table
    function attachRemoveButtonListeners() {
        document.querySelectorAll('.remove-member-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('Remove button clicked'); // Debug log

                if (confirm('Are you sure you want to remove this member?')) {
                    const studentId = this.dataset.studentId;
                    const orgId = this.dataset.orgId;
                    const orgName = this.dataset.orgName;

                    console.log('Removing member:', { studentId, orgId, orgName }); // Debug log

                    fetch('remove_member_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            student_id: studentId,
                            org_id: orgId,
                            org_name: orgName
                        })
                    })
                        .then(response => {
                            console.log('Response received:', response); // Debug log
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data); // Debug log
                            if (data.status === 'success') {
                                this.closest('tr').remove();
                                alert('Member removed successfully');
                            } else {
                                alert(data.message || 'Failed to remove member');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while removing the member');
                        });
                }
            });
        });
    }

    const viewPaymentsBtns = document.getElementsByClassName('view-payments-btn');
    for (let btn of viewPaymentsBtns) {
        btn.onclick = function () {
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
        btn.onclick = function () {
            // First check if there's an active academic period
            fetch('get_current_period.php')
                .then(response => response.json())
                .then(period => {
                    if (!period.school_year || !period.semester) {
                        alert('No active academic period set. Please set an academic period first.');
                        return;
                    }

                    const orgId = this.dataset.id;
                    const orgName = this.dataset.name;
                    document.getElementById('payment-org-id').value = orgId;
                    document.getElementById('payment-org-name').textContent = orgName;

                    // Add academic period to hidden inputs
                    const form = document.getElementById('add-payment-form');
                    let yearInput = form.querySelector('input[name="school_year"]');
                    let semesterInput = form.querySelector('input[name="semester"]');

                    if (!yearInput) {
                        yearInput = document.createElement('input');
                        yearInput.type = 'hidden';
                        yearInput.name = 'school_year';
                        form.appendChild(yearInput);
                    }
                    if (!semesterInput) {
                        semesterInput = document.createElement('input');
                        semesterInput.type = 'hidden';
                        semesterInput.name = 'semester';
                        form.appendChild(semesterInput);
                    }

                    yearInput.value = period.school_year;
                    semesterInput.value = period.semester;

                    // Set default due date
                    const dueDate = new Date();
                    dueDate.setDate(dueDate.getDate() + 30);
                    document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];

                    addPaymentModal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error checking academic period. Please try again.');
                });
        }
    }

    // Add table row highlighting
    const tableRows = document.querySelectorAll('#org-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseover', function () {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseout', function () {
            this.style.backgroundColor = '';
        });
    });

    // Form submissions


    const memberForm = document.getElementById('add-member-form');
    if (memberForm) {
        memberForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Check if all required fields are filled
            const studentId = document.getElementById('student_id').value.trim();
            const position = document.getElementById('position').value.trim();
            const studentName = document.getElementById('student_name').value.trim();

            if (!studentId || !position || !studentName) {
                alert('Please fill in all required fields and ensure student details are loaded');
                return;
            }

            // First fetch current academic period
            fetch('get_current_period.php')
                .then(response => response.json())
                .then(period => {
                    if (!period.school_year || !period.semester) {
                        throw new Error('No active academic period found');
                    }

                    const formData = new FormData(this);
                    formData.append('school_year', period.school_year);
                    formData.append('semester', period.semester);

                    return fetch('add_member_handler.php', {
                        method: 'POST',
                        body: formData
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        // Close modal and reset form
                        document.getElementById('add-member-modal').style.display = 'none';
                        this.reset();
                        clearAndHideFields(); // Clear and hide fields after successful submission
                        
                        // Refresh the members list in the view members modal if it's open
                        const orgId = formData.get('org_id');
                        const viewMembersModal = document.getElementById('view-members-modal');
                        if (viewMembersModal && viewMembersModal.style.display === 'block') {
                            fetch(`get_org_members.php?org_id=${orgId}`)
                                .then(response => response.json())
                                .then(data => {
                                    const tbody = document.getElementById('members-table-body');
                                    tbody.innerHTML = ''; // Clear existing content

                                    if (data.length === 0) {
                                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No members found</td></tr>';
                                        return;
                                    }

                                    data.forEach((member, index) => {
                                        const row = `
                                            <tr>
                                                <td style="border: 1px solid #ddd;">${index + 1}</td>
                                                <td style="border: 1px solid #ddd;">${member.StudentID}</td>
                                                <td style="border: 1px solid #ddd;">${member.first_name} ${member.last_name}</td>
                                                <td style="border: 1px solid #ddd;">${member.WmsuEmail}</td>
                                                <td style="border: 1px solid #ddd;">${member.Position}</td>
                                                <td style="border: 1px solid #ddd;">
                                                    <button class="btn btn-danger remove-member-btn" 
                                                        data-student-id="${member.StudentID}" 
                                                        data-org-id="${orgId}">
                                                        <i class="fas fa-user-minus"></i> Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        `;
                                        tbody.innerHTML += row;
                                    });

                                    // Reattach event listeners for the new buttons
                                    attachRemoveButtonListeners();
                                })
                                .catch(error => {
                                    console.error('Error refreshing members list:', error);
                                });
                        }
                    } else {
                        alert(data.message || 'Failed to add member');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the member: ' + error.message);
                });
        });
    }

    if (paymentForm) {
        console.log('Payment form found:', !!paymentForm);

        paymentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            console.log('Payment form submitted');

            // Validate required fields
            const feeId = document.getElementById('fee_id').value.trim();
            const feeName = document.getElementById('fee_name').value.trim();
            const amount = document.getElementById('amount').value.trim();
            const dueDate = document.getElementById('due_date').value.trim();

            if (!feeId || !feeName || !amount || !dueDate) {
                alert('Please fill in all required fields');
                return;
            }

            let formData; // Declare formData in the outer scope

            // First fetch current academic period
            fetch('get_current_period.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to get academic period');
                    }
                    return response.json();
                })
                .then(period => {
                    if (!period.school_year || !period.semester) {
                        throw new Error('No active academic period found');
                    }

                    formData = new FormData(paymentForm); // Assign to the outer scope variable
                    formData.append('add_payment', '1');
                    formData.append('school_year', period.school_year);
                    formData.append('semester', period.semester);

                    console.log('Submitting payment with data:', Object.fromEntries(formData));

                    return fetch('add_payment_handler.php', {
                        method: 'POST',
                        body: formData
                    });
                })
                .then(async response => {
                    const rawResponse = await response.text();
                    console.log('Raw response:', rawResponse);

                    try {
                        const data = JSON.parse(rawResponse);
                        return data;
                    } catch (e) {
                        throw new Error(`Invalid JSON response: ${rawResponse}`);
                    }
                })
                .then(data => {
                    console.log('Parsed response:', data);
                    if (data.status === 'success') {
                        alert(data.message);
                        // Close the modal
                        document.getElementById('add-payment-modal').style.display = 'none';
                        // Reset the form
                        paymentForm.reset();
                        // Optionally refresh the payments list if visible
                        const orgId = formData.get('org_id'); // Now formData is accessible here
                        if (document.getElementById('payments-modal').style.display === 'block') {
                            refreshPaymentsList(orgId);
                        }
                    } else {
                        throw new Error(data.message || 'Failed to add payment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the payment: ' + error.message);
                });
        });
    } else {
        console.error('Payment form not found in DOM');
    }

    // Add this search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            const tableRows = document.querySelectorAll('#org-table tbody tr');

            tableRows.forEach(row => {
                const orgId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const orgName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                // Show row if either ID or Name matches the search term
                if (orgId.includes(searchTerm) || orgName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Show "no results" message if no matches found
            const noResultsRow = document.getElementById('no-results-row');
            const hasVisibleRows = Array.from(tableRows).some(row => row.style.display !== 'none');

            if (!hasVisibleRows) {
                if (!noResultsRow) {
                    const tbody = document.querySelector('#org-table tbody');
                    const newRow = document.createElement('tr');
                    newRow.id = 'no-results-row';
                    newRow.innerHTML = `<td colspan="3" style="text-align: center;">No organizations found matching "${this.value}"</td>`;
                    tbody.appendChild(newRow);
                } else {
                    noResultsRow.style.display = '';
                }
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        });
    }

    // Update the delete organization functionality
    const deleteButtons = document.getElementsByClassName('delete-org-btn');
    for (let btn of deleteButtons) {
        btn.onclick = function () {
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
        if (!orgId) {
            console.error('No organization ID provided for refresh');
            return;
        }

        // First get current academic period
        fetch('get_current_period.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to get academic period');
                }
                return response.json();
            })
            .then(period => {
                if (!period.school_year || !period.semester) {
                    throw new Error('No active academic period found');
                }

                // Then fetch payments with academic period
                return fetch(`get_org_payments.php?org_id=${orgId}&school_year=${period.school_year}&semester=${period.semester}`);
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const paymentsList = document.getElementById('payments-list');
                if (paymentsList) {
                    // Clear existing content
                    paymentsList.innerHTML = '';

                    // Add new content
                    if (Array.isArray(data) && data.length > 0) {
                        const table = document.createElement('table');
                        table.className = 'payments-table';

                        // Add table header
                        table.innerHTML = `
                            <thead>
                                <tr>
                                    <th>Fee Name</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Description</th>
                                    <th>School Year</th>
                                    <th>Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.map(payment => `
                                    <tr>
                                        <td>${payment.fee_name}</td>
                                        <td>₱${parseFloat(payment.amount).toFixed(2)}</td>
                                        <td>${payment.due_date}</td>
                                        <td>${payment.description || ''}</td>
                                        <td>${payment.school_year}</td>
                                        <td>${payment.semester}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        `;
                        paymentsList.appendChild(table);
                    } else {
                        paymentsList.innerHTML = '<p>No payments found for the current academic period</p>';
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing payments:', error);
                alert('Error refreshing payments list: ' + error.message);
            });
    }

    // Handle delete member
    document.addEventListener('click', function (e) {
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
    document.addEventListener('click', function (e) {
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
        btn.onclick = function () {
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
        btn.addEventListener('click', function () {
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
        button.addEventListener('click', function () {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.view-fees-btn').forEach(btn => {
        btn.addEventListener('click', function () {
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
    document.addEventListener('click', function (e) {
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
    document.addEventListener('click', function (e) {
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

    // Close modal when clicking the X
    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('edit-member-modal').style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (e) {
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
    document.addEventListener('click', function (e) {
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
   

    // Close modal when clicking the X
    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('edit-fee-modal').style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (e) {
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
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Number of edit member buttons:', document.querySelectorAll('.edit-member').length);
        console.log('Number of edit fee buttons:', document.querySelectorAll('.edit-fee').length);
    });

    // Edit fee form submission
   

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
    document.addEventListener('click', function (e) {
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
    +

    // Auto-fill student details when student ID is entered
    document.getElementById('student_id').addEventListener('input', function() {
        const studentId = this.value.trim();
        const studentDetailsFields = document.querySelector('.student-details');
        
        if (studentId.length >= 5) { // Start searching after 5 digits
            fetch(`get_student_details.php?student_id=${encodeURIComponent(studentId)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Student details response:', data); // Debug log
                    
                    if (data.status === 'success' && data.student) {
                        // Auto-fill the form fields
                        document.getElementById('student_name').value = 
                            `${data.student.first_name} ${data.student.last_name}`;
                        document.getElementById('course').value = data.student.Course || '';
                        document.getElementById('year').value = data.student.Year || '';
                        document.getElementById('section').value = data.student.Section || '';
                        document.getElementById('email').value = data.student.WmsuEmail || '';

                        // Show all fields and enable submit button
                        studentDetailsFields.style.display = 'block';
                        document.getElementById('add-member-submit').disabled = false;
                    } else {
                        clearAndHideFields();
                        alert('Student not found');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    clearAndHideFields();
                    alert('Error fetching student details');
                });
        } else {
            clearAndHideFields();
        }
    });

    // Add this helper function
    function clearAndHideFields() {
        document.getElementById('student_name').value = '';
        document.getElementById('course').value = '';
        document.getElementById('year').value = '';
        document.getElementById('section').value = '';
        document.getElementById('email').value = '';
        document.querySelector('.student-details').style.display = 'none';
        document.getElementById('add-member-submit').disabled = true;
    }

    // Initialize form state when page loads
    document.addEventListener('DOMContentLoaded', function() {
        clearAndHideFields();
    });

    document.querySelectorAll('.view-payments-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const orgId = this.dataset.id;
            const orgName = this.dataset.name;

            // Debug logs
            console.log('Button clicked');
            console.log('Organization ID:', orgId);
            console.log('Organization Name:', orgName);

            // Get modal elements
            const modal = document.getElementById('view-payments-modal');
            const tbody = document.getElementById('payments-table-body');
            const titleSpan = document.getElementById('org-name-payments');

            // Update modal title
            titleSpan.textContent = orgName;

            // Show loading
            tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';

            // Display modal
            modal.style.display = 'block';

            // Fetch data
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Received data:', data);

                    if (!Array.isArray(data)) {
                        console.error('Data is not an array:', data);
                        tbody.innerHTML = '<tr><td colspan="6">Error: Invalid data format</td></tr>';
                        return;
                    }

                    // Clear tbody
                    tbody.innerHTML = '';

                    // Add rows
                    data.forEach((payment, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${payment.fee_name}</td>
                                <td>₱${parseFloat(payment.amount).toFixed(2)}</td>
                                <td>${payment.due_date}</td>
                                <td>${payment.description}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm view-fee-details" 
                                            data-fee-id="${payment.fee_id}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = '<tr><td colspan="6">Error loading data</td></tr>';
                });
        });
    });

    // Add these handlers only if they don't already exist
    if (!window.paymentModalHandlersInitialized) {
        // Close button handler
        document.querySelectorAll('.modal .close').forEach(closeBtn => {
            closeBtn.addEventListener('click', function () {
                this.closest('.modal').style.display = 'none';
            });
        });

        // Click outside modal to close
        window.addEventListener('click', function (e) {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });

        window.paymentModalHandlersInitialized = true;
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM Content Loaded');

        // Initialize view payments functionality
        initializeViewPayments();
    });

    function initializeViewPayments() {
        // Get required elements
        const viewPaymentsModal = document.getElementById('view-payments-modal');
        const paymentsTableBody = document.getElementById('payments-table-body');
        const orgNamePayments = document.getElementById('org-name-payments');

        // Debug logs
        console.log('Modal found:', !!viewPaymentsModal);
        console.log('Table body found:', !!paymentsTableBody);
        console.log('Org name span found:', !!orgNamePayments);

        // Check if elements exist
        if (!viewPaymentsModal || !paymentsTableBody || !orgNamePayments) {
            console.error('Required modal elements not found');
            return;
        }

        // Use event delegation for view payments buttons
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.view-payments-btn');
            if (!btn) return; // If click wasn't on a view payments button, exit

            e.preventDefault();

            const orgId = btn.dataset.id;
            const orgName = btn.dataset.name;

            console.log('Button clicked for:', orgId, orgName);

            // Update modal title
            orgNamePayments.textContent = orgName;

            // Show loading
            paymentsTableBody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';

            // Display modal
            viewPaymentsModal.style.display = 'block';

            // Fetch data
            fetch(`get_org_payments.php?org_id=${orgId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Raw data received:', data);

                    // Ensure data is an array
                    const paymentsArray = Array.isArray(data) ? data : [data];
                    console.log('Payments array:', paymentsArray);

                    // Clear tbody
                    paymentsTableBody.innerHTML = '';

                    if (paymentsArray.length === 0) {
                        paymentsTableBody.innerHTML = '<tr><td colspan="6">No payments found</td></tr>';
                        return;
                    }

                    // Add rows
                    paymentsArray.forEach((payment, index) => {
                        console.log('Processing payment:', payment); // Debug log

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${payment.fee_name || ''}</td>
                            <td>₱${parseFloat(payment.amount || 0).toFixed(2)}</td>
                            <td>${payment.due_date || ''}</td>
                            <td>${payment.description || ''}</td>
                            <td>
                                <button class="btn btn-primary view-fee-details" data-fee-id="${payment.fee_id}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        `;
                        paymentsTableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    paymentsTableBody.innerHTML = '<tr><td colspan="6">Error loading data</td></tr>';
                });
        });

        // Add close button handler
        const closeButtons = viewPaymentsModal.querySelectorAll('.close');
        closeButtons.forEach(closeBtn => {
            closeBtn.addEventListener('click', function () {
                viewPaymentsModal.style.display = 'none';
            });
        });

        // Add click outside handler
        window.addEventListener('click', function (e) {
            if (e.target === viewPaymentsModal) {
                viewPaymentsModal.style.display = 'none';
            }
        });
    }

    // View Payments Button Click Handler
    $(document).on('click', '.view-payments-btn', function (e) {
        e.preventDefault();
        const orgId = $(this).data('id');
        const orgName = $(this).data('name');

        // Get current academic period
        fetch('get_current_period.php')
            .then(response => response.json())
            .then(period => {
                // Update modal title with academic period
                $('#org-name-payments').text(`${orgName} - ${period.school_year} ${period.semester} Semester`);

                // Show modal
                $('#view-payments-modal').show();

                // Load payments data with academic period
                $.ajax({
                    url: 'get_org_payments.php',
                    type: 'GET',
                    data: {
                        org_id: orgId,
                        school_year: period.school_year,
                        semester: period.semester
                    },
                    success: function (response) {
                        const tbody = $('#payments-table-body');
                        tbody.empty();

                        if (Array.isArray(response)) {
                            response.forEach((payment, index) => {
                                tbody.append(`
                                    <tr>
                                        <td style="border: 1px solid #ddd;">${index + 1}</td>
                                        <td style="border: 1px solid #ddd;">${payment.fee_name}</td>
                                        <td style="border: 1px solid #ddd;">₱${parseFloat(payment.amount).toFixed(2)}</td>
                                        <td style="border: 1px solid #ddd;">${payment.due_date}</td>
                                        <td style="border: 1px solid #ddd;">${payment.description}</td>
                                        <td style="border: 1px solid #ddd;">
                                            <button class="btn btn-primary view-fee-details" data-fee-id="${payment.fee_id}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            tbody.append('<tr><td colspan="6" style="text-align: center;">No payments found</td></tr>');
                        }
                    },
                    error: function () {
                        $('#payments-table-body').html('<tr><td colspan="6" style="text-align: center;">Error loading payments</td></tr>');
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading academic period information.');
            });
    });

    // Close modal when clicking the X
    $(document).on('click', '#view-payments-modal .close', function () {
        $('#view-payments-modal').hide();
    });

    // Close modal when clicking outside
    $(window).on('click', function (e) {
        if ($(e.target).is('#view-payments-modal')) {
            $('#view-payments-modal').hide();
        }
    });

    // Add Payment Button Click Handler
    $(document).on('click', '.add-payment-btn', function (e) {
        e.preventDefault();
        const orgId = $(this).data('id');
        const orgName = $(this).data('name');
        const addPaymentModal = document.getElementById('add-payment-modal');

        // First check if there's an active academic period
        $.ajax({
            url: 'get_current_period.php',
            method: 'GET',
            success: function (response) {
                try {
                    const period = JSON.parse(response);
                    if (period && period.school_year && period.semester) {
                        // Show the modal and set the values
                        $('#payment-org-id').val(orgId);
                        $('#payment-org-name').text(orgName);

                        // Add hidden fields for academic period
                        const form = $('#add-payment-form');

                        // Remove any existing hidden fields first
                        form.find('input[name="school_year"]').remove();
                        form.find('input[name="semester"]').remove();

                        // Add new hidden fields
                        form.append(`
                            <input type="hidden" name="school_year" value="${period.school_year}">
                            <input type="hidden" name="semester" value="${period.semester}">
                        `);

                        // Set default due date
                        const dueDate = new Date();
                        dueDate.setDate(dueDate.getDate() + 30);
                        $('#due_date').val(dueDate.toISOString().split('T')[0]);

                        // Show modal
                        $('#add-payment-modal').show();

                    } else {
                        alert('No active academic period set. Please set an academic period first.');
                    }
                } catch (e) {
                    console.error('Error parsing academic period:', e);
                    alert('Error checking academic period. Please try again.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error checking academic period. Please try again.');
            }
        });
    });

    // Close modal when X is clicked
    $('.close').click(function () {
        $(this).closest('.modal').hide();
    });

    // Close modal when clicking outside
    $(window).click(function (event) {
        if ($(event.target).hasClass('modal')) {
            $('.modal').hide();
        }
    });

    // Add organization form submission
    $('#add-org-form').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response); // Will show "Organization added successfully" or error message
                if (response === "Organization added successfully") {
                    $('#org-modal').hide(); // Hide the modal instead of reloading
                    $('#add-org-form')[0].reset(); // Reset the form
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('An error occurred while adding the organization');
            }
        });
    });

    // Close modal when X is clicked
    $('.close').click(function () {
        $(this).closest('.modal').hide();
    });

    // Close modal when clicking outside
    $(window).click(function (event) {
        if ($(event.target).hasClass('modal')) {
            $('.modal').hide();
        }
    });

    // Only add event listeners if elements exist

    if (viewPaymentBtns && viewPaymentBtns.length > 0) {
        viewPaymentBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                // Your view payment logic here
            });
        });
    }
});

$(document).ready(function () {
    // View payments button click handler
    $(document).on('click', '.view-payments-btn', function () {
        const orgId = $(this).data('id');
        console.log('Viewing payments for org:', orgId);

        // Show modal
        $('#payments-modal').modal('show');

        // Load payments
        $.ajax({
            url: 'get_org_payments.php',
            type: 'GET',
            data: { org_id: orgId },
            success: function (response) {
                console.log('Response:', response);
                $('.payments-content').html(response);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                $('.payments-content').html('<p class="text-danger">Error loading payments</p>');
            }
        });
    });
});



