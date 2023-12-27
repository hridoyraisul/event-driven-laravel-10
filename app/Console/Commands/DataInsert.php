<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DataInsert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = \Faker\Factory::create();
        $products = [];
        for ($i = 0; $i < 100; $i++) {
            $products[] = [
                'name' => $faker->sentence(3),
                'slug' => $faker->slug,
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 1, 100),
                'stock' => $faker->numberBetween(1, 100),
                'created_at' => now(),
            ];
        }
        \DB::table('products')->insert($products);

        $faker = \Faker\Factory::create();
        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make('123456'),
                'created_at' => now(),
            ];
        }
        \DB::table('users')->insert($users);

        Log::info('Data inserted successfully');

    }
}
