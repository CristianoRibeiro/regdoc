<?php

namespace App\Listeners\SAML;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Saml2User;

use App\Domain\Pessoa\Models\pessoa;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Usuario\Models\usuario;

use App\Listeners\SAML\Attributes\IDP;

use Illuminate\Support\Facades\Auth;

abstract class AbstractSamlListener
{
  private string $IDP;

  public function __construct(protected UsuarioServiceInterface $usuarioService)
  {
    $idp_attribute = (new \ReflectionClass(static::class))->getAttributes(IDP::class)[0];
    $this->IDP = $idp_attribute->newInstance()->getName();
  }

  public function handle(Saml2LoginEvent $event): void
  {
    if(!$this->shouldListen($event->getSaml2Idp())) return;

    $samlUser = $event->getSaml2User();
    $this->loginLaravelUser($samlUser);
  }

  private function shouldListen(string $idp): bool
  {
    return $idp === $this->IDP;
  }

  private function loginLaravelUser(Saml2User $samlUser): void
  {
    $laravelUser = $this->getLaravelUser($samlUser);
    if(!$laravelUser) $laravelUser = $this->cadastrarLaravelUser($samlUser);

    Auth::login($laravelUser);
  }

  private function getLaravelUser(Saml2User $samlUser): ?usuario
  {
    $samlUserEmail = $samlUser->getUserId();

    /** @var usuario | null */
    return usuario::where('email_usuario', $samlUserEmail)
      ->join('usuario_pessoa', 'usuario_pessoa.id_usuario', '=', 'usuario.id_usuario')
      ->join('pessoa', 'usuario_pessoa.id_pessoa', '=', 'pessoa.id_pessoa')
      ->where('pessoa.de_saml', $this->IDP)
      ->first();
  }

  protected function getMasterPessoa(): pessoa
  {
    return pessoa::where('de_saml', $this->IDP)->first();
  }

  abstract protected function cadastrarLaravelUser(Saml2User $samlUser): usuario;
}