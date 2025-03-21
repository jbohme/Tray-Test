<?php

namespace App\Http\Controllers;

use App\Entities\Types\Email;
use App\Entities\Types\RegistrationStatus;
use App\Services\JWT\AuthUser;
use App\Services\OAuth\GoogleOAuthService;
use App\Services\UserRegistration\FirstUserRegistrationInputDTO;
use App\Services\UserRegistration\FirstUserRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GoogleOAuthController extends Controller
{
    private GoogleOAuthService $googleOAuthService;

    public function __construct(
        GoogleOAuthService $googleOAuthService,
        private FirstUserRegistrationService $firstUserRegistrationService,
    ) {
        $this->googleOAuthService = $googleOAuthService;
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleOAuthService->getAuthUrl();

        return Redirect::to($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $code = $request->get('code');
            $userData = $this->googleOAuthService->authenticate($code);

            $output = $this->firstUserRegistrationService->execute(
                new FirstUserRegistrationInputDTO(
                    email: new Email($userData['email']),
                    accessToken: $this->googleOAuthService->getAccessToken()
                )
            );

            $authUser = new AuthUser(
                id: $output->getId(),
                email: $output->getEmail()->getValue(),
                name: $output->getName()
            );
            $jwtToken = $authUser->getToken();

            if ($output->getRegistrationStatus() == RegistrationStatus::Pending) {
                $redirectTo = 'finalizar-cadastro';
            } elseif ($output->getRegistrationStatus() == RegistrationStatus::Complete) {
                $redirectTo = 'usuarios';
            }

            return Redirect::to(config('app.frontend_url')."?token=$jwtToken&redirect=$redirectTo");
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha na autenticação',
            ], 401);
        }
    }
}
