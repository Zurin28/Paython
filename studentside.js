// Add these lines at the beginning of your script.js file
function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');

    // Close dropdown when clicking outside
    window.onclick = function(event) {
        if (!event.target.matches('.user-icon') && !event.target.matches('.bx-user')) {
            const dropdowns = document.getElementsByClassName('dropdown-menu');
            for (let dropdown of dropdowns) {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    }
}

// Get modal elements
const modal = document.getElementById("paymentModal");
const successModal = document.getElementById("successModal");
const payNowBtn = document.getElementById("payNowBtn");

// Function to load modal content
function loadModalContent(event) {
    const button = event.target;
    const feeData = {
        feeId: button.dataset.feeId,
        organizationName: button.dataset.organization,
        feeName: button.dataset.feeName,
        amount: button.dataset.amount,
        dueDate: button.dataset.due
    };
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'payment_modal.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status === 200) {
            modal.innerHTML = this.responseText;
            modal.style.display = "block";
            
            // Add event listeners after content is loaded
            const closeBtn = modal.querySelector(".close");
            const doneButton = modal.querySelector(".btn-done");
            const cancelButton = modal.querySelector(".btn-cancel");
            
            // Close modal when clicking X
            closeBtn.addEventListener('click', () => {
                modal.style.display = "none";
            });
            
            // Handle Done button click
            doneButton.addEventListener('click', () => {
                // Hide payment modal
                modal.style.display = "none";
                
                // Show success modal
                successModal.style.display = "block";
                
                // Hide success modal after 2 seconds and update payment status
                setTimeout(() => {
                    successModal.style.display = "none";
                    
                    // Update button
                    payNowBtn.textContent = "Pending";
                    payNowBtn.classList.remove("pay-now");
                    payNowBtn.classList.add("pending");
                    payNowBtn.disabled = true;
                    
                    // Update status cell
                    const statusCell = payNowBtn.parentElement.previousElementSibling.previousElementSibling;
                    statusCell.textContent = "Pending";
                }, 2000);
            });
            
            // Handle Cancel button click
            cancelButton.addEventListener('click', () => {
                modal.style.display = "none";
            });
        } else {
            console.error('Error loading modal content');
            modal.innerHTML = '<div class="modal-content"><p>Error loading payment form. Please try again.</p></div>';
        }
    };
    
    // Setup error handler
    xhr.onerror = function() {
        console.error('Request failed');
        modal.innerHTML = '<div class="modal-content"><p>Error loading payment form. Please try again.</p></div>';
    };
    
    // Send the fee data with the request
    xhr.send('feeData=' + JSON.stringify(feeData));
}

// Open payment modal when clicking Pay Now
payNowBtn.addEventListener('click', loadModalContent);

// Close payment modal when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

