<?php

namespace Symplefony\Model;

use DateTime;
use ReflectionProperty;

abstract class Entity
{
    protected int $id;
    public function getId(): int { return $this->id; }
    public function setId( int $value ): self
    {
        $this->id = $value;
        return $this; // Permet de "chaîner" les appels aux setters: $toto->setId(2)->setName('toto'), etc.
    }

    public function __construct( array $data = [] )
    {
        /*
        Pattern "Hydrator"
        Automatise l'hydratation d'un objet à partir d'un tableau associatif donné par la BDD
        */
        foreach( $data as $column_name => $value ) {
            // Si le nom de la colonne ne correspond à aucune propriété
            if( !property_exists( $this, $column_name ) ) {
                continue;
            }

            // Traitement particulier pour les dates (car conversion automatique impossible)
            // ReflectionProperty permet d'obtenir des infos techniques sur une propriété de classe
            $reflexionProp = new ReflectionProperty( $this, $column_name );
            // Si le type de la propriété est "\DateTime", on convertit la chaîne en DateTime
            if( $reflexionProp->getType()->getName() == DateTime::class ) {
                $value = new DateTime( $value );
            }

            $this->$column_name = $value;
        }
    }

}