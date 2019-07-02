<?php

namespace Snowdog\DevTest\Controller;

abstract class BaseAction
{
    public function checkUserState()
    {
        $isLogged = isset($_SESSION['login']);
        if (!$isLogged && $this->shouldBeLogged()) {
            header('Location: /login');

            return;
        }

        if ($isLogged && !$this->shouldBeLogged()) {
            header('HTTP/1.0 403 Forbidden');
            require __DIR__ . '/../view/403.phtml';
            die;
        }
    }

    abstract protected function shouldBeLogged(): bool;
}
