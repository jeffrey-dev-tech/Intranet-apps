<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function getData()
    {
        $data = Inventory::all(); // Adjust based on your table name/model
        return response()->json($data);
    }
}
