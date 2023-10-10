<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = !blank(request()->limit) ?  request()->limit : 10;

        $products = Product::latest()->paginate($limit);

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => ProductResource::Collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
            'message' => 'Product(s) retrieved successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::where('id', $id)->first();

        if( blank($product) ) {
            throw new \Exception('Product not found with associated id');
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => new ProductResource($product),
            'message' => 'Product retrieved successfully'
        ], Response::HTTP_OK);
    }

}
