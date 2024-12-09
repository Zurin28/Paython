<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Management</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/staffbar.css">
    <link rel="stylesheet" href="css/staff_fees.css">
</head>
<body>
    <?php include 'staffbar.php'; ?>

    <div class="content-wrapper">
        <div class="header-section">
            <button class="add-fee-btn" onclick="openAddFeeModal()">
                <i class='bx bx-plus'></i>
                Add New Fee
            </button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fee ID</th>
                        <th>Fee Name</th>
                        <th>Amount</th>
                        <th>Date Added</th>
                        <th>Due Date</th>
                        <th>Created By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>FEE001</td>
                        <td>CSC Membership</td>
                        <td>₱500</td>
                        <td>Mar 15, 2024</td>
                        <td>Apr 15, 2024</td>
                        <td>John Doe</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn edit" onclick="openEditModal(1)">
                                    <i class='bx bx-edit-alt'></i>
                                    Edit
                                </button>
                                <button class="btn delete" onclick="deleteFee(1)">
                                    <i class='bx bx-trash'></i>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Fee Modal -->
    <div id="addFeeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddFeeModal()">&times;</span>
            <h2>Add New Fee</h2>
            <form id="addFeeForm">
                <div class="form-group">
                    <label for="feeId">Fee ID</label>
                    <input type="text" id="feeId" name="feeId" required 
                        placeholder="Enter Fee ID (e.g., FEE001)">
                </div>
                <div class="form-group">
                    <label for="feeName">Fee Name</label>
                    <input type="text" id="feeName" name="feeName" required 
                        placeholder="Enter fee name">
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" required 
                        placeholder="Enter amount">
                </div>
                <div class="form-group">
                    <label for="dueDate">Due Date</label>
                    <input type="date" id="dueDate" name="dueDate" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" 
                        placeholder="Enter fee description"></textarea>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Add Fee</button>
                    <button type="button" class="cancel-btn" onclick="closeAddFeeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Fee Modal -->
    <div id="editFeeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Fee</h2>
            <form id="editFeeForm">
                <div class="form-group">
                    <label for="editFeeId">Fee ID</label>
                    <input type="text" id="editFeeId" name="editFeeId" required 
                        placeholder="Enter Fee ID (e.g., FEE001)" readonly>
                </div>
                <div class="form-group">
                    <label for="editFeeName">Fee Name</label>
                    <input type="text" id="editFeeName" name="editFeeName" required 
                        placeholder="Enter fee name">
                </div>
                <div class="form-group">
                    <label for="editAmount">Amount</label>
                    <input type="number" id="editAmount" name="editAmount" required 
                        placeholder="Enter amount">
                </div>
                <div class="form-group">
                    <label for="editDueDate">Due Date</label>
                    <input type="date" id="editDueDate" name="editDueDate" required>
                </div>
                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea id="editDescription" name="editDescription" rows="4" 
                        placeholder="Enter fee description"></textarea>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Update Fee</button>
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
        function openAddFeeModal() {
            document.getElementById('addFeeModal').style.display = 'flex';
        }

        function closeAddFeeModal() {
            document.getElementById('addFeeModal').style.display = 'none';
        }

        function openEditModal(feeId) {
            const editModal = document.getElementById('editFeeModal');
            
            // Here you would typically fetch the fee details from your database
            // For now, we'll use sample data
            document.getElementById('editFeeId').value = 'FEE001';
            document.getElementById('editFeeName').value = 'CSC Membership';
            document.getElementById('editAmount').value = '500';
            document.getElementById('editDueDate').value = '2024-04-15';
            document.getElementById('editDescription').value = 'Sample description';
            
            editModal.style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editFeeModal').style.display = 'none';
        }

        function deleteFee(feeId) {
            if(confirm('Are you sure you want to delete this fee?')) {
                // Implement delete functionality
                alert('Delete fee with ID: ' + feeId);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addFeeModal');
            const editModal = document.getElementById('editFeeModal');
            
            if (event.target == addModal) {
                closeAddFeeModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }

        // Handle form submission
        document.getElementById('editFeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the data to your server
            alert('Fee updated successfully!');
            closeEditModal();
        });
    </script>
</body>
</html> 