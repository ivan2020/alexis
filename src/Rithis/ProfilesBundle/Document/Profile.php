<?php

namespace Rithis\ProfilesBundle\Document;

class Profile
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $passwordHash;

    /**
     * @var string
     */
    protected $passwordSalt;

    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var \DateTime
     */
    protected $birthday;

    /**
     * @var int
     */
    protected $weigth;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $sex;

    /**
     * @var string
     */
    protected $about;

    /**
     * @var int
     */
    protected $budget;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
    }

    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function setWeigth($weigth)
    {
        $this->weigth = $weigth;
    }

    public function getWeigth()
    {
        return $this->weigth;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function setAbout($about)
    {
        $this->about = $about;
    }

    public function getAbout()
    {
        return $this->about;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    public function getBudget()
    {
        return $this->budget;
    }
}
