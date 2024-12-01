<div class="modal-content">
    <span class="close">&times;</span>
    <h2>Payment Information</h2>
    
    <div class="payment-container">
        <div class="payment-left">
            <div class="payment-method">
                <div class="method-header">
                    <span>Choose payment method</span>
                    <button class="edit-btn">Edit</button>
                </div>
                <select id="paymentMethod">
                    <option value="gcash">GCash payment</option>
                </select>
            </div>

            <div class="qr-section">
                <img src="qr-code.png" alt="QR Code" id="qrCode">
                <div class="qr-details">
                    <h4>Scan to Pay Here</h4>
                    <p>CCS-CSC</p>
                    <p>09**45**569</p>
                </div>
            </div>

            <div class="receipt-upload">
                <p>Upload the E-Receipt</p>
                <div class="upload-area" id="uploadArea">
                    <span>Input IMG file</span>
                </div>
            </div>
        </div>

        <div class="payment-right">
            <div class="payment-summary">
                <h3>Total amount</h3>
                <div class="amount">150 php</div>
                <p class="secure-text">Secure payment</p>
                
                <div class="summary-details">
                    <h4>Payment Summary</h4>
                    <div class="summary-row">
                        <span>CSC Palaro Fee</span>
                        <span>150 php</span>
                    </div>
                    <div class="summary-row">
                        <span>CCS-CSC</span>
                    </div>
                    <div class="summary-row">
                        <span>Payment Method</span>
                        <span>GCash</span>
                    </div>
                    <div class="summary-row">
                        <span>Juan De La Cruz</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn-done">Done</button>
</div> 