<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function categoryList( Request $request ) {
        $userId = $request->header( 'id' );
        return Category::where( 'user_id', $userId )->get();
    }

    public function categoryCreate( Request $request ) {
        $userId = $request->header( 'id' );

        return Category::create( [
            'name'    => $request->input( 'name' ),
            'user_id' => $userId,
        ] );
    }

    public function CategoryById( Request $request ) {
        $category_id = $request->input( 'id' );
        $user_id     = $request->header( 'id' );
        return Category::where( 'id', $category_id )->where( 'user_id', $user_id )->first();
    }

    public function categoryUpdate( Request $request ) {
        $categoryId = $request->input( 'id' );
        $userId     = $request->header( 'id' );

        return Category::where( 'id', $categoryId )->where( 'user_id', $userId )->update( [
            'name' => $request->input( 'name' ),
        ] );
    }

    public function categoryDelete( Request $request ) {
        $categoryId = $request->input( 'id' );
        $userId     = $request->header( 'id' );

        return Category::where( 'id', $categoryId )->where( 'user_id', $userId )->delete();
    }
}