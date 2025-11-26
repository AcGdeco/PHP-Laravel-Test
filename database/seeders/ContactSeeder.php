<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $contacts = [];
        $usedEmails = [];
        $usedContacts = [];

        for ($i = 0; $i < 20; $i++) {
            // Generate unique email
            do {
                $email = $faker->unique()->safeEmail();
            } while (in_array($email, $usedEmails));
            $usedEmails[] = $email;

            // Generate unique 9-digit contact
            do {
                $contact = str_pad(random_int(100000000, 999999999), 9, '0', STR_PAD_LEFT);
            } while (in_array($contact, $usedContacts));
            $usedContacts[] = $contact;

            // Generate name with more than 5 characters
            $name = $faker->name();
            while (strlen($name) <= 5) {
                $name = $faker->name();
            }

            $contacts[] = [
                'name' => $name,
                'contact' => $contact,
                'email' => $email,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('contacts')->insert($contacts);
    }
}
