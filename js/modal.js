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

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('show-fees-modal');
    const closeBtn = modal.querySelector('.close');
    const loadingIndicator = document.getElementById('loading-indicator');

    // Event delegation for view buttons
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('view-status-btn') ||
            e.target.closest('.view-status-btn')) {
            const button = e.target.classList.contains('view-status-btn') ?
                e.target :
                e.target.closest('.view-status-btn');
            const studentId = button.getAttribute('data-student-id');

            modal.style.display = "block";
            loadingIndicator.style.display = "block";

            fetch(`get_student_fees.php?student_id=${studentId}`)
                .then(response => response.json())
                .then(data => {
                    loadingIndicator.style.display = "none";
                    updateFeesTable(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingIndicator.style.display = "none";
                });
        }
    });

    function updateFeesTable(fees) {
        const tbody = document.querySelector('#feesTable tbody');
        tbody.innerHTML = ''; // Clear existing rows

        if (fees.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">No fees found for this student</td>
                </tr>`;
            return;
        }

        fees.forEach((fee, index) => {
            const statusClass = fee.paymentStatus.toLowerCase().replace(/\s+/g, '-');
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${fee.organization}</td>
                    <td>${fee.FeeName}</td>
                    <td>₱${fee.Amount}</td>
                    <td>
                        <span class="status-badge ${statusClass}">
                            ${fee.paymentStatus}
                        </span>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Close modal when X is clicked
    closeBtn.onclick = function () {
        modal.style.display = "none";
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});