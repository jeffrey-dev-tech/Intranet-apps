@extends('layouts.app')

@section('title', 'Deposit Form')

@section('content')

  <style>
 .sanden-logo {
  display: flex;
  flex-direction: column;
  align-items: center; /* centers horizontally */
  text-align: center; /* center-aligns text inside h6 */
}

.sanden-logo img{
  width: 700px;
  margin-bottom: 10px;
}

@media (max-width: 766px) {
	.sanden-logo img{
  width: 350px;
  margin-bottom: 10px;
}

}
@media (max-width: 398px) {
	.sanden-logo img{
  width: 325px;
  margin-bottom: 10px;
}
}
.loader {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin: 20px auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

    #viewer {
      width: 100%;
      /* background: #78b1e3; */
      height: auto;
      border: 1px solid #ccc;
      margin-top: 20px;
      padding: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      overflow: auto;
      user-select: none;           /* 🔒 Prevent text selection */
    }

    img, canvas {
      max-width: 100%;
      border: none;
      margin-bottom: 20px;
      pointer-events: none;       /* 🔒 Prevent drag/save */
    }

    pre {
      white-space: pre-wrap;
      text-align: left;
      padding: 10px;
      width: 100%;
      box-sizing: border-box;
      user-select: none;          /* 🔒 Prevent text selection */
    }

    /* 🔒 Prevent printing of viewer content */
    @media print {
      #viewer, #viewer canvas, #viewer pre {
        display: none !important;
      }
    }
    

input[type="file"] {
  position: relative;
}

input[type="file"]::file-selector-button {
  width: 136px;
  color: transparent;
}

/* Faked label styles and icon */
input[type="file"]::before {
  position: absolute;
  pointer-events: none;
  top: 10px;
  left: 16px;
  height: 20px;
  width: 20px;
  content: "";
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230964B0'%3E%3Cpath d='M18 15v3H6v-3H4v3c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-3h-2zM7 9l1.41 1.41L11 7.83V16h2V7.83l2.59 2.58L17 9l-5-5-5 5z'/%3E%3C/svg%3E");
}

input[type="file"]::after {
  position: absolute;
  pointer-events: none;
  top: 11px;
  left: 40px;
  color: #0964b0;
  content: "Upload File";
}

/* ------- From Step 1 ------- */

/* file upload button */
input[type="file"]::file-selector-button {
  border-radius: 4px;
  padding: 0 16px;
  height: 40px;
  cursor: pointer;
  background-color: white;
  border: 1px solid rgba(0, 0, 0, 0.16);
  box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
  margin-right: 16px;
  transition: background-color 200ms;
}

/* file upload button hover state */
input[type="file"]::file-selector-button:hover {
  background-color: #f3f4f6;
}

/* file upload button active state */
input[type="file"]::file-selector-button:active {
  background-color: #e5e7eb;
}


/* this responsive for table custom */
/* Override Bootstrap behavior */




</style>
<div class="page-content">
    <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Forms</a></li>
						<li class="breadcrumb-item active" aria-current="page">Collection Receipt</li>
					</ol>
				</nav>
		<div class="row">
					<div class="col-md-12 stretch-card">
						<div class="card">
							<div class="card-body">

							   	<div class="sanden-logo">
										<img src="{{ asset('img/Sanden_Logo_SCP2_.png')}}" alt="sanden-logo">
										<div class="title-form">
											<h6 class="card-title" style="font-size:25px;">Collection Receipt</h6>
										</div>
                    
									</div>
		    <form id="Deposit_Form" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Customer Name</label>
                <select name="customer_name" id="customer_name">
                  <option selected disabled>Choose Customer</option>
                  <option value="Customer1">Customer 1</option>
                    <option value="Customer3">Customer 3</option>
                   <option value="Customer2">Customer 2</option>
                </select>
              </div>
            </div>
             <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Amount</label>
               <input type="text" id="amount" placeholder="Enter amount" class="form-control">
              </div>
            </div>
                <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Date</label>
               <input type="date" id="date_deposit"  class="form-control">
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label">Transaction Type</label>
                <select name="transaction_type" id="transaction_type">
                  <option selected disabled>Choose Transaction Type</option>
                  <option value="Deposit">Deposit</option>
                    <option value="Billing">Billing</option>
                </select>
              </div>
            </div>
          
            <div class="col-sm-12">
              <div class="form-group">
                <input type="file" name="fileToUpload" id="fileToUpload" accept="application/pdf"  required>
              </div>
            </div>
            
            <div class="col-sm-12">
              <div class="form-group">
                <input type="button" name="btnDeposit" id="btnDeposit"  class="btn btn-success" value="Submit">
              </div>
            </div>
          </div>
        </form>
                                </div>
             </div>
        </div>
    </div>
</div>


<script>
const amountInput = document.getElementById('amount');

// Restrict invalid characters (only digits, one dot)
amountInput.addEventListener('keypress', function (e) {
    const char = String.fromCharCode(e.which);
    const value = e.target.value;

    // Allow only numbers and dot, but only one dot
    if (!/[0-9.]/.test(char) || (char === '.' && value.includes('.'))) {
        e.preventDefault();
    }
});

amountInput.addEventListener('input', function (e) {
    let value = e.target.value.replace(/,/g, '');

    if (!isNaN(value) && value !== '') {
        let parts = value.split('.');

        // Limit decimal part to 4 digits
        if (parts[1] && parts[1].length > 4) {
            parts[1] = parts[1].substring(0, 4);
        }

        // Add commas to integer part
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        e.target.value = parts.join('.');
    }
});

// Ensure 4 decimal places on blur
amountInput.addEventListener('blur', function (e) {
    let value = e.target.value.replace(/,/g, '');
    if (value && !isNaN(value)) {
        e.target.value = parseFloat(value).toLocaleString('en-US', {
            minimumFractionDigits: 4,
            maximumFractionDigits: 4
        });
    }
});
</script>

<script>
    // Initialize Choices.js on the select element
    const userSelect = new Choices('#customer_name', {
        searchEnabled: true,      // Enable search
        itemSelectText: '',       // Remove "Press to select" text
        shouldSort: true,        // Keep original order
        placeholder: true,        // Enable placeholder
        searchPlaceholderValue: 'Search customer...', // Search box placeholder
    });
     // Initialize Choices.js on the select element
    const transaction_typeSelect = new Choices('#transaction_type', {
        searchEnabled: true,      // Enable search
        itemSelectText: '',       // Remove "Press to select" text
        shouldSort: false,        // Keep original order
        placeholder: true,        // Enable placeholder
        searchPlaceholderValue: 'Search transaction type...', // Search box placeholder
    });
</script>
@endsection