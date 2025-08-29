<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Two Column Layout with Fixed Left & Flexible Right</title>
  <style>
    table.DOP {
      border-collapse: collapse;
      width: 100%;
    }
    table.DOP, 
    table.DOP td {
      border: 1px solid black;
      height: 30px;
    }
    table.DOP td {
      padding: 6px;
    }

    .logo_sanden {
      text-align: right;
      margin-bottom: 10px;

    }
    .title h3 {
      margin: 0 0 5px 0;
      font-family: 'Eras', sans-serif;
      font-weight: bold;
    }
    .title h6 {
      margin: 0;
      text-align: center;
      font-family: 'Calibri', sans-serif;
      font-weight: 100;
      line-height: 1.3;
    }
  body { 
  font-family: sans-serif; 
  font-size: 9pt; 
  margin: 0; 
  padding: 0; 
}

.layout-wrapper {
  margin-top: 10px; /* or 0 for no margin */
    margin: 0; 
  padding: 0; 
}


  </style>
</head>
<body>

  <div class="layout-wrapper">
    <table style="width:100%;">
      <tr>
        <!-- Left column with Payment Table -->
        <td style="width:400px; vertical-align:top;">
          <table class="DOP">
            <tr>
              <td colspan="3" style="color:white; background:black; font-weight:bold;text-align:center">
                Details of Payment
              </td>
            </tr>
            <tr>
              <td>Reference</td>
              <td colspan="2">Amount</td>
            </tr>

         <!-- Empty rows -->
<tr class="row_ra"><td></td><td ></td><td style="width: 5px;"></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td></td><td></td><td></td></tr>
<tr class="row_ra"><td>Total Sales (VAT inclusive)</td><td></td><td></td></tr>
<tr class="row_ra"><td>Less: VAT</td><td></td><td></td></tr>
<tr class="row_ra"><td>Total</td><td></td><td></td></tr>
<tr class="row_ra"><td>Less Withholding Tax</td><td></td><td></td></tr>
<tr class="row_ra"><td>Amount Due</td><td></td><td></td></tr>
<tr>
<td colspan="3" style="color:white; background:black; font-weight:bold; text-align:center">
Form of Payment
</td>
</tr>
<tr >
    <td style="border: none; font-weight:bold;">Cash ▢</td>
    <td style="border: none;font-weight:bold;">Check ▢</td>
    <td style="border: none; font-weight: bold; text-decoration: underline;">
  Bank/Branch/Check#
</td>

</tr>
          </table>
        </td>

        <!-- Right column with Logo and Info -->
        <td style="vertical-align:top; padding-left:20px;">
          <div class="logo_sanden">
            <img src="{{ asset('/img/Sanden_Logo_SCP2_.png') }}" style="width:350px;">
          </div>
          <div class="title">
            <h3>SANDEN COLD CHAIN SYSTEM PHILIPPINES INCORPORATED</h3>
        <p>105 Makiling Drive, Allegis IT Park, CIP, Km. 54 National Highway</p>
          <p>Milagrosa (Tulo), 4027 City of Calamba, Laguna Philippines</p>  
          <p>Tel. No. Laguna Line: (049) 554 9970-79 / Manila Line: (02) 898 5110</p> 
          <p>Vat Reg. Tin: 010-385-357-00000</p>
          
          </div>
            <br>
            <br>
            <br>
         
  <div style="width:100%;">
  <span style="float:left; font-style:italic; font-size:20px;">COLLECTION RECEIPT</span>
  <span style="float:right; font-style:italic; font-size:20px;">No.</span>
</div>


           
        </td>
      </tr>
    </table>
  </div>

</body>
</html>
