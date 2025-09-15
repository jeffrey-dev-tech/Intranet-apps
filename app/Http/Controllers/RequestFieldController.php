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
                <option value="Other">Other</option>
                </select>

                  </div>
                </div>
                   <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label" for="priority">Importance</label>
                   <select name="priority" id="priority" required>
                     <option selected disabled">Choose</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-12">
      <div class="form-group">
        <label class="control-label">Description of Request</label>
        <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
      </div>
    </div>
         <div class="col-sm-12">
    <label class="control-label">Attachments</label>
    <div class="form-group">
        <input 
            type="file" 
            name="attachments[]" 
            multiple 
            accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
        >
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

            case 'New_Intranet_Subsystem':
        $managers = User::where('position', 'like', '%Manager%')->get();


                $html = '
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="subsystem_title">Subsystem Title</label>
                    <input type="text" class="form-control" name="subsystem_title" id="subsystem_title" placeholder="Input Sub System Details" required>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label" for="manager_email">Select Manager</label>
                    <select name="manager_email" id="manager_email" class="form-control">
                      <option value="">-- Select Manager --</option>';
                foreach ($managers as $manager) {
                    $html .= '<option value="' . $manager->email . '">' . $manager->name . '</option>';
                }
                $html .= '</select>
                  </div>
                </div>
                  <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label" for="priority">Importance</label>
                   <select name="priority" id="priority" required>
                     <option selected disabled">Choose</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Purpose of Subsystem</label>
                    <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your purpose 255 characters only..." required></textarea>
                  </div>
                </div>
                
         <div class="col-sm-12">
    <label class="control-label">Attachments</label>
    <div class="form-group">
        <input 
            type="file" 
            name="attachments[]" 
            multiple 
            accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
        >
    </div>
</div>';
                break;
     case 'Change_Request_Intranet':
        $managers = User::where('position', 'like', '%Manager%')->get();
                $html = '
                <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="subsystem_title">Sub System Name</label>
                    <input type="text" class="form-control" name="change_request_intranet" id="change_request_intranet" placeholder="Input the Name of Subsystem"required>
                  </div>
                </div>
                  <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label" for="manager_id">Select Manager</label>
                    <select name="manager_id" id="manager_id" class="form-control">
                      <option value="">-- Select Manager --</option>';
                foreach ($managers as $manager) {
                    $html .= '<option value="' . $manager->email . '">' . $manager->name . '</option>';
                }
                $html .= '</select>
                  </div>
                </div>
              <div class="col-sm-12">
      <div class="form-group">
        <label class="control-label">Details of Request</label>
        <textarea id="description_of_request" class="form-control" name="description_of_request" rows="5" placeholder="Input your request 255 characters only..." required></textarea>
      </div>
    </div>';
    
                break;         

            default:
                $html = '';
        }

        return response()->json(['html' => $html]);
    }
}
