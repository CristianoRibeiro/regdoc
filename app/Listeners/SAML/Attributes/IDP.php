<?php

namespace App\Listeners\SAML\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class IDP
{
  public function __construct(private string $name)
  {
  }

  public function getName(): string
  {
    return $this->name;
  }
}