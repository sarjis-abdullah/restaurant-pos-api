<?php

namespace Database\Seeders;

use App\Enums\RolesAndPermissions;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $pass = '@Test123';
        $this->createUser(
            'SA|Sarjis',  'sarjis.m.a@gmail.com', $pass, RolesAndPermissions::SUPER_ADMIN
        );
        $this->createUser(
            'SA|',  'superadmin@gmail.com', $pass, RolesAndPermissions::SUPER_ADMIN
        );
        $this->createUser(
            'A|Admin',  'admin@gmail.com', $pass, RolesAndPermissions::ADMIN
        );
        $this->createUser(
            'C|Customer', 'customer@gmail.com', $pass, RolesAndPermissions::CUSTOMER
        );
        $this->createUser(
            'W|Waiter', 'waiter@gmail.com', $pass, RolesAndPermissions::WAITER
        );
        $this->createUser(
            'CH|Chef', 'chef@gmail.com', $pass, RolesAndPermissions::CHEF
        );
        $this->createUser(
            'M|Manager', 'manager@gmail.com', $pass, RolesAndPermissions::MANAGER
        );
        $this->createUser(
            'CA|cashier', 'cashier@gmail.com', $pass, RolesAndPermissions::CASHIER
        );
    }


    public function createUser(string $name, string $email, string $password, ...$roles)
    {
        $this->createUserWith(
            collect([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make(
                    $password
                ),
            ]),
            collect(
                $roles
            )
        );
    }

    private function createUserWith(Collection $data, Collection $roles): User
    {
        // IMPORTANT: Business|Private Customer & Home Buyers start also with a role of an Investor and Customer
//        if ($roles->contains(RolesAndPermissions::BUSINESS_CUSTOMER) || $roles->contains(
//                RolesAndPermissions::PRIVATE_CUSTOMER
//            ) || $roles->contains(RolesAndPermissions::HOME_BUYER))
//            $roles->push(RolesAndPermissions::INVESTOR, RolesAndPermissions::CUSTOMER);
//        else
//        $roles->push(RolesAndPermissions::EMPLOYEE);


        $user = User::create($data->toArray());

        $user->assignRole($roles->toArray());

        return $user;
    }
}
