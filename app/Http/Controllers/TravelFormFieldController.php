<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TravelFormFieldController extends Controller
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
            case 'Air Travel':
                $html = ' 
                          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label">Travel Date From*</label>
              <input type="datetime-local" id="travel_date_from" name="travel_date_from" class="form-control" required>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label class="control-label">Travel Date To*</label>
              <input type="datetime-local" id="travel_date_to" name="travel_date_to" class="form-control" required>
            </div>
          </div>
           <div class="col-sm-3">
  <div class="form-group">
    <label class="control-label">Preferred Time Departure*</label>
    <input type="datetime-local" id="preferred_time_departure" name="preferred_time_departure" class="form-control" required>
  </div>
</div>

<div class="col-sm-3">
  <div class="form-group">
    <label class="control-label">Preferred Time Return*</label>
    <input type="datetime-local" id="preferred_time_return" name="preferred_time_return" class="form-control" required>
  </div>
</div>
    <div class="col-sm-6">
  <div class="form-group">
    <label class="control-label">Destination</label>
    <textarea id="destination" class="form-control" name="destination" rows="5" 
      placeholder="Input your purpose (250 characters max)..." 
      maxlength="250" required></textarea>
  </div>
</div>
    <div class="col-sm-6">
  <div class="form-group">
    <label class="control-label">Additional Request / (Optional)</label>
    <textarea id="additional_request" class="form-control" name="additional_request" rows="5" 
      placeholder="Input your purpose (250 characters max)..." 
      maxlength="250"></textarea>
  </div>
</div>
                 <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="item_to_borrow">Accomodation</label>
                   <select name="accommodation" id="accommodation" required>
                    <option disabled selected>Choose</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                    </select>
                  </div>
                </div>    
                 <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="item_to_borrow">Purpose</label>
                   <select name="purpose" id="purpose" required>
                    <option disabled selected>Choose</option>
                    <option value="Sales Activity">Sales Activity</option>
                    <option value="Project">Project</option>
                     <option value="Others">Others</option>
                    </select>
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
       required >
    </div>
</div>

';
                break;

            case 'Land Transport':
                $html = '
                 <div class="col-sm-3">
  <div class="form-group">
    <label class="control-label">Travel Date From*</label>
    <input type="datetime-local" id="travel_date_from" name="travel_date_from" class="form-control" required>
  </div>
</div>

<div class="col-sm-3">
  <div class="form-group">
    <label class="control-label">Travel Date To*</label>
    <input type="datetime-local" id="travel_date_to" name="travel_date_to" class="form-control" required>
  </div>
</div>

 <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label" for="item_to_borrow">Purpose*</label>
                   <select name="purpose" id="purpose" required>
                    <option disabled selected>Choose</option>
                    <option value="Sales Activity">Sales Activity</option>
                    <option value="Project">Project</option>
                     <option value="Others">Others</option>
                    </select>
                  </div>
                  </div>

        <div class="col-sm-6">
  <div class="form-group">
    <label class="control-label">Destination</label>
    <textarea id="destination" class="form-control" name="destination" rows="5" 
      placeholder="Input your purpose (250 characters max)..." 
      maxlength="250" required></textarea>
  </div>
</div>
    <div class="col-sm-6">
  <div class="form-group">
    <label class="control-label">Additional Request / (Optional)</label>
    <textarea id="additional_request" class="form-control" name="additional_request" rows="5" 
      placeholder="Input your purpose (250 characters max)..." 
      maxlength="250"></textarea>
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
