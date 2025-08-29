
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Styled Email</title>
 <style type="text/css">
 
table { border-collapse: collapse !important; padding: 0px !important; border: none !important; border-bottom-width: 0px !important; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        table td { border-collapse: collapse; text-decoration: none;}
        body { margin: 0px; padding: 0px; background-color: #f9f9f9; }
       .ExternalClass * { line-height: 100%; }    

		
		
@media only screen and (max-width:640px)

{
	body {width:auto!important;}
	table [class=main] {width:85% !important;}
	table [class=full] {width:100% !important; margin:0px auto;}
	table [class=table-inner] {width:90% !important; margin:0px auto;}
	td[class="table-merge"] { display: block; width: 100% !important; }
	img[class="image-full"] { width: 100% !important; }

	}

@media only screen and (max-width:479px)
{
	body {width:auto!important;}
	table [class=main] {width:93% !important;}
	table [class=full] {width:100% !important; margin:0px auto;}
	td[class="table-merge"] { display: block; width: 100% !important; }
	table [class=table-inner] {width:90% !important; margin:0px auto;}
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
          <td align="center" style="padding: 30px 0; border-bottom: 1px solid #eee;background:#009fe3;">
            <table width="600" class="full" align="center">
              <tr>
                <td width="600" align="center">
                  <a href="#">
                  <img src="{{ $logoDataUri }}" alt="Sanden Logo" width="90" height="90">

                  </a>
                <span><p style="color:white; font-size:smaller;">105 Makiling Drive, Carmelray Industrial Park II Km 54 National Hi-way,
Brgy. Milagrosa Calamba City, 4027 Laguna Tel. No. :(049) 554-9970 up
to 79 Manila Line: (02) 8-898- 5110
</p></span>
                </td>
                
              
              </tr>
            </table>
          </td>
        </tr>
    
        <!-- Title Section -->
        <tr>
          <td align="center" style="padding: 60px 0;">
            <h3 style="margin: 0; text-transform: uppercase; font-size: 18px; color: #222;">Sanden</h3>
            <h1 style="margin: 15px 0 20px; text-transform: uppercase; font-size: 30px; color: #222;">Intranet</h1>
            <hr style="width: 40px; border: 1px solid #2fb28c; margin: 0 auto;">
          
             <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                   <tr>
        <td style="font-weight: bold; background-color: #ffffff; width: 30%;"colspan="2" align="center">  <h2>New Item Request</h2></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #ffffff; width: 30%;">Requestor</td>
        <td>{{ $requestor_name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #ffffff;">Item</td>
        <td>{{ $item_name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #ffffff;">Quantity</td>
        <td>{{ $qty }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #ffffff;">Date Needed</td>
        <td>{{ $date_needed }}</td>
    </tr>
     <tr>
        <td style="font-weight: bold; background-color: #ffffff;">Date Plan Return Date</td>
        <td>{{ $date_plan_return }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #ffffff;">Purpose</td>
        <td>{{ $purpose }}</td>
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