<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Request Notification</title>
  <style type="text/css">
    table { border-collapse: collapse !important; padding: 0px !important; border: none !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    table td { border-collapse: collapse; text-decoration: none; }
    body { margin: 0px; padding: 0px; background-color: #f9f9f9; font-family: Arial, sans-serif; }
    .ExternalClass * { line-height: 100%; }

    @media only screen and (max-width:640px) {
      body {width:auto!important;}
      table[class=main] {width:85% !important;}
      table[class=full] {width:100% !important; margin:0px auto;}
      table[class=table-inner] {width:90% !important; margin:0px auto;}
      td[class="table-merge"] { display: block; width: 100% !important; }
      img[class="image-full"] { width: 100% !important; }
    }

    @media only screen and (max-width:479px) {
      body {width:auto!important;}
      table[class=main] {width:93% !important;}
      table[class=full] {width:100% !important; margin:0px auto;}
      td[class="table-merge"] { display: block; width: 100% !important; }
      table[class=table-inner] {width:90% !important; margin:0px auto;}
      img[class="image-full"] { width: 100% !important; }
    }
  </style>
</head>
<body>

<!-- Wrapper Table -->
<table bgcolor="#f9f9f9" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td align="center">

      <!-- Main Container -->
      <table class="main" width="800" bgcolor="#ffffff" cellpadding="0" cellspacing="0">

        <!-- Header -->
        <tr>
          <td align="center" style="padding: 30px 0; border-bottom: 1px solid #eee; background:#009fe3;">
            <table width="600" class="full" align="center">
              <tr>
                <td width="600" align="center">
                  <a href="#">
                    <img src="{{ $logoDataUri }}" alt="Sanden Logo" width="90" height="90">
                  </a>
                  <p style="color:white; font-size:smaller; margin-top:5px;">
                    105 Makiling Drive, Carmelray Industrial Park II Km 54 National Hi-way,
                    Brgy. Milagrosa Calamba City, 4027 Laguna | Tel: (049) 554-9970 up to 79 | Manila Line: (02) 8-898-5110
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Title Section -->
        <tr>
          <td align="center" style="padding: 60px 0;">
            <h3 style="margin: 0; text-transform: uppercase; font-size: 18px; color: #222;">Travel Request System</h3>
            <hr style="width: 40px; border: 1px solid #2fb28c; margin: 0 auto;">

            <!-- Travel Request Table -->
            <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; font-size: 14px; color: #333;">
              <tr>
                <td style="font-weight:bold; background-color:#f0f0f0; width:30%;">TRF No</td>
                <td>{{ $request_data['trf_no'] ?? 'N/A' }}</td>
              </tr>
                <tr>
                <td style="font-weight:bold; background-color:#f0f0f0;">Requestor</td>
              <td>{{ $request_data->user->name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-weight:bold; background-color:#f0f0f0;">Department</td>
                <td>{{ $request_data['department'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-weight:bold; background-color:#f0f0f0;">Travel Dates</td>
                <td>{{ $request_data['travel_date_from'] ?? 'N/A' }} to {{ $request_data['travel_date_to'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-weight:bold; background-color:#f0f0f0;">Destination</td>
                <td>{{ $request_data['destination'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-weight:bold; background-color:#f0f0f0;">Purpose</td>
                <td>{{ $request_data['purpose'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-weight:bold; background-color:#f0f0f0;">Additional Request</td>
                <td>{{ $request_data['additional_request'] ?? 'N/A' }}</td>
              </tr>
          
              <tr>
                <td colspan="2" align="center" style="padding: 20px;">
                  <a href="https://intranet.sanden.net/TravelRequest/ApprovalForm/{{$request_data['trf_no']}}" 
                     style="background-color: #007bff; color: #ffffff; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block;">
                     View / Approve Request
                  </a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td bgcolor="#009fe3" style="padding: 50px 0;">
            <table width="600" align="center">
              <tr>
                <td align="left" style="font-size: 12px; color: #f9f9f9; font-weight: bold;">Email Address</td>
              </tr>
              <tr>
                <td align="left" style="font-size: 16px; color: #f9f9f9; font-weight: bold;">sanden.mis.sm@sanden-rs.com</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Copyright -->
        <tr>
          <td bgcolor="#009fe3" style="padding: 35px 0;">
            <table width="600" align="center">
              <tr>
                <td align="center" style="font-size: 14px; color: #ffffff; font-weight: bold;">
                  &copy; All Rights Reserved. MIS.
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
