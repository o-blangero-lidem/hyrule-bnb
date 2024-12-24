<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class PageController extends Controller
{
    // Page d'accueil
    public function index(): void
    {
        $view = new View( 'page:home', auth_controller: AuthController::class );

        $data = [
            'title' => 'Hyrule BnB'
        ];

        $view->render( $data );
    }
}