<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $userId = $request->header('id');
        $fromDate = date('Y-m-d', strtotime($request->fromDate));
        $toDate = date('Y-m-d', strtotime($request->toDate));

        $total = Invoice::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('total');

        $vat = Invoice::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('vat');

        $payable = Invoice::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('payable');

        $discount = Invoice::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)->sum('discount');

        $customerList = Invoice::where('user_id', $userId)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->with('customer')->get();

        $data = [
            'total' => $total,
            'vat' => $vat,
            'payable' => $payable,
            'discount' => $discount,
            'customerList' => $customerList,
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
        ];

        $pdf = Pdf::loadView('report.salesReport', $data);

        return $pdf->download('invoice.pdf');
    }
}
