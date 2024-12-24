<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\User;

class UserRepository extends Repository
{
    protected function getTableName(): string { return 'users'; }

    /* Crud: Create */
    public function create( User $user ): ?User
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`password`,`email`,`firstname`,`lastname`,`phone_number`,`role`) 
                VALUES (:password,:email,:firstname,:lastname,:phone_number,:role)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'phone_number' => $user->getPhoneNumber(),
            'role' => $user->getRole()
        ]);

        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $user->setId( $this->pdo->lastInsertId() );

        return $user;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( User::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?User
    {
        return $this->readById( User::class, $id );
    }

    /* crUd: Update */
    public function update( User $user ): ?User
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET
                    `email`=:email,
                    `firstname`=:firstname,
                    `lastname`=:lastname,
                    `phone_number`=:phone_number,
                    `role`=:role
                WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'phone_number' => $user->getPhoneNumber(),
            'role' => $user->getRole(),
            'id' => $user->getId()
        ]);

        // Si echec de la mise à jour
        if( ! $success ) {
            return null;
        }

        return $user;
    }
    
    /**
     * Valide les données d'authentification
     *
     * @param  string $email Email de l'utilisateur
     * @param  string $password Mot de passe de l'utilisateur
     * 
     * @return mixed User | null en cas d'échec
     */
    public function checkAuth( string $email, string $password ): ?User
    {
		$query = sprintf(
			'SELECT * FROM `%s` WHERE `email`=:email AND `password`=:password',
			$this->getTableName()
		);

		$sth = $this->pdo->prepare( $query );

		if( ! $sth ) {
            return null;
        }

		$sth->execute([ 
            'email' => $email,
            'password' => $password
        ]);

		$user_data = $sth->fetch();

		if( ! $user_data ) {
            return null;
        }

		return new User( $user_data );
    }
}