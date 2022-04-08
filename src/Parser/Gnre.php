<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

use Inspire\Support\Xml\Xml;
use Inspire\Support\ {
    Arrays
};

/**
 * Description of Gnre
 *
 * @author aalves
 */
class Gnre extends Base
{

    /**
     * Parse SEFAZ response of GnreConfigUF
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function GnreConfigUF(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'TConfigUf');
        Arrays::set($aData, 'cType', self::$messages[$aData['situacaoConsulta']['codigo']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of GnreRecepcaoLote
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function GnreRecepcaoLote(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'TRetLote_GNRE');
        Arrays::set($aData, 'cType', self::$messages[$aData['situacaoRecepcao']['codigo']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of GnreResultadoLote
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function GnreResultadoLote(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'TResultLote_GNRE');
        Arrays::set($aData, 'cType', self::$messages[$aData['situacaoProcess']['codigo']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of GnreResultadoLote
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function GnreResultadoLoteConsulta(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'TResultLote_GNRE');
        Arrays::set($aData, 'cType', self::$messages[$aData['situacaoProcess']['codigo']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     *
     * @param string $code
     * @return number[]|string[]
     */
    public static function getResponseCode(string $code): ?array
    {
        return self::$messages[$code];
    }

    /**
     * Response codes and messages.
     * Group messages by status type
     *
     * 0 - Error on message sent. Fix package before resending
     * 1 - OK
     * 2 - Wait before try again
     * 3 - Temporary error on service
     * 4 - Permanent error on service
     * 5 - Permanent error with carrier registration
     * 6 - Certificate error
     * 7 - Permanent error on document
     *
     * @var array
     */
    private static array $messages = [
        '400' => [ // Verified
            'type' => 2,
            'message' => 'Lote Recebido. Aguardando processamento'
        ],
        '401' => [ // Verified
            'type' => 2,
            'message' => 'Lote em Processamento'
        ],
        '402' => [ // Verified
            'type' => 1,
            'message' => 'Lote processado com sucesso'
        ],
        '403' => [ // Verified
            'type' => 0,
            'message' => 'Processado com pendência'
        ],
        '404' => [ // Verified
            'type' => 0,
            'message' => 'Lote com pendência de tempo de processamento. As Guias com situação 4 (campo <situacaoGuia> para a versão 2.00) podem levar em média 20 minutos, e no máximo 1 hora para serem processadas. Erro no processamento do lote. Enviar o lote novamente.'
        ],
        '601' => [ // Verified
            'type' => 0,
            'message' => 'O ambiente solicitado difere do ambiente do webservice'
        ],
        '602' => [ // Verified
            'type' => 0,
            'message' => 'Número do recibo não cadastrado'
        ],
        '603' => [ // Verified
            'type' => 0,
            'message' => 'CNPJ do recibo difere do CNPJ do contribuinte'
        ],
        '604' => [ // Verified
            'type' => 0,
            'message' => 'Erro ao gerar arquivo de código de barras'
        ],
        '450' => [ // Verified
            'type' => 1,
            'message' => 'Consulta da configuração da UF realizada com sucesso.'
        ],
        '451' => [ // Verified
            'type' => 0,
            'message' => 'Receita não cadastrada!'
        ],
        '452' => [ // Verified
            'type' => 0,
            'message' => 'Receita não relacionada a esta UF'
        ],
        '453' => [ // Verified
            'type' => 4,
            'message' => 'UF não cadastrada neste ambiente!'
        ],
        '454' => [ // Verified
            'type' => 4,
            'message' => 'UF não habilitada neste ambiente!'
        ],
        '455' => [ // Verified
            'type' => 4,
            'message' => 'Nenhuma receita habilitada para esta UF!'
        ],
        '100' => [ // Verified
            'type' => 1,
            'message' => 'Lote recebido com sucesso'
        ],
        '101' => [ // Verified
            'type' => 6,
            'message' => 'Certificado inválido'
        ],
        '102' => [ // Verified
            'type' => 5,
            'message' => 'CNPJ não habilitado para uso do serviço.'
        ],
        '103' => [ // Verified
            'type' => 0,
            'message' => 'Mensagem excedeu o tamanho máximo de 600KB.'
        ],
        '153' => [ // Verified
            'type' => 0,
            'message' => 'A quantidade de guias no lote não pode ser maior que xxx! (vide serviço de Consulta de Configurações – GnreConfigUF)'
        ],
        '154' => [ // Verified
            'type' => 5,
            'message' => 'CNPJ bloqueado para uso do serviço.'
        ],
        '155' => [ // Verified
            'type' => 0,
            'message' => 'A quantidade de itens no lote não pode ser maior que xxx! (vide serviço de Consulta de Configurações – GnreConfigUF)'
        ],
        '156' => [ // Verified
            'type' => 0,
            'message' => 'XML de Lote inválido'
        ],
        '157' => [ // Verified
            'type' => 0,
            'message' => 'Versão do cabeçalho informada difere da versão do XML de Dados!'
        ],
        '197' => [ // Verified
            'type' => 0,
            'message' => 'Erro ao recepcionar o lote'
        ],
        '198' => [ // Verified
            'type' => 6,
            'message' => 'Este serviço deve usar uma conexão HTTPS Segura! Tente novamente utilizando seu Certificado Digital.'
        ],
        '199' => [ // Verified
            'type' => 3,
            'message' => 'A quantidade de consultas no lote nao pode ser maior que xx. (vide serviço de Consulta de Configurações - GnreConfigUF)'
        ],
        '104' => [ // Verified
            'type' => 0,
            'message' => 'Conteúdo do XML de dados inválido!'
        ],
        '105' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c01_UfFavorecida" está inválido! Deve ser um destes valores: [AC, AL, AM, AP, BA, CE, DF, ES, GO, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, TO]'
        ],
        '106' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c20_ufEnderecoEmitente" está inválido! Deve ser um destes valores: [AC, AL, AM, AP, BA, CE, DF, ES, GO, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, TO]'
        ],
        '107' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c05_referencia/ ódigo " está inválido! Deve ser um destes valores: [0, 1, 2, 3, 4, 5]'
        ],
        '108' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c05_referencia/mes" está inválido! Deve ser um destes valores: [01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12]'
        ],
        '109' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c27_tipoIdentificacaoEmitente" está inválido! Deve ser um destes valores: [1, 2]'
        ],
        '110' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c34_tipoIdentificacaoDestinatario" está inválido! Deve ser um destes valores: [1, 2]'
        ],
        '111' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c39_camposExtras/campoExtra/tipo" está inválido! Deveser um destes valores: [T, N, D]'
        ],
        '114' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c15_convenio" não deve ser maior que 30 caracteres!'
        ],
        '115' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c16_razaoSocialEmitente" não deve ser maior que 60 caracteres!'
        ],
        '116' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c18_enderecoEmitente" não deve ser maior que 60 caracteres!'
        ],
        '117' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c37_razaoSocialDestinatario" não deve ser maior que 60 caracteres!'
        ],
        '118' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c39_camposExtras/campoExtra/valor" não deve ser maior que 100 caracteres!'
        ],
        '119' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c15_convenio" está inválido. O valor não deve ter o caractere espaço no início nem no final do texto, bem como não deve haver quebras de linha!'
        ],
        '120' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c16_razaoSocialEmitente" está inválido. O valor não deve ter o caractere espaço no início nem no final do texto, bem como não deve haver quebras de linha!'
        ],
        '121' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c18_enderecoEmitente" está inválido. O valor não deve ter o caractere espaço no início nem no final do texto, bem como não deve haver quebras de linha!'
        ],
        '122' => [
            'type' => 0,
            'message' => 'O valor do campo "c37_razaoSocialDestinatario" está inválido. O valor não deve ter o caractere espaço no início nem no final do texto, bem como não deve haver quebras de linha!'
        ],
        '123' => [
            'type' => 0,
            'message' => 'O valor do campo "c02_receita" está inválido. O valor deve ter 6 caracteres numéricos.'
        ],
        '124' => [
            'type' => 0,
            'message' => 'O valor do campo "c25_detalhamentoReceita" está inválido. O valor deve ter 6 caracteres numéricos.'
        ],
        '125' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c25_detalhamentoReceita" está inválido. O valor deve ter até 4 caracteres numéricos, com valor entre "1" e "9999"'
        ],
        '126' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c28_tipoDocOrigem" está inválido. O valor deve ter 2 caracteres numéricos.'
        ],
        '127' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c04_docOrigem" está inválido. O valor deve ter entre 1 e 18 caracteres numéricos.'
        ],
        '128' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c21_cepEmitente" está inválido. O valor deve ter 8 caracteres numéricos.'
        ],
        '129' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c22_telefoneEmitente" está inválido. O valor deve ter entre 6 e 11 caracteres numéricos.'
        ],
        '130' => [
            'type' => 0,
            'message' => 'O valor do campo "c05_referencia/parcela" está inválido. O valor deve ter entre 1 e 3 caracteres numéricos, com valor entre "1" e "999".'
        ],
        '131' => [
            'type' => 0,
            'message' => 'O valor do campo "c03_idContribuinteEmitente/CNPJ" está inválido. O valor deve ter 14 caracteres numéricos.'
        ],
        '132' => [
            'type' => 0,
            'message' => 'O valor do campo "c35_idContribuinteDestinatario/CNPJ" está inválido. O valor deve ter 14 caracteres numéricos.'
        ],
        '133' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c03_idContribuinteEmitente/CPF" está inválido. O valor deve ter 11 caracteres numéricos.'
        ],
        '134' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c35_idContribuinteDestinatario/CPF" está inválido. O valor deve ter 11 caracteres numéricos.'
        ],
        '135' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c06_valorPrincipal" está inválido. O valor deve estar no seguinte formato: "[0.00 a 9999999999.99]" ou "0".'
        ],
        '136' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c10_valorTotal" está inválido. O valor deve estar no seguinte formato: "[0.00 a 9999999999.99]" ou "0".'
        ],
        '140' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c14_dataVencimento" está inválido. O valor deve ser uma data válida no formato AAAA-MM-DD, com ano maior ou igual a "2000" e menor que "2100".'
        ],
        '141' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c33_dataPagamento" está inválido. O valor deve ser uma data válida no formato AAAA-MM-DD, com ano maior ou igual a "2000 e menor que "2100".'
        ],
        '143' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c17_inscricaoEstadualEmitente" está inválido. O valor deve ter de 2 a 16 caracteres numéricos.'
        ],
        '144' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c36_inscricaoEstadualDestinatario" está inválido. O valor deve ter de 2 a 16 caracteres numéricos.'
        ],
        '145' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c19_municipioEmitente" está inválido. O valor deve ter o código do IBGE com 5 caracteres numéricos, sem o código da UF.'
        ],
        '146' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c38_municipioDestinatario" está inválido. O valor deve ter o código do IBGE com 5 caracteres numéricos, sem o código da UF.'
        ],
        '147' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c05_referencia/ano" está inválido. O valor deve ter 4 caracteres numéricos maior ou igual a "1000".'
        ],
        '148' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c39_camposExtras/campoExtra/ ódigo" está inválido. O valor deve ser numérico com o valor máximo de 2147483647!'
        ],
        '149' => [ // Verified
            'type' => 0,
            'message' => 'Foi localizado um conteúdo inválido começando com o campo "[Nome do campo]"! Ou este campo não existe, ou o(s) seguinte(s) campos devem vir antes dele: [Listas de campos]'
        ],
        '150' => [ // Verified
            'type' => 0,
            'message' => 'O campo "c39_camposExtras" só pode ter até 3 campos filhos ("campoExtra")! '
        ],
        '151' => [ // Verified
            'type' => 0,
            'message' => 'O campo "[Nome do Campo]" está numa posição incorreta! Nenhum campo filho esperado neste ponto.'
        ],
        '152' => [ // Verified
            'type' => 0,
            'message' => 'O campo "[Nome do Campo]" não deve ter campos filhos! '
        ],
        '199' => [ // Verified
            'type' => 0,
            'message' => 'Outros erros de validação do XML.'
        ],
        '201' => [ // Verified
            'type' => 7,
            'message' => 'ufFavorecida: Esta UF não gera GNRE online.'
        ],
        '202' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identificação->IE: Informe a Inscrição Estadual do emitente se inscrito na UF favorecida ou o Tipo de Identificação se não inscrito.'
        ],
        '203' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identificação->CNPJ: CNPJ do contribuinte emitente inválido!'
        ],
        '204' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identificação->CPF: CPF do contribuinte emitente inválido!'
        ],
        '205' => [ // Verified
            'type' => 0,
            'message' => 'Item->receita: A UF favorecida não gera GNRE para a Receita informada.'
        ],
        '206' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente: Informe a identificação do contribuinte emitente!'
        ],
        '207' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identificação->CNPJ/contribuinteEmitente->identificação->CPF: Informe o CNPJ ou CPF do contribuinte emitente!'
        ],
        '208' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->razaoSocial: Informe a Razão Social do emitente!'
        ],
        '209' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->endereco: Informe o endereço do emitente!'
        ],
        '210' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->uf: Informe a UF do Endereço do emitente!'
        ],
        '211' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->municipio: Informe o código do Município do emitente!'
        ],
        '212' => [ // Verified
            'type' => 0,
            'message' => 'item->detalhamentoReceita: O Detalhamento da Receita informado não é usado pela Receita informada para a UF favorecida!'
        ],
        '213' => [ // Verified
            'type' => 0,
            'message' => 'item->detalhamentoReceita: A Receita informada exige um Detalhamento!'
        ],
        '214' => [ // Verified
            'type' => 0,
            'message' => 'item->produto: O Produto informado não é usado pela Receita informada na UF favorecida!'
        ],
        '215' => [ // Verified
            'type' => 0,
            'message' => 'item->produto: A Receita informada exige um Produto!'
        ],
        '216' => [ // Verified
            'type' => 0,
            'message' => 'item->documentoOrigem: Documento de Origem não informado!'
        ],
        '217' => [ // Verified
            'type' => 0,
            'message' => 'item->documentoOrigem: O Documento de Origem informado não é usado pela Receita informada na UF favorecida!'
        ],
        '218' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem@tipo: Tipo de Documento de Origem não informado!'
        ],
        '219' => [ // Verified
            'type' => 0,
            'message' => 'item->documentoOrigem@tipo e item->documentoOrigem: Tipo de Documento de Origem e Documento de Origem não informados'
        ],
        '220' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->periodo: Informe o período de apuração!'
        ],
        '221' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->mes: Informe o mês de referência!'
        ],
        '222' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->ano: Informe o ano de referência!'
        ],
        '223' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->parcela: Informe a parcela!'
        ],
        '224' => [ // Verified
            'type' => 0,
            'message' => 'item->dataVencimento: Informe a Data de Vencimento!'
        ],
        '225' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: Informe a Data de Pagamento!'
        ],
        '226' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A Data de Pagamento deve ser maior ou igual a Data Atual!'
        ],
        '227' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11">: Informe o Valor Principal!'
        ],
        '228' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Informe o Valor Total!'
        ],
        '229' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11"> ou item-><valor tipo="22">: Informe o Valor Principal ou o Valor Total!'
        ],
        '230' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificação->IE: Informe a Inscrição Estadual do destinatário se inscrito na UF ou o Tipo de Identificação se não inscrito!'
        ],
        '231' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificação->CNPJ: CNPJ do contribuinte destinatário inválido!'
        ],
        '232' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificação->CPF: CPF do contribuinte destinatário inválido!'
        ],
        '233' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario: Informe a identificação do destinatário!'
        ],
        '234' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificação->CNPJ ou item->contribuinteDestinatario->identificação->CPF: Informe o CNPJ ou CPF do destinatário!'
        ],
        '235' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->razaoSocial: Informe a razão social do destinatário!'
        ],
        '236' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->município: Informe o código do município do destinatário!'
        ],
        '237' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificacao: A informação do contribuinte destinatário não é obrigatória para a receita informada. Caso informe o campo item->contribuinteDestinatario, o campo "item->contribuinteDestinatario->identificação" também deve ser informado!'
        ],
        '238' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") deve ser informado!'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Valor numérico do Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") inválido!'
        ],
        '240' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Data do Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") inválida!'
        ],
        '241' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") deve ter no máximo “ + campoAdicional.getTamanhoMaximo() + “caracteres!'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") foi informado com o tipo incorreto!'
        ],
        '243' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") deve ser informado!'
        ],
        '244' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: Informe a Receita!'
        ],
        '245' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identificação: Tipo do documento de identificação do emitente informado difere do documento informado!'
        ],
        '246' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificacao: Tipo do documento de identificação do destinatário informado difere do documento informado!'
        ],
        '247' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->municipio: Código do município do emitente não cadastrado!'
        ],
        '248' => [ // Verified
            'type' => 0,
            'message' => 'c38 item->contribuinteDestinatario->municipio: Código do município do destinatário não cadastrado para a UF favorecida!'
        ],
        '249' => [ // Verified
            'type' => 3,
            'message' => 'ufFavorecida: Serviço da UF indisponível temporariamente no período de [Data/Hora Inicio] a [Data/Hora Final]'
        ],
        '250' => [
            'type' => 3,
            'message' => 'item->receita: Serviço da UF indisponível temporariamente para esta receita, no período de [Data/Hora Inicio] a [Data/Hora Final]'
        ],
        '251' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identificação->IE: A UF informada esta em contingencia. A Inscrição Estadual não poderá ser informada, apenas o CNPJ ou CPF do emitente.'
        ],
        '252' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificação->IE: A UF informada esta em contingencia. A Inscrição Estadual não poderá ser informada, apenas o CNPJ ou CPF do destinatário.'
        ],
        '253' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia: Referência do tributo não informada!'
        ],
        '254' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->ano: O ano de referência não pode ser menor que o quinto ano anterior ao atual!'
        ],
        '255' => [ // Verified
            'type' => 0,
            'message' => 'item->convenio: Convênio não informado!'
        ],
        '256' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]") deve ter 44 dígitos!'
        ],
        '257' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Dígito verificador inválido do Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]")!'
        ],
        '258' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Código da UF inválido no Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]")!'
        ],
        '259' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: CNPJ do emitente inválido no Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]")!'
        ],
        '260' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Modelo do documento eletrônico inválido no Campo Extra "[Titulo do Campo]" (Código: "[Código do Campo]")!'
        ],
        '261' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11">: Valor principal não pode ser maior que R$ 999.999.999,99!'
        ],
        '262' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="21">: Valor total não pode ser maior que R$ 999.999.999,99!'
        ],
        '263' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento, item->dataVencimento:  Data inválida!'
        ],
        '264' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento, item->dataVencimento: O ano não pode ser superior a 2099!'
        ],
        '265' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento, item->dataVencimento: O ano não pode ser inferior a 2000.'
        ],
        '266' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11">: Valor principal não pode ser igual a R$ 0,00!'
        ],
        '267' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="21": Valor total não pode ser igual a R$ 0,00!'
        ],
        '268' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: A receita 10005-6 só está habilitada nesta UF para empresas de remessas expressas.'
        ],
        '269' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: A receita 10005-6 não está habilitada nesta UF para empresas de remessas expressas.'
        ],
        '270' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->endereco: O Endereço do emitente não deve possuir caracteres inválidos (Ex: TAB, etc)!'
        ],
        '271' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->razaoSocial: A razão social do emitente não deve possuir quando a UF favorecida está em contingência'
        ],
        '272' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario-> razaoSocial: A razão social do destinatário não deve possuir caracteres inválidos (Ex: TAB, etc)!'
        ],
        '273' => [ // Verified
            'type' => 0,
            'message' => 'Item: GNRE Simples não pode ter a quantidade de itens maior que 1.'
        ],
        '274' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplos Documentos de Origem não pode conter itens com receitas diferentes.'
        ],
        '275' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplos Documentos de Origem não pode conter itens com tipos de documentos de origem diferentes.'
        ],
        '276' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplos Documentos de Origem não pode conter itens com documentos de origem iguais.'
        ],
        '277' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplas Receitas não pode ter mais de uma receita igual com o mesmo documento de origem.'
        ],
        '278' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplas Receitas não pode ter mais de uma receita igual com o mesmo periodo de referência.'
        ],
        '279' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="12">: Valor Principal do Fecp não informado!'
        ],
        '280' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Valor Total do Fecp não informado!'
        ],
        '281' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="12"> item-><valor tipo="22">: Deve-se informar ou o Valor Principal do Fecp ou o Valor Total do Fecp!'
        ],
        '282' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11"> item-><valor tipo="12">: Se informado o Valor Principal do tributo, deve- se informar o Valor Principal do Fecp!'
        ],
        '283' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="21"> item-><valor tipo="22">: Se informado o Valor Total do tributo, deve-se informar o Valor Total do Fecp!'
        ],
        '284' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="12">: Valor principal do Fecp não pode ser maior que R$ 999.999.999,99!'
        ],
        '285' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Valor total do Fecp não pode ser maior que R$ 999.999.999,99!'
        ],
        '286' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="12">: Valor principal do Fecp não pode ser igual a R$ 0,00!'
        ],
        '287' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Valor total do Fecp não pode ser igual a R$ 0,00!'
        ],
        '288' => [ // Verified
            'type' => 0,
            'message' => 'ufFavorecida: UF favorecida não está com a versão "%s" do XML de dados habilitada.'
        ],
        '289' => [ // Verified
            'type' => 0,
            'message' => '<TDadosGNRE versao="2.00">: XML de dados não é válido para a versão informada.'
        ],
        '290' => [ // Verified
            'type' => 0,
            'message' => 'valorGNRE: O valor da GNRE diverge do somatório dos valores dos itens'
        ],
        '291' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A UF favorecida está em contingência. Neste caso, a data de pagamento deve estar dentro do mês atual.'
        ],
        '292' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A data de pagamento não pode ser maior que a data de vencimento.'
        ],
        '293' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A data de pagamento não poder ser maior que a data de vencimento (menor data de vencimento dos Pagamentos da Guia).'
        ],
        '294' => [ // Verified
            'type' => 0,
            'message' => 'tipoGnre: Para Guias do tipo Múltiplos Documentos de Origem, não podem existir mais de um pagamento com o mesmo tipo de Documento de Origem, mesmo número e a mesma Receita.'
        ],
        '295' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: item->dataVencimento A data de vencimento deve ser igual à data de validade'
        ],
        '296' => [ // Verified
            'type' => 0,
            'message' => 'Item->referencia->ano: O Ano de Referência deve ser o ano corrente, quando a UF favorecida está em contingência'
        ],
        '297' => [ // Verified
            'type' => 0,
            'message' => 'Item->referencia->mes: O Mês de Referência deve ser o mês corrente, período de [Data/Hora Inicio] a [Data/Hora Final]'
        ],
        '298' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Chave do documento eletrônico inválida. A chave deve ter 44 dígitos!'
        ],
        '299' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Modelo da chave do documento eletrônico inválido'
        ],
        '300' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Chave do documento eletrônico inválida'
        ],
        '301' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: DUIMP - DOCUMENTO ÚNICO DE IMPORTAÇÃO inválido!'
        ],
        '302' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Valor do documento de origem inválido para o tipo informado!'
        ],
        '303' => [ // Verified
            'type' => 0,
            'message' => 'tipoGnre: O Tipo "Múltiplos Doc. de Origem" não está habilitado para a Receita "%s", na UF "%s"'
        ],
        '304' => [ // Verified
            'type' => 0,
            'message' => 'tipoGnre: O Tipo "Múltiplas Receitas" não está habilitado para a Receita "%s", na UF "%s"'
        ],
        '305' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->tipoConsulta: O tipo da consulta não foi informado'
        ],
        '306' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->codBarras: O código de barras não foi informado'
        ],
        '307' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->codBarras: Código de barras inválido'
        ],
        '308' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->numControle: Número de controle não informado'
        ],
        '309' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->numControle: Número de controle deve ter 16 dígitos'
        ],
        '700' => [ // Verified
            'type' => 3,
            'message' => 'Guia invalidada pela UF'
        ],
        '701' => [ // Verified
            'type' => 3,
            'message' => 'Erro na validação do retorno da UF'
        ],
        '702' => [ // Verified
            'type' => 3,
            'message' => 'Guia invalidada pela UF sem motivo informado'
        ],
        '703' => [ // Verified
            'type' => 2,
            'message' => 'Falha na comunicação com a UF'
        ],
        '704' => [ // Verified
            'type' => 0,
            'message' => 'Mensagem de erro da UF'
        ],
        '705' => [ // Verified
            'type' => 4,
            'message' => 'UF sem endereço do serviço cadastrado'
        ],
        '706' => [ // Verified
            'type' => 3,
            'message' => 'Guia invalidada devido à UF estar em contingência'
        ],
        '707' => [ // Verified
            'type' => 2,
            'message' => 'Guia invalidada pela UF. Serão feitas novas tentativas de processamento. Favor consultar o Lote em até XX minutos.'
        ],
        '708' => [ // Verified
            'type' => 2,
            'message' => 'UF não retornou o XML'
        ],
        '709' => [ // Verified
            'type' => 0,
            'message' => 'Guia não localizada'
        ]
    ];
}

