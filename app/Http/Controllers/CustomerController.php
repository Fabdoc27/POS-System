<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller {
    public function customerList( Request $request ) {
        $userId = $request->header( 'id' );
        return Customer::where( 'user_id', $userId )->get();
    }

    public function customerCreate( Request $request ) {
        $userId = $request->header( 'id' );

        return Customer::create( [
            'name'    => $request->input( 'name' ),
            'email'   => $request->input( 'email' ),
            'mobile'  => $request->input( 'mobile' ),
            'user_id' => $userId,
        ] );
    }

    public function customerById( Request $request ) {
        $customerId = $request->input( 'id' );
        $userId     = $request->header( 'id' );

        return Customer::where( 'id', $customerId )->where( 'user_id', $userId )->first();
    }

    public function customerUpdate( Request $request ) {
        $customerId = $request->input( 'id' );
        $userId     = $request->header( 'id' );

        return Customer::where( 'id', $customerId )->where( 'user_id', $userId )->update( [
            'name'   => $request->input( 'name' ),
            'email'  => $request->input( 'email' ),
            'mobile' => $request->input( 'mobile' ),
        ] );
    }

    public function customerDelete( Request $request ) {
        $customerId = $request->input( 'id' );
        $userId     = $request->header( 'id' );

        return Customer::where( 'id', $customerId )->where( 'user_id', $userId )->delete();
    }
}