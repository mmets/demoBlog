<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/*
    Lorsqu'on souhaite insérer un mot de passe crypté en BDD, 
    on doit implémenter l'interface UserInterface qui contient 
    des méthodes spécifiques indispensables à l'insertion en BDD 
    d'un mdp hashé
*/

/*
    On précise que l'email est unique dans l'ORM de la classe
     User implements UserInterface
*/

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *          fields = {"email"},
 *          message = "Compte déjà existant avec cet email. Avez-vous oublié votre mot de passe?"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *          message = "Cette adresse Email '{{ value }}' n'est  pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *              min="8",
     *              minMessage="Votre message doit contenir au moins 8 caractères"
     * )
     * @Assert\EqualTo(
     *              propertyPath="confirm_password", 
     *              message = "Vérifier votre mot de passe"
     * )
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *              propertyPath="password", 
     *              message = "Vérifier votre mot de passe"
     * )
     */
    public $confirm_password; 
    // On ajoute la propriété publique $confirm_password 
    // qui va réceptionner les données du champ pour comparer avec la 
    // propriété privée

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /*
        Pour pouvoir encoder le mdp, il faut que notre User Entity 
        implémente l'interface UserInterface car UserInterface contient
        des méthodes que nous sommes obligées de déclarer:
        getPassword(), getUsername(), getRoles(), getSalt() et eraseCredentials
    */

    // eraseCredentials nettoie les mdp
    public function eraseCredentials()
    {

    }

    // getSalt() renvoie la chaîne de caractères non encodée 
    // que l'utilisateur a saisi et qui a été utilisée pour l'encodage
    public function getSalt()
    {

    }

    // getRoles renvoit un tableau de chaîne de caractères où sont 
    // stockées les rôles accordés aux utilisateurs comme dans Wordpress
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

}
