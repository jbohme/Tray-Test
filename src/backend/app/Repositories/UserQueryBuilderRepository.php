<?php

namespace App\Repositories;

use App\Entities\Types\CPF;
use App\Entities\Types\Email;
use App\Entities\Types\RegistrationStatus;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserQueryBuilderRepository implements UserRepositoryInterface
{
    public function create(User $user): void
    {
        try {
            DB::beginTransaction();
            $date = Carbon::now();

            DB::table('users')->insert([
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
                'birthdate' => $user->getBirthdate(),
                'registration_status' => $user->getStatus()->value,
                'cpf' => $user->getCpf(),
                'access_token' => $user->getAccessToken(),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function update(User $user): void
    {
        try {
            DB::beginTransaction();

            DB::table('users')
                ->where('email', $user->getEmail()->getValue())
                ->update([
                    'name' => $user->getName(),
                    'birthdate' => $user->getBirthdate(),
                    'registration_status' => $user->getStatus()->value,
                    'cpf' => $user->getCpf()->getValue(),
                    'updated_at' => Carbon::now(),
                ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function findByEmail(Email $email): ?User
    {
        $row = DB::table('users')->where('email', $email->getValue())->first();

        if (is_null($row)) {
            return null;
        }

        return new User(
            id: $row->id,
            email: new Email($row->email),
            status: RegistrationStatus::tryFrom($row->registration_status),
            accessToken: $row->access_token,
            name: $row->name,
            cpf: ! is_null($row->cpf) ? new CPF($row->cpf) : null,
            birthdate: new \DateTime($row->birthdate)
        );
    }

    public function list(?string $filterCPF = null, ?string $filterName = null, int $perPage = 10): array
    {
        $query = DB::table('users');

        if (! is_null($filterCPF)) {
            $query->where('cpf', $filterCPF);
        }

        if (! is_null($filterName)) {
            $query->where('name', 'like', '%'.$filterName.'%');
        }

        $paginatedUsers = $query->paginate($perPage);

        $users = $paginatedUsers->getCollection()->map(function ($userData) {
            return new User(
                id: $userData->id,
                email: new Email($userData->email),
                status: RegistrationStatus::from($userData->registration_status),
                accessToken: $userData->access_token,
                name: $userData->name,
                cpf: ! is_null($userData->cpf) ? new CPF($userData->cpf) : null,
                birthdate: new \DateTime($userData->birthdate)
            );
        });

        return [
            'data' => $users,
            'pagination' => [
                'current_page' => $paginatedUsers->currentPage(),
                'per_page' => $paginatedUsers->perPage(),
                'total' => $paginatedUsers->total(),
                'last_page' => $paginatedUsers->lastPage(),
                'next_page_url' => $paginatedUsers->nextPageUrl(),
                'prev_page_url' => $paginatedUsers->previousPageUrl(),
            ],
        ];
    }

    public function findByID(string $id): ?User
    {
        $row = DB::table('users')->where('id', $id)->first();

        if (is_null($row)) {
            return null;
        }

        return new User(
            id: $row->id,
            email: new Email($row->email),
            status: RegistrationStatus::tryFrom($row->registration_status),
            accessToken: $row->access_token,
            name: $row->name,
            cpf: ! is_null($row->cpf) ? new CPF($row->cpf) : null,
            birthdate: new \DateTime($row->birthdate)
        );
    }
}
