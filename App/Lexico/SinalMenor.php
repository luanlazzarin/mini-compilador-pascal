<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Classe utilizada para verificação e criação de token da categoria sinal menor.
 * Lista de possíveis tokens:
 * 
 * 1) <
 * 2) <=
 * 3) <>
 *
 * @author Peter Clayder e Fernanda Pires
 */
class SinalMenor implements IToken
{
    
    /**
     * Recebe a chave que o token pertence. 
     * Essa chave é referente ao array $simbolos da classe TabelaSimbolos 
     * @var string 
     */
    private $tipoToken;
    
    /**
     *
     * @var Codigo
     */
    private $codigo;
    
    /**
     * 
     * @param Codigo $codigo
     * @return void
     */
    public function __construct($codigo)
    {
        $this->codigo = $codigo;
    }

     /**
     * Implementação da interface IToken 
     * @param type $token
     * @return array Description
     */
    public function gerarRelatorio($token)
    {
        return Relatorio::get($token, $this->tipoToken);
    }

    /**
     * Analisa o sinal < e verifica se o próximo carácter é um dos sinais :
     * = ou >
     * 
     * @param string $token
     * @param type $chAtual
     * @param int $idChAtual
     * @return array
     */
    public function gerarToken($token, $chAtual, $idChAtual)
    {

        //Teste\Teste::gerarToken("menor", $chAtual, $token, $idChAtual);
       
        // próximo caracter 
        $dadosProxCaracter = $this->codigo->proximoCaracter($idChAtual, $chAtual);

        // recebe os dados do próximo caracter
        $chProximo = $dadosProxCaracter['chAtual'];
        $idChProximo = $dadosProxCaracter['idChAtual'];
        
        if ($dadosProxCaracter['chAtual'] === ">")
        {
            $token = $chAtual . ">";
            $dadosProxCaracter = $this->codigo->proximoCaracter($idChProximo, $chProximo);
            $this->tipoToken = "diferente";
        } elseif ($dadosProxCaracter['chAtual'] === "=")
        {
            $token = $chAtual . "=";
            $dadosProxCaracter = $this->codigo->proximoCaracter($idChProximo, $chProximo);
            $this->tipoToken = "menor_igual";
        } else
        {
            $this->tipoToken = "menor";
            $token = $chAtual;
        }

        $relatorio = $this->gerarRelatorio($token);

        return array('token' => $token, 'chAtual' => $dadosProxCaracter['chAtual'], 'idChatual' => $dadosProxCaracter['idChAtual'], 'relatorio' => $relatorio);
    }


}
