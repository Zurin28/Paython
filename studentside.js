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
function loadModalContent() {
    // Create new XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    
    // Configure the request
    xhr.open('GET', 'payment_modal.php', true);
    
    // Setup request handler
    xhr.onload = function() {
        if (this.status === 200) {
            // Insert the modal content
            modal.innerHTML = this.responseText;
            
            // Show the modal
            modal.style.display = "block";
            
            // Add event listeners after content is loaded
            const closeBtn = modal.querySelector(".close");
            const doneButton = modal.querySelector(".btn-done");
            
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
    
    // Send the request
    xhr.send();
}

// Open payment modal when clicking Pay Now
payNowBtn.addEventListener('click', loadModalContent);

// Close payment modal when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

