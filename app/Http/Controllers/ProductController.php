<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(5);
      
        return view('products.index',compact('products'))
                    ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product): View
    {

        $category = Category::all();
        return view('products.create', compact('category', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'detail' => 'required',
    //         'category_name' => 'required',
           
    //     ]);
      
    //     Product::create($request->all());
       
    //     return redirect()->route('products.index')
    //                     ->with('success','Product created successfully.');
    // }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'category_name' => 'required',
        ]);
    
        // Find the category by name
        $category = Category::where('title', $request->input('category_name'))->first();
    
        // Create the post and associate it with the category
        $product = new Product([
            'name' => $request->input('name'),
            'detail' => $request->input('detail'),
            'category_name' => $request->input('category_name'),
        ]);
    
        // If category is found, associate it with the post
        if ($category) {
            $product->category()->associate($category);
        }
    
        $product->save();
    
        return redirect()->route('products.index')
                        ->with('success', 'Product created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $category = Category::all();
        return view('products.edit',compact('product', 'category'));
    }
//  public function about(Request $request) 
//  {


//     $about = Product::where('category_name', 'about')->orderBy('created_at', 'desc')->first();

//     $contact = Product::where('category_name', 'contact')->first();
//     return view('about',compact('contact', 'about'));
//  }

public function about(Request $request)
{
    $aboutCategoryTitle = 'about';
    $contactCategoryTitle = 'contact';

    $about = Product::with('category')
        ->whereHas('category', function ($query) use ($aboutCategoryTitle) {
            $query->where('title', $aboutCategoryTitle);
        })
        ->orderBy('created_at', 'desc')
        ->first();

    $contact = Product::with('category')
        ->whereHas('category', function ($query) use ($contactCategoryTitle) {
            $query->where('title', $contactCategoryTitle);
        })
        ->first();

    return view('about', compact('contact', 'about'));
}


    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Product $product): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => 'nullable',
    //         'detail' => 'nullable',
    //         'category_name' => 'nullable',
           
    //     ]);
      
    //     $product->update($request->all());
      
    //     return redirect()->route('products.index')
    //                     ->with('success','Product updated successfully');
    // }
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'nullable',
            'detail' => 'nullable',
            'category_name' => 'nullable',
        ]);
    
        // Find the product by ID
        $product = Product::find($id);
    
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
    
        // Find the category by name
        $category = Category::where('title', $request->input('category_name'))->first();
    
        // Update the product properties
        $product->name = $request->input('name');
        $product->detail = $request->input('detail');
        $product->category_name = $request->input('category_name');
    
        // If category is found, associate it with the product
        if ($category) {
            $product->category()->associate($category);
        }
    
        $product->save();
    
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
       
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}
