<?php

namespace Rithis\ProfileBundle;

class UserConverter {
    private $dbuser;

    public function __construct($dbuser) {
        $this->dbuser=$dbuser;
    }

    public function fromDb() {
        $role=
        $age=(int)((date('Ymd') - date('Ymd', $this->dbuser['birthdate'])) / 10000);
        $timerange=

        $user=array(
            'nickname' => $this->dbuser['nickname'],
            'avatar' => $this->dbuser['avatar'],
            'age' => $age,
            'weight' => $this->dbuser['weight'],
            'height' => $this->dbuser['height'],
            'sex' => $this->dbuser['sex'],
            'role' => $role,
            'sex' => $this->dbuser['sex'],
            'about_me' => $this->dbuser['about_me'],
            'budget' => $this->dbuser['budget'],
            'timerange' => $timerange,
        );

        return $user;
    }
}
