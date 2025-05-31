<?php

namespace Database\Seeders;

use App\Models\Items;
use App\Models\Sites;
use App\Models\Types;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Warehouse Administrator',
            'email' => 'warehouse@example.com',
            'role' => 'warehouse_admin',
        ]);

        User::factory()->create([
            'name' => 'Office Administrator',
            'email' => 'office@example.com',
            'role' => 'office_admin',
        ]);

        // Tehnicians

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'role' => 'technician',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'role' => 'technician',
        ]);

        User::factory()->create([
            'name' => 'Mike Johnson',
            'email' => 'mikejohnson@example.com',
            'role' => 'technician',
        ]);

        // Employees

        User::factory()->create([
            'name' => 'Mark Jason Espelita',
            'email' => 'markjasonespelita@gmail.com',
            'role' => 'employee',
        ]);

        User::factory()->create([
            'name' => 'Eljohn Gordo Razos',
            'email' => 'mark,medivadigital@gmail.com',
            'role' => 'employee',
        ]);

        User::factory()->create([
            'name' => 'Jhyryle Brint Razos Espelita',
            'email' => 'mjespelita.swashed@gmail.com',
            'role' => 'employee',
        ]);
        
        // Sites
        
        $faker = Faker::create();

        // Create multiple sites with a valid phone number
        Sites::create([
            'name' => 'Allen Central Elementary School',
            'phonenumber' => $faker->numerify('09#########'), // Generates a phone number with '09' followed by 9 digits
            'google_map_link' => 'https://www.google.com/maps/embed/v1/place?q=London&key=AIzaSyBJyFU3OF64Fn1tPHkP37DifH4V0uhuU8w', // Example google_map_link
            'users_id' => 1
        ]);

        // Create types
        Types::create(['name' => 'Router']);
        Types::create(['name' => 'Hub']);
        Types::create(['name' => 'Switch']);
        Types::create(['name' => 'Modem']);
        Types::create(['name' => 'Access Point']);
        Types::create(['name' => 'Repeater']);
        Types::create(['name' => 'Firewall']);
        Types::create(['name' => 'Bridge']);
        Types::create(['name' => 'Load Balancer']);
        Types::create(['name' => 'Gateway']);
        Types::create(['name' => 'LAN CABLE']);

        // Fetch all Types (these are already seeded)
        $types = Types::all();

        // Function to generate serial numbers with a prefix and random 5-digit number
        function generateSerialNumbers($name, $count) {
            $faker = Faker::create();
            $prefix = strtoupper(preg_replace('/[^a-zA-Z0-9]+/', '', $name)); // Remove non-alphanumeric characters and make it uppercase
            $serialNumbers = [];
            
            for ($i = 0; $i < $count; $i++) {
                $serialNumbers[] = $prefix . '_' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);  // Generate a serial number with the prefix and 5 digits
            }

            return implode(', ', $serialNumbers);  // Return the serial numbers separated by commas
        }

        // Create items with random serial numbers and quantities
        $itemsData = [
            [
                'itemId' => 'ITEM0002',
                'name' => 'D-Link Router',
                'model' => 'DIR-615',
                'brand' => 'D-Link',
                'types_id' => $types->where('name', 'Router')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 3, // For example, we want 3 serial numbers
                'serial_numbers' => generateSerialNumbers('D-Link', 3),  // Generate 3 serial numbers with 'DLINK_' prefix
            ],
            [
                'itemId' => 'ITEM0003',
                'name' => 'Netgear Hub',
                'model' => 'JGS524Ev2',
                'brand' => 'Netgear',
                'types_id' => $types->where('name', 'Hub')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 2, // 2 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('Netgear', 2),
            ],
            [
                'itemId' => 'ITEM0004',
                'name' => 'Cisco Switch',
                'model' => '2960X',
                'brand' => 'Cisco',
                'types_id' => $types->where('name', 'Switch')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 5, // 5 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('Cisco', 5),
            ],
            [
                'itemId' => 'ITEM0005',
                'name' => 'TP-Link Modem',
                'model' => 'TD-W8960N',
                'brand' => 'TP-Link',
                'types_id' => $types->where('name', 'Modem')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 4, // 4 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('TP-Link', 4),
            ],
            [
                'itemId' => 'ITEM0006',
                'name' => 'Wireless Access Point',
                'model' => 'TL-WA801ND',
                'brand' => 'TP-Link',
                'types_id' => $types->where('name', 'Access Point')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 3, // 3 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('TP-Link', 3),
            ],
            [
                'itemId' => 'ITEM0007',
                'name' => 'TP-Link Repeater',
                'model' => 'RE200',
                'brand' => 'TP-Link',
                'types_id' => $types->where('name', 'Repeater')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 1, // 1 serial number for this item
                'serial_numbers' => generateSerialNumbers('TP-Link', 1),
            ],
            [
                'itemId' => 'ITEM0008',
                'name' => 'Fortinet Firewall',
                'model' => 'FortiGate 60E',
                'brand' => 'Fortinet',
                'types_id' => $types->where('name', 'Firewall')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 2, // 2 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('Fortinet', 2),
            ],
            [
                'itemId' => 'ITEM0009',
                'name' => 'TP-Link Bridge',
                'model' => 'TL-WA5210G',
                'brand' => 'TP-Link',
                'types_id' => $types->where('name', 'Bridge')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 4, // 4 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('TP-Link', 4),
            ],
            [
                'itemId' => 'ITEM0010',
                'name' => 'Cisco Load Balancer',
                'model' => 'ACE 4710',
                'brand' => 'Cisco',
                'types_id' => $types->where('name', 'Load Balancer')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 2, // 2 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('Cisco', 2),
            ],
            [
                'itemId' => 'ITEM0011',
                'name' => 'Ubiquiti Gateway',
                'model' => 'USG-PRO-4',
                'brand' => 'Ubiquiti',
                'types_id' => $types->where('name', 'Gateway')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'piece',
                'quantity' => 5, // 5 serial numbers for this item
                'serial_numbers' => generateSerialNumbers('Ubiquiti', 5),
            ],
            [
                'itemId' => 'ITEM0012',
                'name' => 'LAN Cable',
                'model' => 'Cat5e',
                'brand' => 'Generic',
                'types_id' => $types->where('name', 'LAN CABLE')->first()->id,
                'description' => $faker->sentence,
                'unit' => 'meter',
                'quantity' => 0, // LAN Cable does not have serial numbers
                'serial_numbers' => null, // No serial numbers for LAN Cable
            ]
        ];

        // Create the items
        foreach ($itemsData as $item) {
            Items::create($item);
        }


    }
}
