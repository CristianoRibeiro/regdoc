<?php

namespace App\Helpers;

use App\Domain\Parte\Models\parte_emissao_certificado;
use App\Domain\Pedido\Repositories\PedidoRepository;

use App\Mail\Sul\NotificarAgendamento;

use Carbon\Carbon;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use DateTimeInterface;
use Exception;

final class SistemaSulCertificados
{
    private static function login(): string
    {
        $response = Http::post(config('certificadora.SISTEMA_SUL.URL') . 'login', [
           'username' => config('certificadora.SISTEMA_SUL.LOGIN'),
           'password' => config('certificadora.SISTEMA_SUL.PASSWORD')
        ]);

        if ($response->failed()) throw new Exception("Erro ao obter o Token", $response->status());

        return $response->header('Authorization');
    }

    public static function consulta_por_id(int $id): JsonResponse
    {
        $response = Http::withToken(self::login(), 'Bearer')
            ->withHeaders(['EmpresaId' => config('certificadora.SISTEMA_SUL.ID_EMPRESA')])
            ->get(config('certificadora.SISTEMA_SUL.URL') . 'certificados/' . $id);

        if (!$response->failed()) return response()->json($response->json(), 200);

        return response()->json([
            'Error' => [
                'status' => $response->status(),
                'message' => $response->body()
            ]
        ]);
    }

    public static function consulta_por_data(DateTimeInterface $dataInicio, DateTimeInterface $dataFim): JsonResponse
    {
        $response = Http::withToken(self::login(), 'Bearer')
            ->withHeaders(['EmpresaId' => config('certificadora.SISTEMA_SUL.ID_EMPRESA')])
            ->get(config('certificadora.SISTEMA_SUL.URL') . 'certificados?dt_cadastro_inicio=' . $dataInicio->format('Y-m-d') .'&dt_cadastro_fim=' . $dataFim->format('Y-m-d'));

        if (!$response->failed()) return response()->json($response->json(), 200);
        
        return response()->json([
            'Error' => [
                'status' => $response->status(),
                'message' => $response->body()
            ]
        ]);
    }

    public static function consulta_por_ticket(string $ticket): JsonResponse
    {
        $response = Http::withToken(self::login(), 'Bearer')
            ->withHeaders(['EmpresaId' => config('certificadora.SISTEMA_SUL.ID_EMPRESA')])
            ->get(config('certificadora.SISTEMA_SUL.URL') . 'certificados/ticket/' . $ticket);

        if (!$response->failed()) return response()->json($response->json(), 200);
        
        return response()->json([
            'Error' => [
                'status' => $response->status(),
                'message' => $response->body()
            ]
        ]);
    }

    public static function enviar_solicitacao(parte_emissao_certificado $parte_emissao_certificado, ?int $partner): void
    {
        $response = Http::withToken(self::login(), 'Bearer')
            ->withHeaders(['EmpresaId' => config('certificadora.SISTEMA_SUL.ID_EMPRESA')])
            ->post(config('certificadora.SISTEMA_SUL.URL') . 'certificados', [
                "empresa_id" => null,
                "dt_cadastro" => Carbon::now('America/Sao_Paulo'),
                "dt_agendamento" => null,
                "ponto_venda_id" => 1,
                "ponto_venda_cnpj" => "36504841000183",
                "ponto_venda_nome" => "VALID HUB CONSULTORIA EM TECNOLOGIA E TRATAMENTO DE DADOS S.A",
                "produto_id" => 2,
                "produto_codigo" => "ECPF3N",
                "produto_descricao" => "E CPF A3 DE 1 ANO EM NUVEM ",
                "titular_nome" => $parte_emissao_certificado->no_parte,
                "titular_cpf" => $parte_emissao_certificado->nu_cpf_cnpj,
                "titular_email" => $parte_emissao_certificado->no_email_contato,
                "titular_telefone" => $parte_emissao_certificado->nu_telefone_contato,
                "titular_telefone_adicional" => "",
                "cnpj" => "",
                "razao_social" => "",
                "telefone" => $parte_emissao_certificado->nu_telefone_contato,
                "ticket" => $parte_emissao_certificado->nu_ticket_vidaas,
                "dt_validade_certificado"=> null,
                "observacao" => $parte_emissao_certificado->de_observacoes_envio,
                "urgente" => false,
                "aceita_zap" => true,
                "parceiro_id" => $partner,
                "situacao_codigo" => "S1",
            ]);

        if ($response->failed()) {
            LogDB::insere(1,4, 'API Sistema Sul', 'API Sistema Sul', 'S', null, $response->body());

            throw new Exception('Erro ao se comunicar com a certificadora');
        }

        $args_email = [
            'no_email_contato' => $parte_emissao_certificado->no_email_contato,
            'titular_nome' => $response->json('titular_nome'),
            'no_id' => $response->json('id'),
        ];

        Mail::to($parte_emissao_certificado->no_email_contato, $parte_emissao_certificado->nu_telefone_contato)->queue(new NotificarAgendamento($parte_emissao_certificado->pedido, $args_email, "Agendamento Certificado Digital"));
    }
}