<?php

class AccountController extends Controller
{
    public function login()
    {
        $this->startSession();
        if (isset($_SESSION['logged'])) {
            App::get('router')->direct('');
        } else {
            return $this->view(
                'login',
                [
                    'title' => 'Login'
                ]
            );
        }
    }

    public function loginPost()
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
            return $this->view(
                'login',
                compact(
                    'title',
                    'errorMessage',
                    'email'
                )
            );
        }
    }

    public function logout()
    {
        $this->startSession();
        if (isset($_SESSION['logged'])) {
            session_unset();
            session_destroy();
        }
        
        header('Location: /');
    }

    public function register()
    {
        $this->startSession();
        if (isset($_SESSION['logged'])) {
            header('Location: /');
        } else {
            return $this->view(
                'register',
                [
                    'title' => 'Register'
                ]
            );
        }
    }

    public function registerPost()
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
            $errorMessage[] = 'This email address is not valide.';            
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
    
            $name = ';
            $lastName = ';
            $email = ';
            $password = ';
        }
        if (isset($_SESSION['logged'])) {
            header('Location: /');
        } else {
            return $this->view(
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