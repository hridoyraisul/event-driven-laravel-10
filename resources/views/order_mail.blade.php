<h3>Congratulations! Order has been confirmed!</h3>
<br>

<p>Order Invoice: {{ $order->code }}</p>
<hr>
<strong>Shipping Address</strong>
<p>{{ optional($order->shipment)->address??'' }}</p>
<p>Phone: {{ optional($order->shipment)->phone??'' }}</p>
<p>Email: {{ optional($order->shipment)->email??'' }}</p>
<hr>
<strong>Order Details</strong>
<table style="border: aquamarine">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderItems??[] as $item)
            <tr>
                <td>{{ $item->product->name??'' }}</td>
                <td>{{ $item->quantity }} Unit</td>
                <td>{{ $item->price }} TK.</td>
                <td>{{ $item->total }} TK.</td>
            </tr>
        @endforeach
    </tbody>
</table>
<hr>
<p>Total: {{ $order->orderItems->sum('total')??00 }} BDT</p>
<br>
<p>Thank you for shopping with us!</p>
