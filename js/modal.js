// Modal Controller
class ModalController {
    constructor() {
        this.initializeModals();
        this.setupEventListeners();
    }

    initializeModals() {
        // Get all modal triggers
        this.modalTriggers = {
            addOrg: document.getElementById('add-org-btn'),
            viewMembers: document.querySelectorAll('.btn-view[data-action="members"]'),
            viewPayments: document.querySelectorAll('.btn-view[data-action="payments"]'),
            addMember: document.querySelectorAll('.btn-edit[data-action="add-member"]'),
            addPayment: document.querySelectorAll('.btn-edit[data-action="add-payment"]')
        };

        // Get all modals
        this.modals = {
            addOrg: document.getElementById('org-modal'),
            members: document.getElementById('members-modal'),
            payments: document.getElementById('payments-modal'),
            addMember: document.getElementById('add-member-modal'),
            addPayment: document.getElementById('add-payment-modal')
        };

        // Get all close buttons
        this.closeButtons = document.querySelectorAll('.close');
    }

    setupEventListeners() {
        // Add Organization button
        if (this.modalTriggers.addOrg) {
            this.modalTriggers.addOrg.addEventListener('click', () => this.openModal('addOrg'));
        }

        // View Members buttons
        this.modalTriggers.viewMembers.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const orgId = e.currentTarget.dataset.id;
                const orgName = e.currentTarget.dataset.name;
                this.openModal('members', { orgId, orgName });
            });
        });

        // View Payments buttons
        this.modalTriggers.viewPayments.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const orgId = e.currentTarget.dataset.id;
                const orgName = e.currentTarget.dataset.name;
                this.openModal('payments', { orgId, orgName });
            });
        });

        // Add Member buttons
        this.modalTriggers.addMember.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const orgId = e.currentTarget.dataset.id;
                const orgName = e.currentTarget.dataset.name;
                this.openModal('addMember', { orgId, orgName });
            });
        });

        // Add Payment buttons
        this.modalTriggers.addPayment.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const orgId = e.currentTarget.dataset.id;
                const orgName = e.currentTarget.dataset.name;
                this.openModal('addPayment', { orgId, orgName });
            });
        });

        // Close buttons
        this.closeButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.currentTarget.closest('.modal');
                this.closeModal(modal);
            });
        });

        // Close on outside click
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.closeModal(e.target);
            }
        });
    }

    openModal(modalType, data = {}) {
        const modal = this.modals[modalType];
        if (!modal) return;

        // Set data if provided
        if (data.orgId) {
            modal.querySelector('[name="org_id"]')?.setAttribute('value', data.orgId);
        }
        if (data.orgName) {
            const nameDisplay = modal.querySelector('#org-name-display, #payment-org-name');
            if (nameDisplay) nameDisplay.textContent = data.orgName;
        }

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    closeModal(modal) {
        if (!modal) return;
        
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling

        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ModalController();
});

document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('show-fees-modal');
    const closeBtn = modal.querySelector('.close');
    const viewButtons = document.querySelectorAll('.view-status-btn');

    // Open modal when View button is clicked
    viewButtons.forEach(button => {
        button.onclick = function() {
            const studentId = this.getAttribute('data-student-id');
            modal.style.display = "block";
            
            // Here you can add AJAX call to fetch student fees
            fetchStudentFees(studentId);
        }
    });

    // Close modal when X is clicked
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Function to fetch student fees (you'll need to implement this)
    function fetchStudentFees(studentId) {
        // Example AJAX call
        fetch(`get_student_fees.php?student_id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#feesTable tbody');
                tbody.innerHTML = ''; // Clear existing rows
                
                data.forEach((fee, index) => {
                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${fee.organization}</td>
                            <td>${fee.fee_name}</td>
                            <td>${fee.amount}</td>
                            <td>
                                <span class="status-badge ${fee.status.toLowerCase()}">
                                    ${fee.status}
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error:', error));
    }
});