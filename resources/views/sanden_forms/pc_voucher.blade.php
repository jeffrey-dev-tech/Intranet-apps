<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>4 Vouchers per Page</title>
<style>
body {
    font-family: Arial, sans-serif;
    font-size: 8pt;
    margin: 0;
    padding: 0;
}

.voucher-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: separate;   /* IMPORTANT */
    border-spacing: 5px;        /* GAP BETWEEN VOUCHERS */
}


.voucher {
    width: 50%;
    height: 50%;
    padding: 2mm;
    border: 1px dashed #000; /* changed from solid to dashed */
    vertical-align: top;
}

.voucher-inner {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
}

.voucher-inner th, .voucher-inner td {
    border: 1px solid #000;
    padding: 3px;
    vertical-align: middle;
}

.particulars th {
    text-align: center;
    background: #f0f0f0;
}

.particulars tr {
    height: 12mm;
}

.signature-table td {
    border: none;
    padding: 20px;
    font-size: 8px;
    border: 1px solid #000;
}


</style>
</head>
<body>

@php $seriesIndex = 0; @endphp

<table class="voucher-table">
@for ($row = 0; $row < 2; $row++)
<tr>
    @for ($col = 0; $col < 2; $col++)
    @php
        $formattedSeries = $seriesNumbers[$seriesIndex++];
    @endphp
    <td class="voucher">
        <table class="voucher-inner">
            <tr><th colspan="3">SANDEN COLD CHAIN SYSTEM PHILIPPINES INC</th></tr>
            <tr>
                <td colspan="3" style="text-align:center; font-size:7pt;">
                    105 Makiling Drive Allegis IT Park<br>
                    Carmelray Industrial Park II, KM 54 NATIONAL HIGHWAY<br>
                    Brgy. Milagrosa Calamba City Laguna 4027
                </td>
            </tr>
            <tr>
                <th colspan="3" style="
                    font-family: 'Courier New', Courier, monospace;
                    font-size: 20px;
                    font-weight: bold;
                    text-align: center;
                    letter-spacing: 2px;
                ">
                    PETTY CASH VOUCHER
                    <span style="
                        color: red;
                        display: block;
                        font-size: 16px;
                        font-family: 'Courier New', Courier, monospace;
                    ">{{ $formattedSeries }}</span>
                </th>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; vertical-align: top;">Payee: </td>
                <td>Date:_____ <br>Dept:_____</td>
            </tr>
            <tr>
                <td colspan="2">Cost Center: _________ Profit Center: ________</td>
                <td>Branch: ___ SBU: ___</td>
            </tr>
            <tr class="particulars">
                <th colspan="2">PARTICULARS</th>
                <th>AMOUNT</th>
            </tr>
            @for ($i = 0; $i < 8; $i++)
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @endfor
            <tr><td colspan="2">Total Expenses</td><td></td></tr>
            <tr><td colspan="2">Cash Advance</td><td></td></tr>
            <tr><td colspan="2">(Cash Return)/Claim</td><td></td></tr>
            <tr>
                <th>Prepared by:</th>
                <th>Approved by:</th>
                <th>Received by:</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 33%; text-align: center; font-size: 8px;">Printed Name & Signature</td>
                <td style="width: 33%; text-align: center; font-size: 8px;">Printed Name & Signature</td>
                <td style="width: 33%; text-align: center; font-size: 8px;">Printed Name & Signature</td>
            </tr>
            <tr>
                <td colspan="3">Paid by: Petty Cash Custodian</td>
            </tr>
        </table>
    </td>
    @endfor
</tr>
@endfor
</table>

</body>
</html>
