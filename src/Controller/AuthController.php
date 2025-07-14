<?php

require_once __DIR__ . '/../Service/ExternalApiService.php';
require_once __DIR__ . '/../Utils/Response.php';

class AuthController
{
    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['username'], $input['password'])) {
            Response::json(['error' => 'Missing credentials'], 400);
        }

        $externalService = new ExternalApiService();
        $token = $externalService->login($input['username'], $input['password']);

        if ($token) {
            Response::json(['token' => $token]);
        } else {
            Response::json(['error' => 'Pogrešno korisničko ime ili lozinka. Pokušajte ponovo.'], 401);
        }
    }
}
