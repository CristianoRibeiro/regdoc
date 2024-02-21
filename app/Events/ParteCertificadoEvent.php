<?php

namespace App\Events;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParteCertificadoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private registro_fiduciario $registro_fiduciario;

    public function __construct(registro_fiduciario $registro_fiduciario)
    {
        $this->registro_fiduciario = $registro_fiduciario;
    }

    public function getRegistroFiduciario(): registro_fiduciario
    {
        return $this->registro_fiduciario;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
