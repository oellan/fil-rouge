<?php

namespace App\Controllers;

use App\Entities\User;
use BadBox\Http\AbstractController;
use BadBox\Http\Response\BasicResponse;
use BadBox\Http\Response\JsonResponse;
use BadBox\Http\Response\RedirectResponse;
use BadBox\Http\Response\Response;
use BadBox\Http\Response\TemplateResponse;
use BadBox\Modules\Database;

class AuthController
    extends AbstractController
{

    public static function login(): Response
    {

        return self::getMethod() === 'post'
            ? self::login_post()
            : self::login_get();
    }

    private static function login_get(string $error = null): Response
    {
        $context = [];
        if ($error !== null) {
            $context['error'] = $error;
        }
        return new TemplateResponse('auth/login.html', $context);
    }

    private static function login_post(): Response
    {
        $postUsername = $_POST['username'];
        $postPassword = $_POST['password'];
        $db = Database::getInstance();
        $results = $db->preparedQuery(
            'SELECT id, username, email, password FROM users WHERE `username` = :username',
            [':username' => $_POST['username']]
        );
        if (!$results) {
            return self::login_get("Can't find user $postUsername");
        }
        return new JsonResponse(
            ['$_GET'  => $_GET,
             '$_POST' => $_POST]
        );
    }

    public static function register(): Response
    {
        return self::getMethod() === 'post'
            ? self::register_post()
            : self::register_get();
    }

    private static function register_get($error = null): Response
    {
        $context = [];
        if ($error !== null) {
            $context['error'] = $error;
        }
        return new TemplateResponse('auth/register.html', $context);
    }

    private static function register_post(): Response
    {
        $postUsername = $_POST['username'];
        $postEmail = $_POST['email'];
        $postPassword = $_POST['password'];
        $postConfirmPassword = $_POST['confirm_password'];
        if ($postPassword !== $postConfirmPassword) {
            return self::register_get('Password and password confirmation are mismatched');
        }
        if (strlen(trim($postPassword)) <= 12) {
            return self::register_get('Your password is too short');
        }
        if (User::getByUsername($postUsername) !== null) {
            return self::register_get('User with username ' . htmlspecialchars($postUsername) . ' already exists');
        }
        if (User::getByEmail($postEmail) !== null) {
            return self::register_get('User with email ' . htmlspecialchars($postEmail) . ' already exists');
        }
        User::insertUser(new User($postUsername, $postEmail, password_hash($postPassword, PASSWORD_ARGON2I)));
        return new RedirectResponse('/');
    }
}
