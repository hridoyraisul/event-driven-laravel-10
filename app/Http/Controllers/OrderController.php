<?php

namespace App\Http\Controllers;

use App\Events\OrderCreate;
use App\Http\Requests\OrderCreateRequest;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'name' => 'required|string',
                'email' => 'required|email',
                'address' => 'required|string',
                'phone' => 'required|string',
                'products' => 'required|array',
                'products.*.id' => 'required|integer|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ],422);
        }

        $products = Product::query()
            ->whereIn('id', array_column($request->products, 'id'))
            ->get();

        $order = Order::query()->create([
            'user_id' => $request->user_id,
            'code' => Str::uuid(),
        ]);

        $orderDetails = [];
        foreach ($products as $product) {
            $orderDetails[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->products[array_search($product->id, array_column($request->products, 'id'))]['quantity'],
                'price' => $product->price,
                'total' => $product->price * $request->products[array_search($product->id, array_column($request->products, 'id'))]['quantity'],
                'created_at' => now(),
            ];
        }
        OrderDetails::query()->insert($orderDetails);

        Shipment::query()->create([
            'order_id' => $order->id,
            'tracking_number' => Str::random(10),
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        foreach ($products as $product) {
            $product->stock -= $request->products[array_search($product->id, array_column($request->products, 'id'))]['quantity'];
            $product->save();
        }

        Mail::to($request->email)->send(new OrderConfirmationMail($order));


        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ],201);

    }

    public function create2(OrderCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $order = Order::query()->create([
            'user_id' => $attributes['user_id'],
            'code' => Str::uuid(),
        ]);
        OrderCreate::dispatch($order, $attributes);
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ],201);
    }
}
