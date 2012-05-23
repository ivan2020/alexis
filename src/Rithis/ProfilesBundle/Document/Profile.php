<?php

namespace Rithis\ProfilesBundle\Document;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class Profile implements UserInterface
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
    protected $weight;

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

    /**
     * @var array
     */
    protected $identities = array();

    /**
     * @var array
     */
    protected $roles = array();

    public $password;
    public $role;
    public $license;

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

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function getNickname()
    {
        return $this->nickname;
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

    public function setWeight($weigth)
    {
        $this->weight = $weigth;
    }

    public function getWeight()
    {
        return $this->weight;
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

    public function addIdentity($identity)
    {
        $this->identities[] = $identity;
    }

    public function setIdentities(array $identities)
    {
        $this->identities = $identities;
    }

    public function getIdentities()
    {
        return $this->identities;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function mergeFormData(PasswordEncoderInterface $encoder)
    {
        $salt = md5(time());
        $this->setPasswordSalt($salt);
        $this->setPasswordHash($encoder->encodePassword($this->password, $salt));
        $this->addRole($this->role);
    }

    public function toArray()
    {
        return array(
            'nickname' => $this->nickname,
            'sex' => $this->sex,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'about' => $this->about,
            'birthday' => $this->birthday,
        );
    }

    public function loadFromArray(array $data)
    {
        if (isset($data['nickname'])) {
            $this->setNickname($data['nickname']);
        }
        if (isset($data['sex'])) {
            $this->setSex($data['sex']);
        }
        if (isset($data['firstName'])) {
            $this->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $this->setLastName($data['lastName']);
        }
        if (isset($data['about'])) {
            $this->setAbout($data['about']);
        }
        if (isset($data['birthday'])) {
            $this->setBirthday($data['birthday']);
        }
    }

    public function isSponsor()
    {
        return in_array('ROLE_SPONSOR', $this->roles);
    }

    public function isFree()
    {
        return in_array('ROLE_FREE', $this->roles);
    }

    public function getAge()
    {
        return $this->getBirthday()->diff(new \DateTime())->y;
    }

    public function getUsername()
    {
        return strlen($this->nickname) > 0 ? $this->nickname : reset($this->identities);
    }

    public function getPassword()
    {
        return $this->passwordHash;
    }

    public function getSalt()
    {
        return $this->passwordSalt;
    }

    public function eraseCredentials()
    {
    }
}
