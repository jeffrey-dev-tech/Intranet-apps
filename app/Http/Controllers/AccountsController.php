<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DepartmentHead;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountsController extends Controller
{
    public function AccountPage()
    {
        $employeeList = User::with('head')->where('status','Active')->get();
        $departmentHeads = DepartmentHead::all();
        $departments = Department::all(); // load all departments

        return view('accounts.employee', compact('employeeList', 'departmentHeads', 'departments'));
    }

    // Update Status
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldStatus = $user->status;
        $user->status = $request->status;
        $user->save();
        // Log the change
        Log::info("User ID {$user->id} status changed from '{$oldStatus}' to '{$user->status}' by User ID " . auth()->id());
        return response()->json(['message' => 'Status updated successfully!']);
    }

    // Update Department
    public function updateDepartment(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldDept = $user->department_id;
        $user->department_id = $request->department_id;
        $user->save();

        Log::info("User ID {$user->id} department changed from '{$oldDept}' to '{$user->department_id}' by User ID " . auth()->id());

        return response()->json(['message' => 'Department updated successfully!']);
    }

    // Update Department Head
    public function updateHead(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldHead = $user->head_id;
        $user->head_id = $request->head_id;
        $user->save();

        Log::info("User ID {$user->id} head changed from '{$oldHead}' to '{$user->head_id}' by User ID " . auth()->id());

        return response()->json(['message' => 'Department head updated successfully!']);
    }

    // Update Position
    public function updatePosition(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldPosition = $user->position;
        $user->position = $request->position;
        $user->save();

        Log::info("User ID {$user->id} position changed from '{$oldPosition}' to '{$user->position}' by User ID " . auth()->id());

        return response()->json(['message' => 'Position updated successfully!']);
    }

public function resetPassword($id)
{
    // Prevent resetting password for developer ID 100
    if ($id == 100) {
        Log::info("Trying ADMIN ACCOUNT reset by User ID " . auth()->id());
        return response()->json([
            'message' => "Cannot reset password for developer account."
        ], 403); // Forbidden
        
    }

    $user = User::findOrFail($id);

    $defaultPassword = 'password123'; // or generate a random one

    $user->password = Hash::make($defaultPassword);
    $user->password_changed = 0;
    $user->save();

    // Log the action
    Log::info("Password for User ID {$user->id} reset by User ID " . auth()->id());

    return response()->json([
        'message' => "Password reset successfully to default."
    ]);
}
}
