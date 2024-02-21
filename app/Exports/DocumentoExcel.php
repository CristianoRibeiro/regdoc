<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DocumentoExcel implements FromView, ShouldAutoSize
{
    public function __construct($view, $documentos)
    {
        $this->view = $view;
        $this->documentos = $documentos;
    }

    public function view(): View
    {
        return view($this->view, [
            'documentos' => $this->documentos
        ]);
    }
}
