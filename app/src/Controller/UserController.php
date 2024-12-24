<?php

namespace App\Controller;

use App\App;
use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Address;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;

class UserController extends Controller
{    
    /**
     * Action GET : Page d'inscription
     *
     * @return void
     */
    public function signUp(): void
    {
        $view = new View( 'user:sign-up', auth_controller: AuthController::class );

        $data = [
            'title' => ''
        ];

        $view->render( $data );
    }
    
    /**
     * Action POST : Formulaire d'inscription
     *
     * @param  mixed $request Requête HTTP d'origine
     * @return void
     */
    public function subscribe( ServerRequest $request ): void
    {
        $future_user = new User( $request->getParsedBody() );

        $choosen_password =$future_user->getPassword();

        // Si le mot de passe existe, on le chiffre
        if( !is_null( $choosen_password ) ) {
            $future_user->setPassword( App::strHash( $choosen_password ) );
        }

        $user_created = RepoManager::getRM()->getUserRepo()->create( $future_user );

        if( is_null( $user_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/inscription' );
        }

        $this->redirect( '/connexion' );
    }
}