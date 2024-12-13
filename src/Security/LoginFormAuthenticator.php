<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // Récupération de l'email et mot de passe
        $email = $request->request->get('email'); // Correspond au champ 'name' dans le formulaire HTML
        $password = $request->request->get('password'); // Idem pour le mot de passe

        // Vérification si l'email est vide
        if (empty($email)) {
            throw new \Exception('L\'email ne peut pas être vide');
        }

        // Sauvegarde de l'email pour l'auto-complétion (sécurisé)
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Création du passeport avec l'email et le mot de passe
        return new Passport(
            new UserBadge($email), // Badge utilisateur basé sur l'email
            new PasswordCredentials($password), // Badge avec les informations d'authentification (mot de passe)
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')), // Protection CSRF
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Si un chemin cible est trouvé (comme après un accès à une page protégée), redirection vers celui-ci
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Si aucune page cible, redirection vers la page des réservations
        return new RedirectResponse($this->urlGenerator->generate('user_reservations'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

