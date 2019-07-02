<?php

namespace Snowdog\DevTest\Controller;

class RegisterFormAction extends BaseAction
{
    public function execute()
    {
        parent::checkUserState();

        require __DIR__ . '/../view/register.phtml';
    }

    protected function shouldBeLogged(): bool
    {
        return false;
    }
}
