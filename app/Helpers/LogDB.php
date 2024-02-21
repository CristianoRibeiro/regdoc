<?php 

namespace App\Helpers;

use App\Models\log;
use App\Models\log_detalhe;

use Illuminate\Support\Facades\Log as FileLog;

use Exception;

class LogDB
{
    public static function insere(int $id_usuario, int $id_tipo_log, string $de_log, string $de_funcionalidade, string $in_possui_controle_processo, ?string $no_endereco_ip, ?string $detalhe_log = null): bool 
    {
        FileLog::error($de_log, [
            'id_usuario' => $id_usuario,
            'tipo_log' => $id_tipo_log,
            'funcionalidade' => $de_funcionalidade,
            'detalhe_log' => $detalhe_log
        ]);

        $args_novo_log = [
            'id_sistema' => 1,
            'id_usuario' => $id_usuario,
            'id_tipo_log' => $id_tipo_log,
            'de_log' => $de_log,
            'de_funcionalidade' => $de_funcionalidade,
            'in_possui_controle_processo' => $in_possui_controle_processo,
            'in_detalhe_log' => $detalhe_log ? 'S' : 'N',
            'no_endereco_ip' => $no_endereco_ip
        ];

        $novo_log = new log();
        if (!$novo_log->insere($args_novo_log)) throw new Exception('Erro ao inserir o log no banco de dados.');
        if (!$detalhe_log) return true;
        
        $args_novo_log_detalhe = [
            'id_log' => $novo_log->id_log,
            'de_log_detalhe' => $detalhe_log
        ];

        $novo_log_detalhe = new log_detalhe();
        if (!$novo_log_detalhe->insere($args_novo_log_detalhe)) throw new Exception('Erro ao inserir o detalhe do log no banco de dados.');
        
        return true;
    }
}