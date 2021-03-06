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
            'message' => 'Processado com pend??ncia'
        ],
        '404' => [ // Verified
            'type' => 0,
            'message' => 'Lote com pend??ncia de tempo de processamento. As Guias com situa????o 4 (campo <situacaoGuia> para a vers??o 2.00) podem levar em m??dia 20 minutos, e no m??ximo 1 hora para serem processadas. Erro no processamento do lote. Enviar o lote novamente.'
        ],
        '601' => [ // Verified
            'type' => 0,
            'message' => 'O ambiente solicitado difere do ambiente do webservice'
        ],
        '602' => [ // Verified
            'type' => 0,
            'message' => 'N??mero do recibo n??o cadastrado'
        ],
        '603' => [ // Verified
            'type' => 0,
            'message' => 'CNPJ do recibo difere do CNPJ do contribuinte'
        ],
        '604' => [ // Verified
            'type' => 0,
            'message' => 'Erro ao gerar arquivo de c??digo de barras'
        ],
        '450' => [ // Verified
            'type' => 1,
            'message' => 'Consulta da configura????o da UF realizada com sucesso.'
        ],
        '451' => [ // Verified
            'type' => 0,
            'message' => 'Receita n??o cadastrada!'
        ],
        '452' => [ // Verified
            'type' => 0,
            'message' => 'Receita n??o relacionada a esta UF'
        ],
        '453' => [ // Verified
            'type' => 4,
            'message' => 'UF n??o cadastrada neste ambiente!'
        ],
        '454' => [ // Verified
            'type' => 4,
            'message' => 'UF n??o habilitada neste ambiente!'
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
            'message' => 'Certificado inv??lido'
        ],
        '102' => [ // Verified
            'type' => 5,
            'message' => 'CNPJ n??o habilitado para uso do servi??o.'
        ],
        '103' => [ // Verified
            'type' => 0,
            'message' => 'Mensagem excedeu o tamanho m??ximo de 600KB.'
        ],
        '153' => [ // Verified
            'type' => 0,
            'message' => 'A quantidade de guias no lote n??o pode ser maior que xxx! (vide servi??o de Consulta de Configura????es ??? GnreConfigUF)'
        ],
        '154' => [ // Verified
            'type' => 5,
            'message' => 'CNPJ bloqueado para uso do servi??o.'
        ],
        '155' => [ // Verified
            'type' => 0,
            'message' => 'A quantidade de itens no lote n??o pode ser maior que xxx! (vide servi??o de Consulta de Configura????es ??? GnreConfigUF)'
        ],
        '156' => [ // Verified
            'type' => 0,
            'message' => 'XML de Lote inv??lido'
        ],
        '157' => [ // Verified
            'type' => 0,
            'message' => 'Vers??o do cabe??alho informada difere da vers??o do XML de Dados!'
        ],
        '197' => [ // Verified
            'type' => 0,
            'message' => 'Erro ao recepcionar o lote'
        ],
        '198' => [ // Verified
            'type' => 6,
            'message' => 'Este servi??o deve usar uma conex??o HTTPS Segura! Tente novamente utilizando seu Certificado Digital.'
        ],
        '199' => [ // Verified
            'type' => 3,
            'message' => 'A quantidade de consultas no lote nao pode ser maior que xx. (vide servi??o de Consulta de Configura????es - GnreConfigUF)'
        ],
        '104' => [ // Verified
            'type' => 0,
            'message' => 'Conte??do do XML de dados inv??lido!'
        ],
        '105' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c01_UfFavorecida" est?? inv??lido! Deve ser um destes valores: [AC, AL, AM, AP, BA, CE, DF, ES, GO, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, TO]'
        ],
        '106' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c20_ufEnderecoEmitente" est?? inv??lido! Deve ser um destes valores: [AC, AL, AM, AP, BA, CE, DF, ES, GO, MA, MG, MS, MT, PA, PB, PE, PI, PR, RJ, RN, RO, RR, RS, SC, SE, SP, TO]'
        ],
        '107' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c05_referencia/ ??digo " est?? inv??lido! Deve ser um destes valores: [0, 1, 2, 3, 4, 5]'
        ],
        '108' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c05_referencia/mes" est?? inv??lido! Deve ser um destes valores: [01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12]'
        ],
        '109' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c27_tipoIdentificacaoEmitente" est?? inv??lido! Deve ser um destes valores: [1, 2]'
        ],
        '110' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c34_tipoIdentificacaoDestinatario" est?? inv??lido! Deve ser um destes valores: [1, 2]'
        ],
        '111' => [ // Verified
            'type' => 0,
            'message' => 'O valor para o campo "c39_camposExtras/campoExtra/tipo" est?? inv??lido! Deveser um destes valores: [T, N, D]'
        ],
        '114' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c15_convenio" n??o deve ser maior que 30 caracteres!'
        ],
        '115' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c16_razaoSocialEmitente" n??o deve ser maior que 60 caracteres!'
        ],
        '116' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c18_enderecoEmitente" n??o deve ser maior que 60 caracteres!'
        ],
        '117' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c37_razaoSocialDestinatario" n??o deve ser maior que 60 caracteres!'
        ],
        '118' => [ // Verified
            'type' => 0,
            'message' => 'O tamanho do valor do campo "c39_camposExtras/campoExtra/valor" n??o deve ser maior que 100 caracteres!'
        ],
        '119' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c15_convenio" est?? inv??lido. O valor n??o deve ter o caractere espa??o no in??cio nem no final do texto, bem como n??o deve haver quebras de linha!'
        ],
        '120' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c16_razaoSocialEmitente" est?? inv??lido. O valor n??o deve ter o caractere espa??o no in??cio nem no final do texto, bem como n??o deve haver quebras de linha!'
        ],
        '121' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c18_enderecoEmitente" est?? inv??lido. O valor n??o deve ter o caractere espa??o no in??cio nem no final do texto, bem como n??o deve haver quebras de linha!'
        ],
        '122' => [
            'type' => 0,
            'message' => 'O valor do campo "c37_razaoSocialDestinatario" est?? inv??lido. O valor n??o deve ter o caractere espa??o no in??cio nem no final do texto, bem como n??o deve haver quebras de linha!'
        ],
        '123' => [
            'type' => 0,
            'message' => 'O valor do campo "c02_receita" est?? inv??lido. O valor deve ter 6 caracteres num??ricos.'
        ],
        '124' => [
            'type' => 0,
            'message' => 'O valor do campo "c25_detalhamentoReceita" est?? inv??lido. O valor deve ter 6 caracteres num??ricos.'
        ],
        '125' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c25_detalhamentoReceita" est?? inv??lido. O valor deve ter at?? 4 caracteres num??ricos, com valor entre "1" e "9999"'
        ],
        '126' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c28_tipoDocOrigem" est?? inv??lido. O valor deve ter 2 caracteres num??ricos.'
        ],
        '127' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c04_docOrigem" est?? inv??lido. O valor deve ter entre 1 e 18 caracteres num??ricos.'
        ],
        '128' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c21_cepEmitente" est?? inv??lido. O valor deve ter 8 caracteres num??ricos.'
        ],
        '129' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c22_telefoneEmitente" est?? inv??lido. O valor deve ter entre 6 e 11 caracteres num??ricos.'
        ],
        '130' => [
            'type' => 0,
            'message' => 'O valor do campo "c05_referencia/parcela" est?? inv??lido. O valor deve ter entre 1 e 3 caracteres num??ricos, com valor entre "1" e "999".'
        ],
        '131' => [
            'type' => 0,
            'message' => 'O valor do campo "c03_idContribuinteEmitente/CNPJ" est?? inv??lido. O valor deve ter 14 caracteres num??ricos.'
        ],
        '132' => [
            'type' => 0,
            'message' => 'O valor do campo "c35_idContribuinteDestinatario/CNPJ" est?? inv??lido. O valor deve ter 14 caracteres num??ricos.'
        ],
        '133' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c03_idContribuinteEmitente/CPF" est?? inv??lido. O valor deve ter 11 caracteres num??ricos.'
        ],
        '134' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c35_idContribuinteDestinatario/CPF" est?? inv??lido. O valor deve ter 11 caracteres num??ricos.'
        ],
        '135' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c06_valorPrincipal" est?? inv??lido. O valor deve estar no seguinte formato: "[0.00 a 9999999999.99]" ou "0".'
        ],
        '136' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c10_valorTotal" est?? inv??lido. O valor deve estar no seguinte formato: "[0.00 a 9999999999.99]" ou "0".'
        ],
        '140' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c14_dataVencimento" est?? inv??lido. O valor deve ser uma data v??lida no formato AAAA-MM-DD, com ano maior ou igual a "2000" e menor que "2100".'
        ],
        '141' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c33_dataPagamento" est?? inv??lido. O valor deve ser uma data v??lida no formato AAAA-MM-DD, com ano maior ou igual a "2000 e menor que "2100".'
        ],
        '143' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c17_inscricaoEstadualEmitente" est?? inv??lido. O valor deve ter de 2 a 16 caracteres num??ricos.'
        ],
        '144' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c36_inscricaoEstadualDestinatario" est?? inv??lido. O valor deve ter de 2 a 16 caracteres num??ricos.'
        ],
        '145' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c19_municipioEmitente" est?? inv??lido. O valor deve ter o c??digo do IBGE com 5 caracteres num??ricos, sem o c??digo da UF.'
        ],
        '146' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c38_municipioDestinatario" est?? inv??lido. O valor deve ter o c??digo do IBGE com 5 caracteres num??ricos, sem o c??digo da UF.'
        ],
        '147' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c05_referencia/ano" est?? inv??lido. O valor deve ter 4 caracteres num??ricos maior ou igual a "1000".'
        ],
        '148' => [ // Verified
            'type' => 0,
            'message' => 'O valor do campo "c39_camposExtras/campoExtra/ ??digo" est?? inv??lido. O valor deve ser num??rico com o valor m??ximo de 2147483647!'
        ],
        '149' => [ // Verified
            'type' => 0,
            'message' => 'Foi localizado um conte??do inv??lido come??ando com o campo "[Nome do campo]"! Ou este campo n??o existe, ou o(s) seguinte(s) campos devem vir antes dele: [Listas de campos]'
        ],
        '150' => [ // Verified
            'type' => 0,
            'message' => 'O campo "c39_camposExtras" s?? pode ter at?? 3 campos filhos ("campoExtra")! '
        ],
        '151' => [ // Verified
            'type' => 0,
            'message' => 'O campo "[Nome do Campo]" est?? numa posi????o incorreta! Nenhum campo filho esperado neste ponto.'
        ],
        '152' => [ // Verified
            'type' => 0,
            'message' => 'O campo "[Nome do Campo]" n??o deve ter campos filhos! '
        ],
        '199' => [ // Verified
            'type' => 0,
            'message' => 'Outros erros de valida????o do XML.'
        ],
        '201' => [ // Verified
            'type' => 7,
            'message' => 'ufFavorecida: Esta UF n??o gera GNRE online.'
        ],
        '202' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identifica????o->IE: Informe a Inscri????o Estadual do emitente se inscrito na UF favorecida ou o Tipo de Identifica????o se n??o inscrito.'
        ],
        '203' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identifica????o->CNPJ: CNPJ do contribuinte emitente inv??lido!'
        ],
        '204' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identifica????o->CPF: CPF do contribuinte emitente inv??lido!'
        ],
        '205' => [ // Verified
            'type' => 0,
            'message' => 'Item->receita: A UF favorecida n??o gera GNRE para a Receita informada.'
        ],
        '206' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente: Informe a identifica????o do contribuinte emitente!'
        ],
        '207' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identifica????o->CNPJ/contribuinteEmitente->identifica????o->CPF: Informe o CNPJ ou CPF do contribuinte emitente!'
        ],
        '208' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->razaoSocial: Informe a Raz??o Social do emitente!'
        ],
        '209' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->endereco: Informe o endere??o do emitente!'
        ],
        '210' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->uf: Informe a UF do Endere??o do emitente!'
        ],
        '211' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->municipio: Informe o c??digo do Munic??pio do emitente!'
        ],
        '212' => [ // Verified
            'type' => 0,
            'message' => 'item->detalhamentoReceita: O Detalhamento da Receita informado n??o ?? usado pela Receita informada para a UF favorecida!'
        ],
        '213' => [ // Verified
            'type' => 0,
            'message' => 'item->detalhamentoReceita: A Receita informada exige um Detalhamento!'
        ],
        '214' => [ // Verified
            'type' => 0,
            'message' => 'item->produto: O Produto informado n??o ?? usado pela Receita informada na UF favorecida!'
        ],
        '215' => [ // Verified
            'type' => 0,
            'message' => 'item->produto: A Receita informada exige um Produto!'
        ],
        '216' => [ // Verified
            'type' => 0,
            'message' => 'item->documentoOrigem: Documento de Origem n??o informado!'
        ],
        '217' => [ // Verified
            'type' => 0,
            'message' => 'item->documentoOrigem: O Documento de Origem informado n??o ?? usado pela Receita informada na UF favorecida!'
        ],
        '218' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem@tipo: Tipo de Documento de Origem n??o informado!'
        ],
        '219' => [ // Verified
            'type' => 0,
            'message' => 'item->documentoOrigem@tipo e item->documentoOrigem: Tipo de Documento de Origem e Documento de Origem n??o informados'
        ],
        '220' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->periodo: Informe o per??odo de apura????o!'
        ],
        '221' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->mes: Informe o m??s de refer??ncia!'
        ],
        '222' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->ano: Informe o ano de refer??ncia!'
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
            'message' => 'item->contribuinteDestinatario->identifica????o->IE: Informe a Inscri????o Estadual do destinat??rio se inscrito na UF ou o Tipo de Identifica????o se n??o inscrito!'
        ],
        '231' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identifica????o->CNPJ: CNPJ do contribuinte destinat??rio inv??lido!'
        ],
        '232' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identifica????o->CPF: CPF do contribuinte destinat??rio inv??lido!'
        ],
        '233' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario: Informe a identifica????o do destinat??rio!'
        ],
        '234' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identifica????o->CNPJ ou item->contribuinteDestinatario->identifica????o->CPF: Informe o CNPJ ou CPF do destinat??rio!'
        ],
        '235' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->razaoSocial: Informe a raz??o social do destinat??rio!'
        ],
        '236' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->munic??pio: Informe o c??digo do munic??pio do destinat??rio!'
        ],
        '237' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificacao: A informa????o do contribuinte destinat??rio n??o ?? obrigat??ria para a receita informada. Caso informe o campo item->contribuinteDestinatario, o campo "item->contribuinteDestinatario->identifica????o" tamb??m deve ser informado!'
        ],
        '238' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") deve ser informado!'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Valor num??rico do Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") inv??lido!'
        ],
        '240' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Data do Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") inv??lida!'
        ],
        '241' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") deve ter no m??ximo ??? + campoAdicional.getTamanhoMaximo() + ???caracteres!'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") foi informado com o tipo incorreto!'
        ],
        '243' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") deve ser informado!'
        ],
        '244' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: Informe a Receita!'
        ],
        '245' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identifica????o: Tipo do documento de identifica????o do emitente informado difere do documento informado!'
        ],
        '246' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identificacao: Tipo do documento de identifica????o do destinat??rio informado difere do documento informado!'
        ],
        '247' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->municipio: C??digo do munic??pio do emitente n??o cadastrado!'
        ],
        '248' => [ // Verified
            'type' => 0,
            'message' => 'c38 item->contribuinteDestinatario->municipio: C??digo do munic??pio do destinat??rio n??o cadastrado para a UF favorecida!'
        ],
        '249' => [ // Verified
            'type' => 3,
            'message' => 'ufFavorecida: Servi??o da UF indispon??vel temporariamente no per??odo de [Data/Hora Inicio] a [Data/Hora Final]'
        ],
        '250' => [
            'type' => 3,
            'message' => 'item->receita: Servi??o da UF indispon??vel temporariamente para esta receita, no per??odo de [Data/Hora Inicio] a [Data/Hora Final]'
        ],
        '251' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->identifica????o->IE: A UF informada esta em contingencia. A Inscri????o Estadual n??o poder?? ser informada, apenas o CNPJ ou CPF do emitente.'
        ],
        '252' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario->identifica????o->IE: A UF informada esta em contingencia. A Inscri????o Estadual n??o poder?? ser informada, apenas o CNPJ ou CPF do destinat??rio.'
        ],
        '253' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia: Refer??ncia do tributo n??o informada!'
        ],
        '254' => [ // Verified
            'type' => 0,
            'message' => 'item->referencia->ano: O ano de refer??ncia n??o pode ser menor que o quinto ano anterior ao atual!'
        ],
        '255' => [ // Verified
            'type' => 0,
            'message' => 'item->convenio: Conv??nio n??o informado!'
        ],
        '256' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: O Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]") deve ter 44 d??gitos!'
        ],
        '257' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: D??gito verificador inv??lido do Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]")!'
        ],
        '258' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: C??digo da UF inv??lido no Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]")!'
        ],
        '259' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: CNPJ do emitente inv??lido no Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]")!'
        ],
        '260' => [ // Verified
            'type' => 0,
            'message' => 'item->camposExtras->campoExtra: Modelo do documento eletr??nico inv??lido no Campo Extra "[Titulo do Campo]" (C??digo: "[C??digo do Campo]")!'
        ],
        '261' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11">: Valor principal n??o pode ser maior que R$ 999.999.999,99!'
        ],
        '262' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="21">: Valor total n??o pode ser maior que R$ 999.999.999,99!'
        ],
        '263' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento, item->dataVencimento:  Data inv??lida!'
        ],
        '264' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento, item->dataVencimento: O ano n??o pode ser superior a 2099!'
        ],
        '265' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento, item->dataVencimento: O ano n??o pode ser inferior a 2000.'
        ],
        '266' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="11">: Valor principal n??o pode ser igual a R$ 0,00!'
        ],
        '267' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="21": Valor total n??o pode ser igual a R$ 0,00!'
        ],
        '268' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: A receita 10005-6 s?? est?? habilitada nesta UF para empresas de remessas expressas.'
        ],
        '269' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: A receita 10005-6 n??o est?? habilitada nesta UF para empresas de remessas expressas.'
        ],
        '270' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->endereco: O Endere??o do emitente n??o deve possuir caracteres inv??lidos (Ex: TAB, etc)!'
        ],
        '271' => [ // Verified
            'type' => 0,
            'message' => 'contribuinteEmitente->razaoSocial: A raz??o social do emitente n??o deve possuir quando a UF favorecida est?? em conting??ncia'
        ],
        '272' => [ // Verified
            'type' => 0,
            'message' => 'item->contribuinteDestinatario-> razaoSocial: A raz??o social do destinat??rio n??o deve possuir caracteres inv??lidos (Ex: TAB, etc)!'
        ],
        '273' => [ // Verified
            'type' => 0,
            'message' => 'Item: GNRE Simples n??o pode ter a quantidade de itens maior que 1.'
        ],
        '274' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplos Documentos de Origem n??o pode conter itens com receitas diferentes.'
        ],
        '275' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplos Documentos de Origem n??o pode conter itens com tipos de documentos de origem diferentes.'
        ],
        '276' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplos Documentos de Origem n??o pode conter itens com documentos de origem iguais.'
        ],
        '277' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplas Receitas n??o pode ter mais de uma receita igual com o mesmo documento de origem.'
        ],
        '278' => [ // Verified
            'type' => 0,
            'message' => 'item->receita: GNRE Multiplas Receitas n??o pode ter mais de uma receita igual com o mesmo periodo de refer??ncia.'
        ],
        '279' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="12">: Valor Principal do Fecp n??o informado!'
        ],
        '280' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Valor Total do Fecp n??o informado!'
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
            'message' => 'item-><valor tipo="12">: Valor principal do Fecp n??o pode ser maior que R$ 999.999.999,99!'
        ],
        '285' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Valor total do Fecp n??o pode ser maior que R$ 999.999.999,99!'
        ],
        '286' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="12">: Valor principal do Fecp n??o pode ser igual a R$ 0,00!'
        ],
        '287' => [ // Verified
            'type' => 0,
            'message' => 'item-><valor tipo="22">: Valor total do Fecp n??o pode ser igual a R$ 0,00!'
        ],
        '288' => [ // Verified
            'type' => 0,
            'message' => 'ufFavorecida: UF favorecida n??o est?? com a vers??o "%s" do XML de dados habilitada.'
        ],
        '289' => [ // Verified
            'type' => 0,
            'message' => '<TDadosGNRE versao="2.00">: XML de dados n??o ?? v??lido para a vers??o informada.'
        ],
        '290' => [ // Verified
            'type' => 0,
            'message' => 'valorGNRE: O valor da GNRE diverge do somat??rio dos valores dos itens'
        ],
        '291' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A UF favorecida est?? em conting??ncia. Neste caso, a data de pagamento deve estar dentro do m??s atual.'
        ],
        '292' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A data de pagamento n??o pode ser maior que a data de vencimento.'
        ],
        '293' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: A data de pagamento n??o poder ser maior que a data de vencimento (menor data de vencimento dos Pagamentos da Guia).'
        ],
        '294' => [ // Verified
            'type' => 0,
            'message' => 'tipoGnre: Para Guias do tipo M??ltiplos Documentos de Origem, n??o podem existir mais de um pagamento com o mesmo tipo de Documento de Origem, mesmo n??mero e a mesma Receita.'
        ],
        '295' => [ // Verified
            'type' => 0,
            'message' => 'dataPagamento: item->dataVencimento A data de vencimento deve ser igual ?? data de validade'
        ],
        '296' => [ // Verified
            'type' => 0,
            'message' => 'Item->referencia->ano: O Ano de Refer??ncia deve ser o ano corrente, quando a UF favorecida est?? em conting??ncia'
        ],
        '297' => [ // Verified
            'type' => 0,
            'message' => 'Item->referencia->mes: O M??s de Refer??ncia deve ser o m??s corrente, per??odo de [Data/Hora Inicio] a [Data/Hora Final]'
        ],
        '298' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Chave do documento eletr??nico inv??lida. A chave deve ter 44 d??gitos!'
        ],
        '299' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Modelo da chave do documento eletr??nico inv??lido'
        ],
        '300' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Chave do documento eletr??nico inv??lida'
        ],
        '301' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: DUIMP - DOCUMENTO ??NICO DE IMPORTA????O inv??lido!'
        ],
        '302' => [ // Verified
            'type' => 0,
            'message' => 'Item->documentoOrigem: Valor do documento de origem inv??lido para o tipo informado!'
        ],
        '303' => [ // Verified
            'type' => 0,
            'message' => 'tipoGnre: O Tipo "M??ltiplos Doc. de Origem" n??o est?? habilitado para a Receita "%s", na UF "%s"'
        ],
        '304' => [ // Verified
            'type' => 0,
            'message' => 'tipoGnre: O Tipo "M??ltiplas Receitas" n??o est?? habilitado para a Receita "%s", na UF "%s"'
        ],
        '305' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->tipoConsulta: O tipo da consulta n??o foi informado'
        ],
        '306' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->codBarras: O c??digo de barras n??o foi informado'
        ],
        '307' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->codBarras: C??digo de barras inv??lido'
        ],
        '308' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->numControle: N??mero de controle n??o informado'
        ],
        '309' => [ // Verified
            'type' => 0,
            'message' => 'Consulta->numControle: N??mero de controle deve ter 16 d??gitos'
        ],
        '700' => [ // Verified
            'type' => 3,
            'message' => 'Guia invalidada pela UF'
        ],
        '701' => [ // Verified
            'type' => 3,
            'message' => 'Erro na valida????o do retorno da UF'
        ],
        '702' => [ // Verified
            'type' => 3,
            'message' => 'Guia invalidada pela UF sem motivo informado'
        ],
        '703' => [ // Verified
            'type' => 2,
            'message' => 'Falha na comunica????o com a UF'
        ],
        '704' => [ // Verified
            'type' => 0,
            'message' => 'Mensagem de erro da UF'
        ],
        '705' => [ // Verified
            'type' => 4,
            'message' => 'UF sem endere??o do servi??o cadastrado'
        ],
        '706' => [ // Verified
            'type' => 3,
            'message' => 'Guia invalidada devido ?? UF estar em conting??ncia'
        ],
        '707' => [ // Verified
            'type' => 2,
            'message' => 'Guia invalidada pela UF. Ser??o feitas novas tentativas de processamento. Favor consultar o Lote em at?? XX minutos.'
        ],
        '708' => [ // Verified
            'type' => 2,
            'message' => 'UF n??o retornou o XML'
        ],
        '709' => [ // Verified
            'type' => 0,
            'message' => 'Guia n??o localizada'
        ]
    ];
}

