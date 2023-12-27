<?php

namespace App\Console\Commands;

use App\Http\Controllers\OrderController;
use App\Http\Requests\OrderCreateRequest;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Symfony\Component\Console\Helper\Table;

class OrderCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order create command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = \Faker\Factory::create();
        $fakeRequest = [
            'user_id' => $faker->numberBetween(1, 10),
            'products' => [
                [
                    'id' => $faker->numberBetween(1, 10),
                    'quantity' => $faker->numberBetween(2, 10)
                ],
                [
                    'id' => $faker->numberBetween(1, 10),
                    'quantity' => $faker->numberBetween(3, 10)
                ],
            ],
            'name' => $faker->name,
            'email' => $faker->email,
            'address' => $faker->address,
            'phone' => $faker->phoneNumber,
        ];

        $this->info('<bg=magenta>Creating order...');

//        $response = (new OrderController())->create(
//            new Request($fakeRequest)
//        );

        $response = (new OrderController())->create2(
            new OrderCreateRequest($fakeRequest)
        );

        $this->info('Execution time: ' . round(microtime(true) - LARAVEL_START, 2) . ' seconds');
        $this->info('Memory used: ' . round(memory_get_usage() / 1024 / 1024, 2) . ' MB');

        $this->newLine();
        if ($response->getStatusCode() !== 201) {
            $this->error('Order not created');
            return;
        }
        $this->alert('Order created successfully!!!!');
        $this->warn('<bg=cyan>Order code:');
        $this->info(json_decode($response->getContent())->order->code);
        $this->warn('<bg=cyan>Shipment details:');
        $this->info('Address: ' . json_decode($response->getContent())->order->shipment->address);
        $this->info('Phone: ' . json_decode($response->getContent())->order->shipment->phone);
        $this->info('Email: ' . json_decode($response->getContent())->order->shipment->email);
        $this->info('Shipment tracking number: ' . json_decode($response->getContent())->order->shipment->tracking_number);
        $this->warn('<bg=cyan>Order items:');
        $table = new Table($this->output);
        $table->setHeaders([
            'SL.','Product', 'Quantity', 'Price', 'Total'
        ]);
        foreach (json_decode($response->getContent())->order->order_items as $index => $item) {
            $table->addRow([
                $index+1,
                $item->product->name,
                $item->quantity.' Units',
                $item->price.' Tk',
                $item->total.' Tk',
            ]);
        }
        $table->render();
    }
}
