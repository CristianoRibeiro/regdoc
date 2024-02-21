<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistroFiduciarioExcel implements FromView, ShouldAutoSize
{
    public function __construct($view, $todos_registros)
    {
        $this->view = $view;
        $this->todos_registros = $todos_registros;
    }

    public function view(): View
    {
        return view($this->view, [
            'todos_registros' => $this->todos_registros
        ]);
    }
}
