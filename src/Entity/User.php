<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *@ORM\Entity() 
 */
class User implements UserInterface{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message ="le nom d'utilisateur ne doit pas etre vide")
     * @Assert\Length(
     *      min = 1,
     *      max = 30,
     *      minMessage = "Ce pseudo est trop court !",
     *      maxMessage = "Ce pseudo est trop long !"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

     /**
     * @ORM\Column(type="string")
     * @Assert\Email(message ="votre mail n'est pas un mail valide !")
     * @Assert\NotBlank(message = "le champ email ne doit pas etre vide !")
     */ 
    private $mail; 
    
     /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "le mot de passe ne doit pas etre vide !")
     * 
     */
    private $password; 

    private $passwordHasher;

    public function __construct(UserPasswordHasher   $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }
     
    public function getId()
    {
        return $this->id;
    }

   
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
 
    public function getMail()
    {
        return $this->mail;
    }

    
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

  
    public function getPassword()
    {
        return $this->password;
    }

    
    public function setPassword($password)
    {
        $this->password = $this->passwordHasher->hashPassword($this, $password);

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        
        return array_unique($roles);
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
         * @see UserInterface
         */
        public function getSalt()
        {
                // not needed when using the "bcrypt" algorithm in security.yaml
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
                // If you store any temporary, sensitive data on the user, clear it here
                // $this->plainPassword = null;
        }

        public function getUserIdentifier()
        {
            return $this->username;
        }
}