<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AfterLoginRedirection
 *
 * @package App\Listener
 */
class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{
    private $router;

    /**
     * AfterLoginRedirection constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request        $request
     *
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) 
    {
        $roles = $token->getRoles();

        $rolesTab = array_map(function ($role) {
            return $role->getRole();
        }, $roles);
        
       if (in_array('ROLE_ADMIN', $rolesTab, true)) {
            
            $redirection = new RedirectResponse($this->router->generate('admin_dashbord'));
        }
        elseif (in_array('ROLE_STUDENT', $rolesTab, true)) {
            $redirection = new RedirectResponse($this->router->generate('student_profile', [
                'id' => $token->getUser()->getId(),
            ]));
        }
        elseif (in_array('ROLE_TEACHER', $rolesTab, true)) {
            $redirection = new RedirectResponse($this->router->generate('teacher_profile', [
                'id' => $token->getUser()->getId(),
            ]));
        }
        elseif (in_array('ROLE_GUARDIAN', $rolesTab, true)) {
            $redirection = new RedirectResponse($this->router->generate('guardian_profile', [
                'id' => $token->getUser()->getId(),
            ]));
        }
        
        else {
            
            $redirection = new RedirectResponse($this->router->generate('home_page'));
        }

        return $redirection;
    }
}