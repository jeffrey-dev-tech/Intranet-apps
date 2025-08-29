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
              <div class="logo_sanden">
            <img src="{{ asset('/img/Sanden_Logo_SCP2_.png') }}" style="width:350px;">
          </div>
          <div class="title">
            <h3>SANDEN COLD CHAIN SYSTEM PHILIPPINES INC.</h3>
        <p>105 Makiling Drive, Allegis IT Park, CIP, Km. 54 National Highway</p>
          <p>Milagrosa (Tulo), 4027 City of Calamba, Laguna Philippines</p>  
          <p>Tel. No. Laguna Line: (049) 554 9970-79 / Manila Line: (02) 898 5110</p> 
          <p>Vat Reg. Tin: 010-385-357-00000</p>
          
          </div>
        </td>

        <!-- Right column with Logo and Info -->
        <td style="vertical-align:top; padding-left:20px;">
     
          
         



           
        </td>
      </tr>
      <tr>
       <td style="width:400px; vertical-align:top;">
        <br>
            <h3>OFFICIAL RECEIPT</h3>
        </td>
          <td style="width:400px; vertical-align:top;">
        <br>
            <h3>No.</h3>
        </td>
      </tr>

      <tr>
        <td>&#9633; CASH</td>
        <td>Payment Date _______________</td>
      </tr>

           <tr>
        <td>&#9633; CREDIT CARD</td>
        <td>Account No. _________________</td>
      </tr>

      <tr>
        <td colspan="2" style="font-weight:bold; border: 1px solid black;">RECEIVED FROM </td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight:bold; border: 1px solid black;">Register Name </td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight:bold; border: 1px solid black;">TIN No.</td>
      </tr>
       <tr>
        <td colspan="2" style="font-weight:bold; border: 1px solid black;">Business Style </td>
      </tr>
      <tr>
        <td colspan="2" style="font-weight:bold; border: 1px solid black;">Business Address </td>
      </tr>


    </table>

    <table style="border: 1px solid black; border-collapse: collapse; width: 100%;">
      <tr>
        <th style="border: 1px solid black; padding: 5px;">
          DESCRIPTION OF TRANSACTION/NATURE OF SERVICE
        </th>
        <th style="border: 1px solid black; padding: 5px;">AMOUNT</th>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 5px; ">Payment for:</td>
        <td style=" padding: 5px;height:20px;"></td>
      </tr>
      <tr>
        <td style=" padding: 5px;"></td>
        <td style="border: 1px solid black; padding: 5px;height:20px;"></td>
      </tr>
      <tr>
        <td style="border: 1px solid black; padding: 5px;"></td>
        <td style="border: 1px solid black; padding: 5px;height:20px;"></td>
      </tr>
    </table>

  </div>

</body>
</html>
