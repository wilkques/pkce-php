<?php

namespace Wilkques\PKCE;

/**
 * @method static string codeVerifier() get code verifier
 * @method static string codeChallenge() get code challenge
 * @method static static generate() build PKCE
 * @method static array force() set access token
 */
class Generator
{
    /** @var string|null */
    protected $codeVerifier = null;
    /** @var string|null */
    protected $codeChallenge = null;

    /**
     * @param int $byteLength A minimum length of 43 characters and a maximum length of 128 characters.
     * 
     * @return static
     */
    public function compilerGenerate(int $byteLength = 43)
    {
        $this->generateCodeVerifier($byteLength);

        $this->generateCodeChallenge(
            $this->getCodeVerifier()
        );

        return $this;
    }

    /**
     * @param int $byteLength A minimum length of 43 characters and a maximum length of 128 characters.
     * 
     * @return string
     */
    public function compilerForce(int $byteLength = 43)
    {
        $this->generate($byteLength);

        return [
            'codeVerifier'  => $this->getCodeVerifier(),
            'codeChallenge' => $this->generateCodeChallenge(),
        ];
    }

    /**
     * @param string $codeVerifier
     * 
     * @return static
     */
    public function setCodeVerifier(string $codeVerifier)
    {
        $this->codeVerifier = $codeVerifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeVerifier()
    {
        return $this->codeVerifier;
    }

    /**
     * @param int $byteLength A minimum length of 43 characters and a maximum length of 128 characters.
     * 
     * @return string
     * 
     * @see https://datatracker.ietf.org/doc/html/rfc7636#section-4.1
     */
    public function generateCodeVerifier(int $byteLength = 43)
    {
        $byteLength = $byteLength < 43 ? 43 : ($byteLength > 128 ? 128 : $byteLength);

        return $this->setCodeVerifier(
            base64UrlEncode(
                openssl_random_pseudo_bytes($byteLength)
            )
        )->getCodeVerifier();
    }

    /**
     * @param string $codeChallenge
     * 
     * @return static
     */
    public function setCodeChallenge(string $codeChallenge)
    {
        $this->codeChallenge = $codeChallenge;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeChallenge()
    {
        return $this->codeChallenge;
    }

    /**
     * @param string|null $codeVerifier
     * 
     * @return string
     * 
     * @see https://datatracker.ietf.org/doc/html/rfc7636#section-4.2
     */
    public function generateCodeChallenge(string $codeVerifier = null)
    {
        return $this->setCodeChallenge(
            base64UrlEncode(
                hash('SHA256', $codeVerifier ?: $this->getCodeVerifier(), true)
            )
        )->getCodeChallenge();
    }

    /**
     * @param string $method
     * @param array $arguments
     * 
     * @return static|Client
     */
    public function __call(string $method, array $arguments)
    {
        $method = ltrim(trim($method));

        in_array($method, ['codeVerifier', 'codeChallenge']) && $method = 'generate' . ucfirst($method);

        in_array($method, ['generate', 'force']) && $method = 'compiler' . ucfirst($method);

        return $this->{$method}(...$arguments);
    }

    /**
     * @param string $method
     * @param array $arguments
     * 
     * @return static
     */
    public static function __callStatic(string $method, array $arguments)
    {
        return (new static)->{$method}(...$arguments);
    }
}
