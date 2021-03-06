<?php

namespace App\Lexico;

use App\Codigo\Codigo;

/**
 * Analisador léxico 
 * 
 * @author Fernanda Pires
 * @author Peter Clayder
 */
class AnalisLexico
{

    /**
     * Recebe o token formado 
     * @var type
     */
    private $token;

    /**
     * Lista de tokens 
     * @var array
     */
    private $arrayTokens;

    /**
     * Lista os tokens e o número da sua linha
     * @var array
     */
    private $arrayTokensLinha;

    /**
     * Recebe o id do caracter atual, que está sendo analisado
     * @var int 
     */
    private $idChAtual;

    /**
     * Recebe o caracter atual, que está sendo analisado
     * @var type
     */
    private $chAtual;

    /**
     * Recebe um objeto do tipo codigo
     * @var Codigo
     */
    private $codigo;

    /**
     * Possui um array com um relatório dos tokens gerados. 
     * Dados: id, descricao, lexema e se o token é palavra reservada ou não 
     * @var array
     */
    private $relatorioTokens;

    /**
     * Recebe true se o caracter for inválido e finaliza o laço de repetição lá da main
     * @var boolean
     */
    private $existeCaracterInvalido = false;

    /**
     * Recebe o caracter (token) inválido
     * @var type 
     */
    private $tokenInvalido;

    /**
     * Recebe o número da linha atual
     * @var int 
     */
    private $linhaAtual;

    /**
     * 
     * @param Codigo $codigo
     */
    public function __construct(Codigo $codigo)
    {
        $this->idChAtual = 0;
        $this->token = "";
        $this->codigo = $codigo;
    }

    /**
     * 
     * @return type
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 
     * @param type $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Percorre o código criando os tokens, a partir do caracter atual (chAtual). 
     * Quando terminar de formar o token em relação ao caracter atual, 
     * o próximo token é escolhido (atualiza chAtual e idAtual, essa atualização é 
     * feita no "do while" do método responsável) com isso o método 
     * (nextToken) é finalizado. Depois de finalizar o método o programa volta para 
     * a main (compilador.php), dando continuidade na execução do “do while”. 
     * @return void
     */
    public function nextToken()
    {
        // Recebe o valor do caracter atual 
        $this->chAtual = $this->codigo->getCaracterCodigo($this->idChAtual);

        $dadosEliminarCaracter = $this->codigo->eliminaCaracterInvalidos($this->idChAtual, $this->chAtual);

        $this->setDadosChAtual($dadosEliminarCaracter['idChAtual'], $dadosEliminarCaracter['chAtual']);

        $objGerarToken = null;

        switch ($this->chAtual)
        {
            case (Letra::ehLetra($this->chAtual)):
                $objGerarToken = new Letra($this->codigo);
                break;

            case (is_numeric($this->chAtual)):
                $objGerarToken = new Algarismo($this->codigo);
                break;

            case (Sinal::ehSinal($this->chAtual)):

                $objGerarToken = new Sinal($this->codigo);

                break;

            case ($this->chAtual === "<"):

                $objGerarToken = new SinalMenor($this->codigo);

                break;

            case ($this->chAtual === ">"):

                $objGerarToken = new SinalMaior($this->codigo);

                break;

            case ($this->chAtual === ":"):
                $objGerarToken = new SinalPontoIgual($this->codigo);
                break;

            case ($this->chAtual === "{"):

                $objGerarToken = new Comentario($this->codigo);

                break;

            default:
                $this->existeCaracterInvalido = true;
                $this->tokenInvalido = $this->chAtual;
                break;
        }


        if ($objGerarToken != null && !$this->existeCaracterInvalido)
        {
            $this->geracaoToken($objGerarToken);
        } else
        {
            $this->tokenInvalido = $this->chAtual;
            $this->linhaAtual = Codigo::$linha;
        }
    }

    /**
     * Método para realizar a geração de tokens.
     * Recebe como parametro uma variável do tipo IToken ( interface )
     * 
     * @param \App\Lexico\IToken $gerar
     */
    public function geracaoToken(IToken $gerar)
    {

        /*
         * Cria o token 
         */
        $tokenGerado = $gerar->gerarToken($this->token, $this->chAtual, $this->idChAtual);

        /* Se o token for valido */
        if (count($tokenGerado) > 0)
        {
            /*
             * Atualiza os dados do chAtual
             */
            $this->setDadosChAtual($tokenGerado['idChatual'], $tokenGerado['chAtual']);
            $this->token = $tokenGerado['token'];
            $this->arrayTokens[] = $this->token;
            $this->arrayTokensLinha[] = array(
                'token' => $this->token,
                'linha' => Codigo::$linha
            );
            $this->relatorioTokens[] = $tokenGerado['relatorio'];
        } else
        {
            $this->existeCaracterInvalido = true;
            $this->tokenInvalido = $this->chAtual;
            $this->linhaAtual = Codigo::$linha;
        }
    }

    public function getLinhaAtual()
    {
        return $this->linhaAtual;
    }

    /**
     * @return void
     */
    public function imprime()
    {
        echo $this->getToken();
    }

    /**
     * 
     * @return int
     */
    public function getIdChAtual()
    {
        return $this->idChAtual;
    }

    /**
     * 
     * @return array
     */
    public function getArrayTokens()
    {
        return $this->arrayTokens;
    }

    /**
     * Atualiza os dados do idChAtual e chAtual
     * @param int $idChAtual
     * @param type $chAtual
     * @return void
     */
    public function setDadosChAtual($idChAtual, $chAtual)
    {
        $this->idChAtual = $idChAtual;
        $this->chAtual = $chAtual;
    }

    /**
     * 
     * @return array
     */
    public function getRelatorioTokens()
    {
        return $this->relatorioTokens;
    }

    /**
     * 
     * @return type
     */
    public function getExisteCaracterInvalido()
    {
        return $this->existeCaracterInvalido;
    }

    /**
     * 
     * @return type
     */
    public function getTokenInvalido()
    {
        return $this->tokenInvalido;
    }
    
    public function getArrayTokensLinha()
    {
        return $this->arrayTokensLinha;
    }



}
