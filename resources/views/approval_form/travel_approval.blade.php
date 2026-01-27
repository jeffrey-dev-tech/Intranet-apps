@extends('layouts.app')

@section('title', 'Travel Request Approval')

@section('content')
<style>
    table {
        border-collapse: collapse !important;
        padding: 0px !important;
        border: none !important;
        border-bottom-width: 0px !important;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    table td {
        border-collapse: collapse;
        text-decoration: none;
    }

    body {
        margin: 0px;
        padding: 0px;
        background-color: #f9f9f9;
    }

    .ExternalClass * {
        line-height: 100%;
    }
</style>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">IT Request Approval</a></li>
            <li class="breadcrumb-item active" aria-current="page">Approval</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card mx-auto">
            <div class="table-responsive">
                <table bgcolor="#f9f9f9" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td align="center">
                            <table class="main" width="800" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 30px 0; border-bottom: 1px solid #eee;background:#009fe3;">
                                        <table width="600" align="center">
                                            <tr>
                                                <td align="center">
                                                    <a href="#">
                                                        <img src="{{ asset('img/sanden-logo-white.png') }}" alt="Sanden Logo" width="90" height="90">
                                                    </a>
                                                    <p style="color:white; font-size:smaller;">
                                                        105 Makiling Drive, Carmelray Industrial Park II Km 54 National Hi-way,
                                                        Brgy. Milagrosa Calamba City, 4027 Laguna Tel. No. :(049) 554-9970 up
                                                        to 79 Manila Line: (02) 8-898- 5110
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" style="padding: 60px 0;">
                                        <h3 style="margin: 0; text-transform: uppercase; font-size: 18px; color: #222;">Sanden</h3>
                                        <h1 style="margin: 15px 0 20px; text-transform: uppercase; font-size: 30px; color: #222;">Intranet</h1>
                                        <hr style="width: 40px; border: 1px solid #2fb28c; margin: 0 auto;">

                                        <table border="1" cellpadding="8" cellspacing="0" width="95%"
                                            style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                                            <tr>
                                                <td colspan="2" align="center" style="background-color: #ffffff;">
                                                    <h4>Travel Request Approval Form</h4>
                                                </td>
                                            </tr>




                                            <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">TRF NO.</td>
                                                <td>{{$trf_no}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">Type of Request</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; background-color: #f9f9f9;">Description</td>
                                                <td></td>
                                            </tr>




                                            <hr>

                                            <tr id="approvalRow">
                                                <td colspan="2" align="center" style="padding: 20px;">
                                                    <div class="form-group">
                                                        <div class="row align-items-center mb-3">
                                                            <div class="col-md-2 text-md-end">
                                                                <label for="status" class="col-form-label">Action</label>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <select class="form-control" id="status" name="status">
                                                                    <option disabled selected>Choose your action</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-start mb-3">
                                                            <div class="col-md-2 text-md-end">
                                                                <label for="remarks" class="col-form-label">Remarks</label>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12 text-end">
                                                                <button id="submitBtn" class="btn btn-success" type="button" name="submit_actions">
                                                                    Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>




                                        </table>
                                    </td>
                                </tr>



                                <tr>
                                    <td bgcolor="#009fe3" style="padding: 50px 0; text-align:center; color:#fff; font-weight:bold;">
                                        mis.scp@sanden-rs.com
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#009fe3" style="padding: 35px 0; text-align:center; color:#fff; font-weight:bold;">
                                        &copy; All Rights Reserved. MIS.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection