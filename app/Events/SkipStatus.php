<?php

namespace App\Events;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SkipStatus
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public registro_fiduciario $registro_fiduciario)
    {
    }
}
