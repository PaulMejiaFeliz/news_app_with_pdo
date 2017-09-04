<?php namespace newsapp\controllers;

use newsapp\core\App;
use newsapp\core\Controller;

/**
 * Class used to manage accounts
 */
class AccountController extends Controller
{

    /**
     * Displays the login view
     *
     * @return void
     */
    public function login() : void
    {
        $this->startSession();
        if (isset($_SESSION['logged'])) {
            App::get('router')->direct('');
        } else {
            $this->view(
                'login',
                [
                    'title' => 'Login'
                ]
            );
        }
    }

    /**
     * If the credentials are right, lets the user login
     *
     * @return void
     */
    public function loginPost() : void
    {
        $this->startSession();
        $title = 'Login';
        $errorMessage = [];
        extract($_POST);
        
        if (strlen(trim($email)) == 0) {
            $errorMessage[]= 'The email is required.';
        } else {
            $user = App::get('qBuilder')->select(
                'user',
                [
                    'email' => $email
                ]
            )[0];
            if ($user > 0) {
                if (strlen(trim($password)) > 0) {
                    if (!password_verify($password, $user['password'])) {
                        $errorMessage[] = 'The passwords do not match.';
                    }
                } else {
                    $errorMessage[] = 'The password is required.';
                }
            } else {
                $errorMessage[] = 'This email address is not registred.';
            }
            if (count($errorMessage) == 0) {
                unset($user['password']);
                $_SESSION['user'] = $user;
                $_SESSION['logged'] = true;
            }
        }
        if (isset($_SESSION['logged'])) {
            header('Location: /');
        } else {
            $this->view(
                'login',
                compact(
                    'title',
                    'errorMessage',
                    'email'
                )
            );
        }
    }

    /**
     * Logouts the current user if where is any
     *
     * @return void
     */
    public function logout() : void
    {
        $this->startSession();
        if (isset($_SESSION['logged'])) {
            session_unset();
            session_destroy();
        }
        
        header('Location: /');
    }

    /**
     * Displays the register view
     *
     * @return void
     */
    public function register() : void
    {
        $this->startSession();
        if (isset($_SESSION['logged'])) {
            header('Location: /');
        } else {
            $this->view(
                'register',
                [
                    'title' => 'Register'
                ]
            );
        }
    }

    /**
     * If the given information fulfill the rules registers a new user
     *
     * @return void
     */
    public function registerPost() : void
    {
        $this->startSession();

        $errorMessage = [];
        $title = 'Register';
        extract($_POST);

        if (strlen(trim($password)) >= 5) {
            if ($password == $confirmPassword) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $errorMessage[] = 'The passwords do not match.';
            }
        } else {
            $errorMessage[] = 'The password must have at least 5 charcters.';
        }
        if (strlen(trim($name)) == 0) {
            $errorMessage[] = 'The name is required.';
        }
        if (strlen(trim($lastName)) == 0) {
            $errorMessage[] = 'The lastname is required.';
        }
        if (strlen(trim($email)) == 0) {
            $errorMessage[] = 'The email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage[] = 'This email address is not valid.';
        } else {
            $user = count(
                App::get('qBuilder')->select(
                    'user',
                    [
                        'email' => $email
                    ]
                )
            );
            if ($user != 0) {
                $errorMessage[] = 'This email address is not available.';
            }
        }
        if (count($errorMessage) == 0) {
            $userId = App::get('qBuilder')->insert(
                'user',
                [
                    'name' => $name,
                    'lastName' => $lastName,
                    'email' => $email,
                    'password' => $password
                ]
            );

            $user = App::get('qBuilder')->selectById('user', $userId);
            unset($user['password']);
            $_SESSION['user'] = $user;
            $_SESSION['logged'] = true;
    
            $name = '';
            $lastName = '';
            $email = '';
            $password = '';
        }
        if (isset($_SESSION['logged'])) {
            header('Location: /');
        } else {
            $this->view(
                'register',
                compact(
                    'title',
                    'errorMessage',
                    'name',
                    'lastName',
                    'email'
                )
            );
        }
    }
}
