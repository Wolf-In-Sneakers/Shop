<?php


namespace Shop\controllers;

use Exception;
use Shop\core\Controller;
use Shop\models\User\User;
use Shop\models\User\UserImg;

class AccountController extends Controller
{
    private User $user;
    private UserImg $user_img;

    public function __construct()
    {
        parent::__construct();

        if (!empty($_SESSION["user"])) {
            $this->user = new User();
            $this->user_img = new UserImg();
        }
    }

    public function indexAction()
    {
        try {
            if (!empty($_SESSION["user"])) {
                $id_user = $_SESSION["user"]['id_user'];

                if (!empty($_POST["update_profile"])) {
                    $update_profile = $this->user->updateProfile($_POST["name"], $_POST["login"], $_FILES["img"]);
                } else if (!empty($_POST["set_main_img_profile"])) {
                    $set_main_img = $this->user_img->setMainImg($id_user, $_POST['set_main_img_profile']);
                } else if (!empty($_POST["delete_img_profile"])) {
                    $delete_img = $this->user_img->deleteImg($id_user, $_POST['delete_img_profile']);
                }

                $profile = $this->user->readProfile();

            } else {
                header('Location: /');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/profile/profile.tmpl";
        $vars = [
            "user" => $profile["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "IMG_NOT_FOUND" => IMG_NOT_FOUND,
            "imgs" => $profile["img"]
        ];

        $this->view->render($path, $vars);
    }

    public function loginAction()
    {
        try {
            if (empty($_SESSION["user"])) {
                if (isset($_POST['enter'])) {
                    $login = $this->auth->login($_POST['login'], $_POST['password']);
                    header('Location: /');
                }
            } else {
                header('Location: /');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/auth/login.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors
        ];

        $this->view->render($path, $vars);
    }

    public function registrationAction()
    {
        try {
            if (empty($_SESSION["user"])) {
                if (isset($_POST['add_registration'])) {
                    $registration = $this->auth->registration($_POST['login'], $_POST['password'], $_POST['check_password'], $_POST['name']);
                }
            } else {
                header('Location: /');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/auth/registration.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors
        ];

        $this->view->render($path, $vars);
    }

    public function changePasswdAction()
    {
        try {
            if (!empty($_SESSION["user"])) {
                if (isset($_POST["change_passwd"])) {
                    $change_passwd = $this->user->changePasswd($_POST["last_passwd"], $_POST["passwd"], $_POST["passwd_check"]);
                }
            } else {
                header('Location: /');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/profile/changePasswd.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "change_passwd" => $change_passwd
        ];

        $this->view->render($path, $vars);
    }

    public function deleteAccountAction()
    {
        try {
            if (!empty($_SESSION["user"])) {
                if (!empty($_POST["delete_acc"])) {
                    $delete_acc = $this->user->deleteAcc($_POST["passwd"]);
                }
            } else {
                header('Location: /');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/profile/deleteAcc.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "delete_acc" => $delete_acc
        ];

        $this->view->render($path, $vars);
    }

    public function exitAction()
    {
        $basket = $_SESSION["basket"] ?? null;
        session_destroy();

        session_start();
        $_SESSION["basket"] = $basket;

        header('Location: /');
    }

}