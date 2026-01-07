<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RequestFieldController extends Controller
{

  public function showManagers()
    {
        $managers = User::where('position', 'Manager')->get();
        return view('managers', compact('managers'));
    }

    public function getFields(Request $request)
    {
        $type = $request->query('type');
        $html = '';

        switch ($type) {
            case 'Repair_Request':
                $html = '
                <div class="col-sm-6">
                  <div class="form-group">
                <label class="control-label" for="issue_details">Request for Troubleshoot</label>
                 <select name="issue" id="issue" class="form-control">
                <option value="">-- Select an option --</option>
                <option value="Email">Email</option>
                <option value="CCTV Concerns">CCTV Concerns</option>
                <option value="Laptop">Laptop</option>
                <option value="Desktop">Desktop</option>
                <option value="SAP B1 Hana System">SAP B1 Hana System</option>
                <option value="Hardware Components">Hardware Components (Printer, Keyboard, Mouse, etc)</option>
                <option value="Software Application">Software Application (Google, Microsoft, AutoCAD, ISI)</option>
                <option value="Network">Network (Internet Connection, WiFi Router, etc)</option>
                <option value="User Account / Password">User Account / Password (Windows, VPN, SAP)</option>
                <option value="Access to shared folder">Access to shared folder</option>
                <option value="Eset Anti Virus">Eset Anti Virus</option>
                <option value="Design">Design (Website, Brochure, Video & Photo shoot, etc)</option>
                <option value="Installation of Application">Installation of Application</option>
                <option value="Corporate Website">Corporate Website</option>
                    <option value="Intranet Support">Intranet Support</option>
                <option value="Other">Other</option>
                </select>

                  </div>
                </div>
                  
                <div class="col-sm-12">
      <div class="form-group">
        <label class="control-label">Description of Request</label>
        <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
      </div>
    </div>
';
                break;

            case 'Borrow_Item':
                $html = '
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label" for="item_to_borrow">Item to Borrow</label>
                   <select name="item_name" id="item_name"">
                    <option value="Keyboard">Keyboard</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Desktop">Desktop</option>
                    <option value="Mouse">Mouse</option>
                    <option value="Flashdrive">Flashdrive</option>
                    <option value="External Drive">External Drive</option>
                    <option value="HDMI">HDMI</option>
                    <option value="Bag">Bag</option>
                    </select>
                  </div>
                </div>
                 <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="date_needed">Date Needed</label>
                    <input type="date" class="form-control" name="date_needed" id="date_needed" required>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="borrow_date">Plan Return Date</label>
                    <input type="date" class="form-control" name="plan_return_date" id="plan_return_date" required>
                  </div>
                </div>
                <div class="col-sm-12">
      <div class="form-group">
        <label class="control-label">Description of Request</label>
        <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
      </div>
    </div>';
                break;

        

             case 'Purchase_Item':
                $html = '
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label" for="purchase_item_name">Item Details</label>
                    <input type="text" class="form-control" name="purchase_item_name" id="purchase_item_name" placeholder="Input Item Details"required>
                  </div>
                </div>
              <div class="col-sm-12">
      <div class="form-group">
        <label class="control-label">Description of Request</label>
        <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
      </div>
    </div>';
                break;
      case 'Project_Request':
                $html = '
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="project_details">Project Details</label>
                    <input type="text" class="form-control" name="project_details" id="project_details" placeholder="Input Project Details"required>
                  </div>
                </div>
              <div class="col-sm-12">
      <div class="form-group">
        <label class="control-label">Description of Request</label>
        <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
      </div>
    </div>';
                break;

            case 'Intranet_Request':
                $html = '
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label" for="intranet_request_type">Type</label>
                   <select name="intranet_request_type" id="intranet_request_type" required>
                    <option selected disabled>Choose Type</option>
                    <option value="New Intranet">New Intranet System</option>
                    <option value="Change Request">Change Request</option>
                   </select>
                  </div>
                </div>
          
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Purpose of Request</label>
                    <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
                  </div>
                </div>
                
  ';
                break;

   

            default:
                $html = '';
        }

        return response()->json(['html' => $html]);
    }
}
