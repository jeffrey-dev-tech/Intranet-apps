<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Feature;
use App\Models\MaintenanceMode;
use Illuminate\Support\Facades\DB;
class SpecialAccessController extends Controller
{
    /**
     * Show the Special Access page with users and features
     */
    public function index()
    {
        // Load users with their features
        $users = User::with('features')->get();

        // Load all available features
        $features = Feature::all();

        return view('special_access.index', compact('users', 'features'));
    }

    /**
     * Assign a feature to a specific user
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'feature_id' => 'required|exists:features,id'
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if already assigned
        if ($user->features()->where('feature_id', $request->feature_id)->exists()) {
            return back()->with('error', 'This feature is already assigned to this user!');
        }

        // Attach feature
        $user->features()->attach($request->feature_id);

        return response()->json(['message' => 'Features Assigned and saved successfully.']);
    }

    public function maintenance_form()
    {

        $results = MaintenanceMode::all();
        return view('special_access.maintenance',compact('results'));
    }

    /**
     * Add a new feature into the features table
     */
    public function store_feature_tbl(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:features,name',
            'description' => 'nullable|string|max:255'
        ]);

        Feature::create($request->only(['name', 'description']));

        return back()->with('success', 'Feature added successfully!');
    }

    /**
     * Remove an assigned feature from a user (pivot record)
     */
    public function destroy($userId, $featureId)
    {
        $user = User::findOrFail($userId);
        $user->features()->detach($featureId);

        return back()->with('success', 'Feature removed from user successfully!');
    }

    /**
     * Delete a feature completely (from features table and pivot)
     */
public function deleteFeature($id)
{
    DB::beginTransaction();

    try {
        $feature = Feature::findOrFail($id);

        // Detach from all users
        $feature->users()->detach();

        // Delete feature
        $feature->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Feature deleted successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete feature.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
