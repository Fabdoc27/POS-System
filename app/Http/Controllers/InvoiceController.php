<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller {
    public function invoiceList( Request $request ) {
        $userId = $request->header( 'id' );
        return Invoice::where( 'user_id', $userId )->with( 'customer' )->get();
    }

    public function invoiceCreate( Request $request ) {
        DB::beginTransaction();

        try {
            $userId     = $request->header( 'id' );
            $total      = $request->input( 'total' );
            $discount   = $request->input( 'discount' );
            $vat        = $request->input( 'vat' );
            $payable    = $request->input( 'payable' );
            $customerId = $request->input( 'customer_id' );

            $invoice = Invoice::create( [
                'total'       => $total,
                'discount'    => $discount,
                'vat'         => $vat,
                'payable'     => $payable,
                'user_id'     => $userId,
                'customer_id' => $customerId,
            ] );

            // for inserting invoice_id on InvoiceProduct table
            $invoiceId = $invoice->id;

            // product list for a single Invoice
            $products = $request->input( 'products' );

            foreach ( $products as $eachProduct ) {
                InvoiceProduct::create( [
                    'quantity'   => $eachProduct['quantity'],
                    'sale_price' => $eachProduct['sale_price'],
                    'product_id' => $eachProduct['product_id'],
                    'invoice_id' => $invoiceId,
                    'user_id'    => $userId,
                ] );
            }

            DB::commit();
            return 1;

        } catch ( Exception $e ) {
            DB::rollBack();
            return 0;
        }
    }

    public function invoiceDetails( Request $request ) {
        $userId = $request->header( 'id' );

        // customer details
        $customerDetails = Customer::where( 'user_id', $userId )
            ->where( 'id', $request->input( 'cust_id' ) )->first();

        // invoice details
        $invoiceDetails = Invoice::where( 'user_id', $userId )
            ->where( 'id', $request->input( 'inv_id' ) )->first();

        // product list of the particular invoice
        $invoiceProduct = InvoiceProduct::where( 'invoice_id', $request->input( 'inv_id' ) )
            ->where( 'user_id', $userId )->get();

        return array(
            'customer' => $customerDetails,
            'invoice'  => $invoiceDetails,
            'product'  => $invoiceProduct,
        );
    }

    public function invoiceDelete( Request $request ) {
        DB::beginTransaction();

        try {
            $userId = $request->header( 'id' );

            InvoiceProduct::where( 'invoice_id', $request->input( 'inv_id' ) )
                ->where( 'user_id', $userId )->delete();

            Invoice::where( 'id', $request->input( 'inv_id' ) )->delete();

            DB::commit();
            return 1;
        } catch ( Exception $e ) {
            DB::rollBack();
            return 0;
        }
    }
}