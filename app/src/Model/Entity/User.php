<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;

use App\Model\Repository\RepoManager;

/**
 * Entité représentant un utilisateur
 */
class User extends Entity
{
    /**
     * Rôle client
     */
    public const ROLE_CUSTOMER = 1;
    
    /**
     * Rôle commercial
     */
    public const ROLE_OWNER = 2;

    /**
     * Colonne "Mot de passe"
     */
    protected string $password;
    public function getPassword(): string { return $this->password; }
    public function setPassword( string $value ): self
    {
        $this->password = $value;
        return $this;
    }
   
    /**
     * Colonne "email"
     */
    protected string $email;
    public function getEmail(): string { return $this->email; }
    public function setEmail( int $value ): self
    {
        $this->email = $value;
        return $this;
    }

    /**
     * Colonne "prénom"
     */
    protected string $firstname;
    public function getFirstname(): string { return $this->firstname; }
    public function setFirstname( int $value ): self
    {
        $this->firstname = $value;
        return $this;
    }

    /**
     * Colonne "nom"
     */
    protected string $lastname;
    public function getLastname(): string { return $this->lastname; }
    public function setLastname( int $value ): self
    {
        $this->lastname = $value;
        return $this;
    }

    /**
     * Colonne "numéro de téléphone"
     */
    protected string $phone_number;
    public function getPhoneNumber(): string { return $this->phone_number; }
    public function setPhoneNumber( int $value ): self
    {
        $this->phone_number = $value;
        return $this;
    }

    /**
     * Colonne "rôle"
     */
    protected int $role;
    public function getRole(): int { return $this->role; }
    public function setRole( int $value ): self
    {
        $this->role = $value;
        return $this;
    }
}