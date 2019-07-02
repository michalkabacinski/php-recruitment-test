<?php

namespace Snowdog\DevTest\Controller;

class LogoutAction extends BaseAction
{

    public function execute()
    {
        parent::checkUserState();

        if(isset($_SESSION['login'])) {
            unset($_SESSION['login']);
            $_SESSION['flash'] = 'Logged out successfully';
        }
        header('Location: /login');
    }

    protected function shouldBeLogged(): bool
    {
        return true;
    }
}