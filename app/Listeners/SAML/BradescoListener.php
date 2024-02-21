<?php

namespace App\Listeners\SAML;

use Aacotroneo\Saml2\Saml2User;

use App\Domain\Usuario\Models\usuario;

use App\Listeners\SAML\Attributes\IDP;

use stdClass;

#[IDP('bradesco')]
final class BradescoListener extends AbstractSamlListener
{
  protected function cadastrarLaravelUser(Saml2User $samlUser): usuario
  {
    $attributes = $samlUser->getAttributes();
    $fullName = "{$attributes['First Name'][0]} {$attributes['Last Name'][0]}";
    $email = $samlUser->getUserId();

    $args = new stdClass();
    $args->nome_completo = $fullName;
    $args->email = $email;
    $args->id_pessoa_relacionada = $this->getMasterPessoa()->id_pessoa;

    return $this->usuarioService->cadastrarUsuario($args);
  }
}