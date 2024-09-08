<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function productList(Request $request)
    {
        $userId = $request->header('id');

        return Product::where('user_id', $userId)->get();
    }

    public function productCreate(Request $request)
    {
        $userId = $request->header('id');

        // Image name
        $img = $request->file('img');

        // Image path
        $t = time();
        $fileName = $img->getClientOriginalName();
        $imgName = "{$userId}-{$t}-{$fileName}";
        $imgUrl = "uploads/{$imgName}";

        // Image upload
        $img->move(public_path('uploads'), $imgName);

        // Save to Database
        return Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'img_url' => $imgUrl,
            'category_id' => $request->input('category_id'),
            'user_id' => $userId,
        ]);
    }

    public function productById(Request $request)
    {
        $userId = $request->header('id');
        $productId = $request->input('id');

        return Product::where('id', $productId)->where('user_id', $userId)->first();
    }

    public function productDelete(Request $request)
    {
        $userId = $request->header('id');
        $productId = $request->input('id');
        $filePath = $request->input('file_path');
        File::delete($filePath);

        return Product::where('id', $productId)->where('user_id', $userId)->delete();
    }

    public function productUpdate(Request $request)
    {
        $userId = $request->header('id');
        $productId = $request->input('id');

        if ($request->hasFile('img')) {
            // Upload image
            $img = $request->file('img');
            $t = time();
            $fileName = $img->getClientOriginalName();
            $imgName = "{$userId}-{$t}-{$fileName}";
            $imgUrl = "uploads/{$imgName}";
            $img->move(public_path('uploads'), $imgName);

            // Delete old image
            $filePath = $request->input('file_path');
            File::delete($filePath);

            // Update product
            return Product::where('id', $productId)->where('user_id', $userId)->update([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'img_url' => $imgUrl,
                'category_id' => $request->input('category_id'),
            ]);
        } else {
            return Product::where('id', $productId)->where('user_id', $userId)->update([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'category_id' => $request->input('category_id'),
            ]);
        }
    }
}
