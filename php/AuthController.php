<?php
// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose.

namespace App\Http\Controllers;

use App\LoginAttempt;
use Illuminate\Http\Request;
use Aacotroneo\Saml2\Saml2Auth;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Http\Controllers\Saml2Controller;

class AuthController extends Saml2Controller
{
    /**
     * Unauthenticated if you don't know where to be
     */
    public function index()
    {
        abort(403);
    }

    /**
     * Login as a teacher
     */
    public function teacher()
    {
        // Load Saml2Auth
        $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig('kennisnet'));

        // Fire a login request to Entree with as redirect the docent page
        return $saml2Auth->login(route('redirect.docent'));
    }

    /**
     * Login as a student
     */
    public function student()
    {
        // Load Saml2Auth
        $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig('kennisnet'));

        // Fire a login request to Entree with as redirect the leerling page
        return $saml2Auth->login(route('redirect.leerling'));
    }

    /**
     * Callback from Entree
     * @param Saml2Auth $saml2Auth
     * @param string $idpName
     */
    public function acs(Saml2Auth $saml2Auth, $idpName)
    {
        // Save an login attempt
        $attempt = new LoginAttempt();

        // Check if we have found errors
        $errors = $saml2Auth->acs();
        if (!empty($errors)) {
            $attempt->data = [
                'saml2_error_detail' => $saml2Auth->getLastErrorReason(),
                'saml2_error' => $errors
            ];

            // Save the attempt with 'failed'
            $attempt->status = 'failed';
            $attempt->save();

            return view('error', [
                'message' => 'Er ging helaas iets mis. Neem contact met ons op.'
            ]);
        }

        // Get the user
        $user = $saml2Auth->getSaml2User();

        // Fire an success event
        event(new Saml2LoginEvent($idpName, $user, $saml2Auth));

        // Set attempt successes
        $attempt->status = 'success';
        $attempt->data = [
            'messageId' => $saml2Auth->getLastMessageId(),
            'attributes' => $user->getAttributes(),
        ];

        // Set the account type
        if ($user->getIntendedUrl() == route('redirect.docent')) {
            $attempt->account_type = 'teacher';
        } else {
            $attempt->account_type = 'student';
        }

        // Save the attempt
        $attempt->save();

        // Handle the attempt
        return $attempt->login();
    }
}