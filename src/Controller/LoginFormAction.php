<?php

namespace Snowdog\DevTest\Controller;

class LoginFormAction extends BaseAction
{

    public function execute()
    {
        parent::checkUserState();

        require __DIR__ . '/../view/login.phtml';
    }

    protected function shouldBeLogged(): bool
    {
        return false;
    }
}