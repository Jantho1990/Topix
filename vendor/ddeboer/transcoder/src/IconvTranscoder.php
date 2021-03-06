<?php

namespace Ddeboer\Transcoder;

use Ddeboer\Transcoder\Exception\ExtensionMissingException;
use Ddeboer\Transcoder\Exception\IllegalCharacterException;
use Ddeboer\Transcoder\Exception\UnsupportedEncodingException;

class IconvTranscoder implements TranscoderInterface
{
    private $defaultEncoding;

    public function __construct($defaultEncoding = 'UTF-8')
    {
        if (!function_exists('iconv')) {
            throw new ExtensionMissingException('iconv');
        }

        $this->defaultEncoding = $defaultEncoding;
    }

    /**
     * {@inheritdoc}
     */
    public function transcode($string, $from = null, $to = null)
    {
        set_error_handler(
            function ($no, $message) use ($string) {
                if (1 === preg_match('/Wrong charset, conversion (.+) is/', $message, $matches)) {
                    throw new UnsupportedEncodingException($matches[1], $message);
                } else {
                  //echo $from . "\n";
                    echo mb_detect_encoding($string);
                    throw new IllegalCharacterException($string, $message);
                }
            },
            E_NOTICE | E_USER_NOTICE
        );
        //var_dump(func_get_args());
        //echo $from . "\n";
        $result = iconv($from === 'us-ascii' ? 'ISO-8859-1' : $from, $to ?: $this->defaultEncoding, $string);
        restore_error_handler();

        return $result;
    }
}
