<?php

namespace App\Http\Controllers;

use App\Entities\Types\CPF;
use App\Http\Requests\AdditionalUserRegistrationRequest;
use App\Services\ListUsers\ListUserInputDTO;
use App\Services\ListUsers\ListUsersService;
use App\Services\OAuth\GoogleOAuthService;
use App\Services\UserRegistration\AdditionalUserRegistrationInputDTO;
use App\Services\UserRegistration\AdditionalUserRegistrationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        GoogleOAuthService $googleOAuthService,
        private ListUsersService $listUsersService,
        private AdditionalUserRegistrationService $additionalUserRegistrationService,
    ) {}

    public function listUsers(Request $request)
    {
        $filterCPF = $request->query('cpf') ?? null;
        $filterName = $request->query('name') ?? null;

        $inputDTO = new ListUserInputDTO(
            name: $filterName,
            cpf: $filterCPF,
        );

        $users = $this->listUsersService->execute($inputDTO);

        return response()->json($users);
    }

    public function update(AdditionalUserRegistrationRequest $request): JsonResponse
    {
        try {
            $this->additionalUserRegistrationService->execute(
                new AdditionalUserRegistrationInputDTO(
                    id: $request->get('user_id'),
                    name: $request->get('name'),
                    cpf: new CPF($request->get('cpf')),
                    birthdate: \DateTime::createFromFormat('d/m/Y', $request->get('birthdate')),
                )
            );

            return response()->json([
                'message' => 'Usuário registrado com sucesso',
            ], 200);
        } catch (\DomainException $exception) {
            return response()->json(['message' => 'Usuário já cadastrado.'], 409);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Internal Server Error',
            ], 500);
        }

    }
}
