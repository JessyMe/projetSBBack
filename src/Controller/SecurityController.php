<?php


namespace App\Controller;


use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\OpenApi\Model\Response;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/api/secure/check_login", name="app_login", methods={"POST"})
     */
    public function login()
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }
        $user = $this->security->getUser();
        $email = $user->getUsername();
        dd($email);
        return new Response(null, 204, [
            'email' => $email
        ]);
    }
}

    /**
     * @Route("/logout", name="app_logout")
     */
    /*
    public function logout()
    {
        throw new \Exception('should not be reached');
    }

    */
