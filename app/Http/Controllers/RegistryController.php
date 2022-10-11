<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Registry;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistryController extends Controller
{
    public function index()
    {
        return view('registry/registry-create');
    }

    public function create(Request $req)
    {
        $exists = Registry::where('name', $req->name)->count();

        if (!$exists > 0) {
            $unique_url = Str::slug($req->name) . '-' . uniqid();

            session(['unique_url' => $unique_url]);

            $registry = new Registry();
            $registry->user_id = Auth::user()->id;
            $registry->name = $req->name;
            $registry->baby_name = $req->babyname;
            $registry->birthdate = $req->date;
            $registry->password = $req->password;
            $registry->url = $unique_url;
            $registry->products = [];
            $registry->save();
        }

        $registry = Registry::where('name', $req->name)->first();
        $unique_url = $registry->url;

        return $this->productsList();
    }

    public function productsList()
    {
        $unique_url = session('unique_url');

        $registry = Registry::where('url', '=', $unique_url)->first();
        $products = Product::paginate(15);
        $products->withPath('/registry/products');
        $categories = Category::all();
        $arrayIds = [];

        return view('registry/registry-products', compact('products', 'categories', 'registry', 'arrayIds', 'unique_url'));
    }

    public function filter(Request $req)
    {
        $category_id = $req->category_id;
        $categories = Category::all();
        $products = Product::where('category_id', $category_id)->paginate(15);
        $products->withPath('/registry/products');
        $registry = Registry::where('id', $req->registry_id)->first();
        $unique_url = session('unique_url');
        
        return view('registry/registry-products-filtered', compact('products', 'categories', 'category_id', 'registry', 'unique_url'));
    }

    public function addProducts(Request $req)
    {
        $registry_url = session('unique_url');
        $registry = Registry::where('url', '=', $registry_url)->first();
        $product = Product::where('id', $req->product_id)->first();

        $registryProducts = $registry->products;
        $arrayIds = [];
        foreach ($registryProducts as $registryProduct) {
            $arrayIds[] = $registryProduct['product_id'];
        }

        if (in_array($product->id, $arrayIds)) return;

        $registryProducts[] = [
            'title' => $product->title,
            'price' => $product->price,
            'product_id' => $product->id,
            'image_src' => $product->image_src,
            'status' => 0
        ];

        $registry->products = $registryProducts;
        
        $registry->save();

        $products = Product::paginate(15);
        $products->withPath('/registry/products');
        $categories = Category::all();
        $category_id = $product->category_id;
        $unique_url = session('unique_url');

        return view('registry/registry-products', compact('products', 'categories', 'category_id', 'registry', 'arrayIds', 'unique_url'));
    }

    public function showSpecificRegistry(Request $req)
    {
        $registry = Registry::where("url", "=", $req->route('url'))->first();
        $products = $registry->products;
        
        return view('registry/registry-overview', compact('products', 'registry'));
    }

    public function checkPassword(Request $req)
    {
        $registry = Registry::where("url", "=", $req->route('url'))->first();
        $givenPassword = $req->password;

        // check if password matches with password registry
        if ($givenPassword == $registry->password) {
            return redirect('/registry/' . $req->route('url') . '/guest');
        } else {
            dd('Wrong password!');
        }
    }

    public function guestLogin(Request $req)
    {
        $url = $req->url;
        return view('registry/guest/registry-guest-login', compact('url'));
    }

    public function storeGuestProducts(Request $req)
    {
        $product = Product::findOrFail($req->product_id);

        Cart::session(1)->add(array(
            'id' => $product->id,
            'name' => $product->title,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ));

        return redirect()->back();
    }

    public function guestIndex(Request $req)
    {
        $url = $req->url;
        session(['registry_url'=> $url]);

        $registry = Registry::where("url", "=" ,$req->url)->first();
        $products = $registry->products;
        $cart = Cart::session(1);
        return view('registry/guest/registry-guest-overview', compact('registry', 'products', 'url', 'cart'));
    }

}
