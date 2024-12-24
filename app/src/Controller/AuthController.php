<?php

namespace App\Controller;

use App\App;
use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Session;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;

/**
 * Gestion des fonctionnalités d'authentification
 */
class AuthController extends Controller
{
    /**
     * Obtient l'utilisateur connecté pour cette session
     *
     * @return User|null Utilisateur connecté
     */
    public static function getUser(): ?User
    {
        if( !self::isAuth() ) {
            return null;
        }
    
        return Session::get( Session::USER );
    }
    
    /**
     * L'utilisateur est connecté
     *
     * @return bool
     */ 
    public static function isAuth(): bool
    {
        return !is_null( self::getUser() );
    }    

    /**
     * L'utilisateur est connecté avec un rôle propriétaire
     *
     * @return bool
     */ 
    public static function isOwner(): bool
    {
        if( !self::isAuth() ) {
            return false;
        }    
        
        return self::getUser()->getRole() === User::ROLE_OWNER;
    }    

    /**
     * Action GET : Page d'authentification
     *
     * @return void
     */
    public function signIn(): void
    {
        $view = new View( 'auth:sign-in', auth_controller: self::class );

        $data = [
            'title' => ''
        ];

        $view->render( $data );
    }
    
    /**
     * Action POST : Formulaire d'authentification
     *
     * @param  mixed $request Requête HTTP d'origine 
     * @return void
     */
    public function checkCredentials( ServerRequest $request ): void
    {
        $form_data = $request->getParsedBody();

        // Si les données du formulaire sont vides ou inexistantes
        if( empty( $form_data['email'] ) || empty( $form_data['password'] ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/sign-in' );
        }

        // On nettoie les espaces en trop
        $email = trim( $form_data['email'] );
        $password = trim( $form_data['password'] );

        // Si les données sont vides après nettoyage
        if( empty( $email ) || empty( $password ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/sign-in' );
        }

        // Chiffrement du mot de passe
        $password = App::strHash( $password );

        // On vérifie les identifiants de connexion
        $user = RepoManager::getRM()->getUserRepo()->checkAuth( $email, $password );

        // Si échec
        if( is_null( $user ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/sign-in' );
        }

        // On enregistre l'utilisateur correspondant dans la session
        Session::set( Session::USER, $user );

        // On redirige vers une page en fonction du rôle de l'utilisateur
        $redirect_url = match( $user->getRole() ) {
            User::ROLE_CUSTOMER => '/',
            User::ROLE_OWNER => '/proprietaire/tableau-de-bord'
        };

        $this->redirect( $redirect_url );
    }

    /**
     * Action GET : Déconnexion de l'utilisateur
     *
     * @return void
     */
    public function signOut(): void
    {
        Session::remove( Session::USER );
        $this->redirect( '/' );
    }
}