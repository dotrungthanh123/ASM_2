<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface {
    public function __construct(SessionInterface $sessionInterface, RouterInterface $routerInterface)
    {
        $this->session = $sessionInterface;
        $this->router = $routerInterface;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $this->session->getFlashBag()->add("Warning", "Access Denied !");
        return new RedirectResponse($this->router->generate('login'));
    }
}
?>