<?php

namespace Wilkques\PKCE;

/**
 * @method static string codeVerifier() get code verifier
 * @method static string codeChallenge() get code challenge
 * @method static static generate() build PKCE
 */
class Generator implements \JsonSerializable, \ArrayAccess
{
    /** @var array */
    protected $code = [];

    /**
     * @param string $key
     * @param string $code
     * 
     * @return static
     */
    public function setCode(string $key, string $code)
    {
        $this->code[$key] = $code;

        return $this;
    }

    /**
     * @param string|null $key
     * 
     * @return mixed
     */
    public function getCode(string $key = null)
    {
        return $key ? $this->code[$key] : $this->code;
    }

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
     * @param string $codeVerifier
     * 
     * @return static
     */
    public function setCodeVerifier(string $codeVerifier)
    {
        return $this->setCode('codeVerifier', $codeVerifier);
    }

    /**
     * @return string
     */
    public function getCodeVerifier()
    {
        return $this->getCode('codeVerifier');
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
        return $this->setCode('codeChallenge', $codeChallenge);
    }

    /**
     * @return string
     */
    public function getCodeChallenge()
    {
        return $this->getCode('codeChallenge');
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
     * @return array
     */
    public function toArray()
    {
        return $this->getCode();
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return !array_key_exists($offset, $this->toArray()) && !is_null($this->__get($offset));
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->toArray()[$offset]);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set(string $key, $value)
    {
        if (in_array($key, ['codeVerifier', 'codeChallenge'])) {
            $this->code[$key] = $value;
        } else {
            $this->{$key} = $value;
        }
    }

    /**
     * @param string $key
     * 
     * @return mixed
     */
    public function __get(string $key)
    {
        if (in_array($key, ['codeVerifier', 'codeChallenge'])) {
            return $this->code[$key];
        }
        
        return $this->{$key};
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return $this->toArray();
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
