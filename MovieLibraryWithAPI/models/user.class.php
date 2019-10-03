<?php
/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user.class.php
 * Description: Defines the user object.
 */

class User {
    //Private attributes.
    private $id, $user_type, $firstname, $lastname, $username, $password;
    
    public function __construct($user_type, $firstname, $lastname, $username, $password) {
        $this->user_type = $user_type;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->password = $password;   
    }
    
    //Get user id.
    public function getId() {
        return $this->id;
    }

    //Get user type.
    public function getUser_type() {
        return $this->user_type;
    }

    //Get user first name.
    public function getFirstname() {
        return $this->firstname;
    }

    //Get user last name.
    public function getLastname() {
        return $this->lastname;
    }

    //Get username.
    public function getUsername() {
        return $this->username;
    }

    //Get password.
    public function getPassword() {
        return $this->password;
    }

    //Set user id.
    public function setId($id) {
        $this->id = $id;
    }

}
