<?php

namespace App\Http\Controllers;

use App\Models\VersionControl;
use Illuminate\Http\Request;

class VersionControlController extends Controller
{
        public function index()
        {
            $versions = VersionControl::all();
            return view('pages.version_control', compact('versions'));
        }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:255',
            'updates' => 'required|string',
            'date_release' => 'required|date',
            'author' => 'required|string|max:255',
        ]);

        $version = VersionControl::create($validated);
        return response()->json($version, 201);
    }

    public function show($id)
    {
        $version = VersionControl::findOrFail($id);
        return response()->json($version);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'version' => 'sometimes|string|max:255',
            'updates' => 'sometimes|string',
            'date_release' => 'sometimes|date',
            'author' => 'sometimes|string|max:255',
        ]);

        $version = VersionControl::findOrFail($id);
        $version->update($validated);

        return response()->json($version);
    }

    public function destroy($id)
    {
        $version = VersionControl::findOrFail($id);
        $version->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
