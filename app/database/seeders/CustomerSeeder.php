<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{

    public function run()
    {
        $customers = [
            ['name' => 'Christan', 'email' => 'christan@gmail.com', 'birthdate' => '2000-12-09'],
            ['name' => 'Brian', 'email' => 'brian@gmail.com', 'birthdate' => '1999-10-10'],
            ['name' => 'William', 'email' => 'william@gmail.com', 'birthdate' => '1998-08-12'],
            ['name' => 'Harry', 'email' => 'harry@gmail.com', 'birthdate' => '1999-01-12'],
            ['name' => 'Henry', 'email' => 'henry@gmail.com', 'birthdate' => '1995-02-17'],
            ['name' => 'Jenny', 'email' => 'jenny@gmail.com', 'birthdate' => '2001-03-15'],
            ['name' => 'Jessica', 'email' => 'jessica@gmail.com', 'birthdate' => '2001-06-12'],
            ['name' => 'Jennifer', 'email' => 'jennifer@gmail.com', 'birthdate' => '1999-08-12'],
            ['name' => 'Kevin', 'email' => 'kevin@gmail.com', 'birthdate' => '1996-11-12'],
            ['name' => 'James', 'email' => 'james@gmail.com', 'birthdate' => '1996-09-22'],
            ['name' => 'Maria', 'email' => 'maria@gmail.com', 'birthdate' => '1999-08-21'],
            ['name' => 'Sarah', 'email' => 'sarah@gmail.com', 'birthdate' => '1995-04-20'],
        ];        

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
