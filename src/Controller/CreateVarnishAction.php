<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishAction extends BaseAction
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {
        parent::checkUserState();

        if ($this->user === null) {
            $_SESSION['flash'] = 'Please, sign in!';
            header('Location: /login');

            return;
        }

        $ip = $_POST['ip'];

        if (!$this->validateIp($ip)) {
            $_SESSION['flash'] = 'Given IP address is invalid!';
            header('Location: /varnish');

            return;
        }

        if ($this->checkIfVarnishAlreadyExists($ip)) {
            $_SESSION['flash'] = 'Varnish has been already added!';
            header('Location: /varnish');

            return;
        }

        $this->addVarnish($ip);
        $_SESSION['flash'] = 'Varnish added successfully!';
        header('Location: /varnish');
    }

    private function validateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    private function checkIfVarnishAlreadyExists(string $ip): bool
    {
        $varnishes = $this->varnishManager->getAllByUser($this->user);

        foreach ($varnishes as $varnish) {
            if ($varnish->getIp() == $ip) {
                return true;
            }
        }

        return false;
    }

    private function addVarnish(string $ip)
    {
        $this->varnishManager->create($this->user, $ip);
    }

    protected function shouldBeLogged(): bool
    {
        return true;
    }
}