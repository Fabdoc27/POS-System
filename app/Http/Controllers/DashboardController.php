<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $userId = $request->header('id');
        $product = Product::where('user_id', $userId)->count();
        $category = Category::where('user_id', $userId)->count();
        $customer = Customer::where('user_id', $userId)->count();
        $invoice = Invoice::where('user_id', $userId)->count();
        $total = Invoice::where('user_id', $userId)->sum('total');
        $vat = Invoice::where('user_id', $userId)->sum('vat');
        $payable = Invoice::where('user_id', $userId)->sum('payable');

        return [
            'product' => $product,
            'category' => $category,
            'customer' => $customer,
            'invoice' => $invoice,
            'total' => round($total, 2),
            'vat' => round($vat, 2),
            'payable' => round($payable, 2),
        ];
    }
}
