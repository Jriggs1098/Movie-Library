<?php

/*
 * Author: Jack Riggs
 * Date: Apr 22, 2019
 * File: user_controller.class.php
 * Description: Controller for user object.
 */

class UserController {

    private $user_model;  //an object of the UserModel class

    //create an instance of the UserModel class in the default constructor

    public function __construct() {
        $this->user_model = new UserModel();
    }

    //Display the registration form.
    public function index() {
        $view = new UserIndex();
        $view->display();
    }

    //This function registers the user, creating a user account and storing that data into the database.
    public function register() {
        $register = $this->user_model->add_user();

        //If the return value is a string (message).
        if (is_string($register)) {
            //Display an error
            $this->error($register);
            return;
        }

        $view = new UserRegister();
        $view->display($register);
    }

    //display the login form
    public function login() {
        $view = new UserLogin();
        $view->display();
    }

    //This function verifies the username and password against the database record.
    public function verify() {
        $verify = $this->user_model->verify_user();

        //If the return value is a string (message).
        if (is_string($verify)) {
            //Display an error
            $this->error($verify);
            return;
        }

        $view = new UserVerify();
        $view->display($verify);
    }

    //This function logs that user out of the system.
    public function logout() {
        $logout = $this->user_model->logout();

        //If the return value is a string (message).
        if (is_string($logout)) {
            //Display an error
            $this->error($logout);
            return;
        }

        $view = new UserLogout();
        $view->display();
    }

    //Show current user's library.
    public function library($user_id) {
        $library = $this->user_model->show_library($user_id);

        //If the return value is a string (message).
        if (is_string($library)) {
            //Display an error
            $this->error($library);
            return;
        }

        $view = new UserLibrary();
        $view->display();
    }

    //Add movie to user's library.
    public function movie($movie_id) {

        //Add to library.
        $movie = $this->user_model->add_movie($movie_id);

        //If the return value is a string (message).
        if (is_string($movie)) {
            //Display an error
            $this->error($movie);
            return;
        }

        //Display confirmation message.
        $confirm = "The movie was successfully added to your library.";

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        //Update the user's library before showing.
        $library = $this->user_model->show_library($user_id);

        //If the return value is a string (message).
        if (is_string($library)) {
            //Display an error
            $this->error($library);
            return;
        }

        $view = new UserLibrary();
        $view->display($confirm);
    }

    //Add episode to user's library.
    public function episode($episode_id) {

        //Add to library.
        $episode = $this->user_model->add_episode($episode_id);

        //If the return value is a string (message).
        if (is_string($episode)) {
            //Display an error
            $this->error($episode);
            return;
        }

        //Display confirmation message.
        $confirm = "The episode was successfully added to your library.";

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        //Update the user's library before showing.
        $library = $this->user_model->show_library($user_id);

        //If the return value is a string (message).
        if (is_string($library)) {
            //Display an error
            $this->error($library);
            return;
        }

        $view = new UserLibrary();
        $view->display($confirm);
    }

    //Delete a movie from a user's library.
    public function mdelete($movie_id) {
        $delete = $this->user_model->delete_movie($movie_id);

        //If the return value is a string (message).
        if (is_string($delete)) {
            //Display an error
            $this->error($delete);
            return;
        }

        //Display confirmation message.
        $confirm = "The movie was successfully removed from your library.";

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        //Update the user's library before showing.
        $library = $this->user_model->show_library($user_id);

        //If the return value is a string (message).
        if (is_string($library)) {
            //Display an error
            $this->error($library);
            return;
        }

        $view = new UserLibrary();
        $view->display($confirm);
    }

    //Delete an episode from a user's library.
    public function edelete($episode_id) {
        $delete = $this->user_model->delete_episode($episode_id);

        //If the return value is a string (message).
        if (is_string($delete)) {
            //Display an error
            $this->error($delete);
            return;
        }

        //Display confirmation message.
        $confirm = "The episode was successfully removed from your library.";

        //See if session exists already, if not start one.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        //Update the user's library before showing.
        $library = $this->user_model->show_library($user_id);

        //If the return value is a string (message).
        if (is_string($library)) {
            //Display an error
            $this->error($library);
            return;
        }

        $view = new UserLibrary();
        $view->display($confirm);
    }

    //Display errors.
    public function error($message) {
        $error = new UserError();
        $error->display($message);
    }

}
