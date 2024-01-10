<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OtpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {
    public function userRegistration( Request $request ) {
        try {
            $request->validate( [
                'firstName' => 'required|string|max:50',
                'lastName'  => 'required|string|max:50',
                'email'     => 'required|email|unique:users,email|max:50',
                'mobile'    => 'required|string|max:50',
                'password'  => 'required|string|min:6',
            ] );

            $user = User::create( [
                'firstName' => $request->input( 'firstName' ),
                'lastName'  => $request->input( 'lastName' ),
                'email'     => $request->input( 'email' ),
                'mobile'    => $request->input( 'mobile' ),
                'password'  => Hash::make( $request->input( 'password' ) ),
            ] );

            // issuing token for login
            $token = JWTToken::createToken( $request->input( 'email' ), $user->id );

            return response()->json( [
                "status"  => "success",
                "message" => "User Registration Successful",
                "user"    => $user,
            ], 200 )->cookie( 'token', $token, 60 * 60 );

        } catch ( Exception $e ) {
            return response()->json( [
                "status"  => "failed",
                "message" => $e->getMessage(),
            ], 200 );
        }
    }

    public function userLogin( Request $request ) {
        try {
            $request->validate( [
                'email'    => 'required|email',
                'password' => 'required|min:6',
            ] );

            $user = User::where( 'email', $request->input( 'email' ) )->first();

            if ( $user !== null && Hash::check( $request->input( 'password' ), $user->password ) ) {
                // user login -> jwt token issue
                $token = JWTToken::createToken( $request->input( 'email' ), $user->id );

                return response()->json( [
                    "status"  => "success",
                    "message" => "User Login Successful",
                ], 200 )->cookie( 'token', $token, 60 * 60 );
            }

        } catch ( Exception $e ) {
            return response()->json( [
                "status"  => "failed",
                "message" => $e->getMessage(),
            ], 200 );
        }
    }

    public function sendOtp( Request $request ) {
        try {
            $request->validate( [
                'email' => 'required|email',
            ] );

            $email = $request->input( 'email' );
            $otp   = rand( 1000, 9999 );
            $count = User::where( 'email', '=', $email )->count();

            if ( $count == 1 ) {
                // otp
                Mail::to( $email )->send( new OtpMail( $otp ) );

                // otp update
                User::where( 'email', '=', $email )->update( ['otp' => $otp] );

                return response()->json( [
                    "status"  => "success",
                    "message" => "4 Digits Otp Code has been Send to your Email.",
                ], 200 );
            } else {
                return response()->json( [
                    "status"  => "failed",
                    "message" => "Invalid Email",
                ], 200 );
            }

        } catch ( Exception $e ) {
            return response()->json( [
                "status"  => "failed",
                "message" => $e->getMessage(),
            ] );
        }
    }

    public function verifyOtp( Request $request ) {
        try {
            $request->validate( [
                'otp' => 'required|digits:4',
            ] );

            $email = $request->input( 'email' );
            $otp   = $request->input( 'otp' );
            $count = User::where( 'email', '=', $email )
                ->where( 'otp', '=', $otp )
                ->select( 'id' )->first();

            if ( $count !== null ) {
                // otp update
                User::where( 'email', '=', $email )->update( ['otp' => '0'] );

                // token issue for reset password
                $token = JWTToken::resetPassword( $email, $count->id );

                return response()->json( [
                    "status"  => "success",
                    "message" => "OTP Verification Successful.",
                ], 200 )->cookie( 'token', $token, 60 * 10 );
            } else {
                return response()->json( [
                    "status"  => "failed",
                    "message" => "Invalid Otp",
                ], 200 );
            }

        } catch ( Exception $e ) {
            return response()->json( [
                "status"  => "failed",
                "message" => $e->getMessage(),
            ] );
        }
    }

    public function passwordReset( Request $request ) {
        try {
            $request->validate( [
                'password' => 'required|min:6',
            ] );

            $email    = $request->header( 'email' );
            $password = $request->input( 'password' );
            User::where( 'email', '=', $email )->update( ['password' => $password] );

            return response()->json( [
                "status"  => "success",
                "message" => "Password Reset Successful.",
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                "status"  => "failed",
                "message" => "Something Went Wrong.",
            ], 200 );
        }
    }

    public function userLogout() {
        return redirect( '/userLogin' )->cookie( 'token', '', -1 );
    }

    public function userProfile( Request $request ) {
        $email = $request->header( 'email' );
        $user  = User::where( 'email', '=', $email )->first();

        return response()->json( [
            'status'  => 'success',
            'message' => 'Request Successful',
            'data'    => $user,
        ] );
    }

    public function updateProfile( Request $request ) {
        try {
            $request->validate( [
                'firstName' => 'required|string|max:50',
                'lastName'  => 'required|string|max:50',
                'mobile'    => 'required|string|max:50',
                'password'  => 'nullable|string|min:6',
            ] );

            $email = $request->header( 'email' );

            User::where( 'email', '=', $email )->update( [
                'firstName' => $request->input( 'firstName' ),
                'lastName'  => $request->input( 'lastName' ),
                'mobile'    => $request->input( 'mobile' ),
                'password'  => $request->input( 'password' ),
            ] );

            return response()->json( [
                'status'  => 'success',
                'message' => 'Profile Update Successful',
            ], 200 );
        } catch ( Exception $e ) {
            return response()->json( [
                'status'  => 'failed',
                'message' => 'Something Went Wrong',
            ], 200 );
        }
    }
}