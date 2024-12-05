<?php

namespace Database\Seeders;

use App\Models\CompostProducer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\RestaurantOwner;
use App\Models\Farmer;
use Faker\Factory as Faker;
class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            Subscription::create([
                'SubscriberID' => RestaurantOwner::inRandomOrder()->first()->user_id,
                'ProviderID' => Farmer::inRandomOrder()->first()->user_id,
                'SubscriptionType' => $faker->randomElement(['Monthly', 'Annual']),
                'StartDate' => $faker->date(),
                'EndDate' => $faker->date(),
                'Status' => $faker->randomElement(['Active', 'Expired']),
                'Reason' => $faker->sentence,
                'Price' => $faker->numberBetween(100, 1000),
                'PointEarned' => $faker->numberBetween(10, 50)
            ]);
        }
        foreach (range(21, 30) as $index) {
            Subscription::create([
                'SubscriberID' => CompostProducer::inRandomOrder()->first()->user_id,
                'ProviderID' => RestaurantOwner::inRandomOrder()->first()->user_id,
                'SubscriptionType' => $faker->randomElement(['Monthly', 'Annual']),
                'StartDate' => $faker->date(),
                'EndDate' => $faker->date(),
                'Status' => $faker->randomElement(['Active', 'Expired', 'Postponed']),
                'Reason' => $faker->sentence,
                'Price' => $faker->numberBetween(100, 1000),
                'PointEarned' => $faker->numberBetween(10, 50)
            ]);
        }
    }
}
