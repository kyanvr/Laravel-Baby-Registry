<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Registry;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Mollie\Laravel\Facades\Mollie;

class CheckoutController extends Controller
{
    public function index(Request $req)
    {
        $cart = Cart::session(1);
        $url = $req->url;
        
        return view('registry/guest/checkout', compact('cart', 'url'));
    }

    public function checkout(Request $req)
    {
        // get cart info
        $cart = Cart::session(1);
        $total = $cart->getTotal();

        $arrayIds = [];
        $arrayProducts = [];

        foreach($cart->getContent() as $item){
            $arrayIds[] = $item->id;
        }
        

        // search for id in registry
        $url = session('registry_url');
        $registry = Registry::where("url", "=", $url)->first();
        foreach($registry->products as $product){
            
            if (in_array($product['product_id'], $arrayIds)){
                $product['status'] = 1;
                $arrayProducts[] = $product;
            } else {
                $arrayProducts[] = $product;
            }
            
        }
        
        $registry->products = $arrayProducts;
        $registry->save();

        // create new order
        $order = new Order();
        $order->name = $req->name;
        $order->remarks = $req->remarks;
        $order->total = $total;
        $order->status = 'pending';

        $order->save();

        $webhookUrl = route('webhooks.mollie');
        if (App::environment('local')) {
            $webhookUrl = 'https://a8ff-2a02-a03f-e2fd-7600-99b-cf00-c3ab-cf50.eu.ngrok.io/webhooks/mollie';
        }

        Log::alert('Before Mollie checkout, total price is calculated');

        $total = number_format($total, 2);

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => $total // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => "Bestelling op " . date('d-m-Y h:i'),
            "redirectUrl" => route('checkout.success'),
            "webhookUrl" => $webhookUrl,
            "metadata" => [
                "order_id" => $order->id,
                "order_from" => $order->name,
                "remarks" => $order->remarks,
            ],
        ]);

        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function success()
    {
        $cart = Cart::session(1);
        return view('registry/guest/checkout-success', compact('cart'));
    }
}
