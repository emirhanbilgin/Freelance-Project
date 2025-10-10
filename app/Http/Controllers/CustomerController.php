<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('receipts')
            ->with(['receipts' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('name')
            ->get();
            
        return view('customers.index', compact('customers'));
    }

    public function receipts($id)
    {
        $customer = Customer::with(['receipts' => function($query) {
            $query->with('items')->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return view('customers.receipts', compact('customer'));
    }

    // Müşteri arama (autocomplete için)
    public function search(Request $request)
    {
        $q = (string) $request->get('q', '');
        if (trim($q) === '') {
            return response()->json([]);
        }

        $customers = Customer::query()
            ->where('name', 'like', '%' . $q . '%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($customers);
    }
}

