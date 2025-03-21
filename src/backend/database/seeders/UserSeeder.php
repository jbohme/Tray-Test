<?php

namespace Database\Seeders;

use App\Entities\Types\RegistrationStatus;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    const TOTAL_USERS = 150000;

    const BATCH_SIZE = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('pt_BR');

        for ($i = 0; $i < self::TOTAL_USERS; $i++) {
            $users = [];

            for ($j = 0; $j < self::BATCH_SIZE; $j++) {
                $users[] = [
                    'id' => Str::uuid()->toString(),
                    'name' => $faker->name(),
                    'birthdate' => $faker->date('Y-m-d', '-18 years'),
                    'cpf' => $faker->unique()->cpf(),
                    'email' => uniqid($i).'@gmail.com',
                    'registration_status' => RegistrationStatus::Complete->value,
                    'access_token' => Str::random(60),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            try {
                DB::table('users')->insert($users);
                unset($users);
            } catch (\Exception $exception) {
            }

            if (self::TOTAL_USERS < $total = DB::table('users')->count()) {
                echo 'Inseridos '.($total)." usuários...\n";
                break;
            }

        }

        echo 'Finalizado!';
    }
}
