<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Support\Message\System\ {
    SystemMessage,
    Message
};
use Inspire\Core\Security\Certificate\PublicKey;
use Inspire\Core\Security\Certificate\CertificateChain;

/**
 * Description of Certificate
 *
 * @author aalves
 */
class Certificate
{

    /**
     * Path to private key file
     *
     * @var string
     */
    private string $priKeyFile;

    /**
     * Path to public key file
     *
     * @var string
     */
    private string $pubKeyFile;

    /**
     * Path to certificate file
     *
     * @var string
     */
    private string $certFile;

    /**
     * Path to certificate PFX file
     *
     * @var string
     */
    private string $pfxFile;

    /**
     * Password of certificate
     *
     * @var string
     */
    private string $pass;

    /**
     *
     * @param string $priKeyFile
     * @param string $pubKeyFile
     * @param string $certFile
     * @param string $pass
     */
    public function __construct(string $priKeyFile, string $pubKeyFile, string $certFile)
    {
        $folders = array_unique([
            dirname($priKeyFile),
            dirname($pubKeyFile),
            dirname($certFile)
        ]);
        foreach ($folders as $folder) {
            if (! file_exists($folder) && ! mkdir($folder, 0755, true)) {
                throw new \Exception("Directory {$folder} does not exists and was not possible to create it.");
            }
        }
        $this->priKeyFile = $priKeyFile;
        $this->pubKeyFile = $pubKeyFile;
        $this->certFile = $certFile;
    }

    /**
     * Check if certificate is valid
     *
     * @return SystemMessage
     */
    public function check(string $pfxFile, string $pass): SystemMessage
    {
        $this->pfxFile = $pfxFile;
        $this->pass = $pass;
        if (! file_exists($this->pfxFile) || ! is_readable($this->pfxFile) || ! is_file($this->pfxFile)) {
            return new SystemMessage("PFX file not found.", // Message
            '100', // System code
            Message::MSG_ERROR, // Message type
            false);
        }
        if (! file_exists($this->pubKeyFile)) {
            $certs = [];
            if (openssl_pkcs12_read(file_get_contents($this->pfxFile), $certs, $pass)) {
                $certs["chain"] = CertificateChain::fetchForCertificate(new \Inspire\Core\Security\Certificate\Certificate($certs["cert"]));
                file_put_contents($this->priKeyFile, $certs["pkey"]);
                file_put_contents($this->pubKeyFile, $certs["cert"]);
                file_put_contents($this->certFile, "{$certs["pkey"]}{$certs["cert"]}{$certs["chain"]}");
            }
        }
        /**
         * Check if certificate is valid and is not expired
         */
        if (file_exists($this->pubKeyFile)) {
            $context = new PublicKey(file_get_contents($this->pubKeyFile));
            if ($context->isExpired()) {
                return new SystemMessage("Digital certificate expired on {$context->validTo->format('Y-m-d H:i:s')}", // Message
                '100', // System code
                Message::MSG_ERROR, // Message type
                false);
            }
        } else {
            return new SystemMessage("Digital certificate not found.", // Message
            '100', // System code
            Message::MSG_ERROR, // Message type
            false);
        }
        return new SystemMessage("Digital certificate OK.", // Message
        '100', // System code
        Message::MSG_OK, // Message type
        true, // Status
        [
            'expiresIn' => $context->validTo->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get public key contents
     *
     * @return string|NULL
     */
    public function getPubKey(): ?string
    {
        return file_get_contents($this->pubKeyFile);
    }

    /**
     * Get public key contents
     *
     * @return string|NULL
     */
    public function getCertKeyFile(): ?string
    {
        return $this->certFile;
    }

    /**
     * Get public key contents
     *
     * @return string|NULL
     */
    public function getPriKeyFile(): ?string
    {
        return $this->priKeyFile;
    }
}

