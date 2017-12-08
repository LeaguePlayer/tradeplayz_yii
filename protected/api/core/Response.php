<?php

class Response extends ApiComponent
{
    const FORMAT_RAW = 'raw';
    const FORMAT_JSON = 'json';
    const FORMAT_DEBUG = 'debug';

    /**
     * @var integer the exit status. Exit statuses should be in the range 0 to 254.
     * The status 0 means the program terminates successfully.
     */
    public $exitStatus = 0;


    public $format = self::FORMAT_JSON;
    /**
     * @var string the response content. When [[data]] is not null, it will be converted into [[content]]
     * according to [[format]] when the response is being sent out.
     * @see data
     */
    public $content;
    /**
     * @var string the charset of the text response. If not set, it will use
     * the value of [[Application::charset]].
     */
    public $charset;
    /**
     * @var string the HTTP status description that comes together with the status code.
     * @see httpStatuses
     */
    public $statusText = 'OK';
    /**
     * @var string the version of the HTTP protocol to use. If not set, it will be determined via `$_SERVER['SERVER_PROTOCOL']`,
     * or '1.1' if that is not available.
     */
    public $version;
    /**
     * @var boolean whether the response has been sent. If this is true, calling [[send()]] will do nothing.
     */
    public $isSent = false;
    /**
     * @var array list of HTTP status codes and the corresponding texts
     */
    public static $httpStatuses = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        304 => 'Not Modified',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
    );


    protected $formatters;



    /**
     * @var integer the HTTP status code to send with the response.
     */
    private $_statusCode = 200;

    /**
     * Initializes this component.
     */
    public function __construct()
    {
        if ($this->version === null) {
            if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
                $this->version = '1.0';
            } else {
                $this->version = '1.1';
            }
        }
        if ($this->charset === null) {
            $this->charset = 'windows-1251';
        }
        $formatters = $this->getDefaultFormatters();
        $this->formatters = empty($this->formatters) ? $formatters : array_merge($formatters, $this->formatters);
    }


    /**
     * @var mixed the original response data. When this is not null, it will be converted into [[content]]
     * according to [[format]] when the response is being sent out.
     * @see content
     */
    private $_data;

    public function getData()
    {
        if ( $this->_data === null && $this->format !== self::FORMAT_RAW ) {
            $this->_data = array(
                'result' => 1,
                // 'error_code' => 0,
                // 'error_message' => '',
                'response' => array()
            );
        }
        return $this->_data;
    }


    /**
     * @param $data
     */
    public function setData($data)
    {
        switch ( $this->format ) {
            case self::FORMAT_JSON:
            case self::FORMAT_DEBUG:
                $this->getData();
                if ( !is_array($data) && !is_object($data) ) {
                    $data = array('content' => $data);
                }
                $this->_data['response'] = $data;
                break;
            default:
                $this->_data = $data;
                break;
        }
    }


    /**
     * @param $code
     * @param $error
     */
    public function setError(Exception $e)
    {
        if ( $this->format !== self::FORMAT_RAW ) {
            $this->getData();
            $this->_data['result'] = 0;
            $this->_data['error_code'] = $e->getCode();
            $this->_data['error_message'] = $e->getMessage();
        }
    }


    /**
     * @return integer the HTTP status code to send with the response.
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * Sets the response status code.
     * This method will set the corresponding status text if `$text` is null.
     * @param integer $value the status code
     * @param string $text the status text. If not set, it will be set automatically based on the status code.
     */
    public function setStatusCode($value, $text = null)
    {
        if ($value === null) {
            $value = 200;
        }
        $this->_statusCode = (int) $value;
        if ($text === null) {
            $this->statusText = isset(static::$httpStatuses[$this->_statusCode]) ? static::$httpStatuses[$this->_statusCode] : '';
        } else {
            $this->statusText = $text;
        }
    }

    /**
     * Sends the response to the client.
     */
    public function send()
    {
        if ($this->isSent) {
            return;
        }
        $this->prepare();
        $this->sendHeaders();
        $this->sendContent();
        $this->isSent = true;
    }

    /**
     * Clears the headers, cookies, content, status code of the response.
     */
    public function clear()
    {
        $this->_statusCode = 200;
        $this->statusText = 'OK';
        $this->_data = null;
        $this->content = null;
        $this->isSent = false;
    }

    /**
     * Sends the response headers to the client
     */
    protected function sendHeaders()
    {
        if (headers_sent()) {
            return;
        }
        $statusCode = $this->getStatusCode();
        header("HTTP/{$this->version} $statusCode {$this->statusText}");
        switch ( $this->format ) {
            case self::FORMAT_JSON:
                header('Content-Type: application/json; charset=utf-8');
                break;
            case self::FORMAT_DEBUG:
                header('Content-Type: text/html; charset=utf-8');
                break;
            default:
                break;
        }
    }

    /**
     *
     */
    protected function sendContent()
    {
        echo $this->content;
    }


    /**
     * @return array
     */
    protected function getDefaultFormatters()
    {
        return array(
            self::FORMAT_JSON => 'JsonFormatter',
            self::FORMAT_DEBUG => 'DebugFormatter',
        );
    }


    /**
     * @throws ApiException
     */
    protected function prepare()
    {
        $data = $this->getData();
        if ( $data === null ) {
            return;
        }

        if (isset($this->formatters[$this->format])) {
            $formatter = new $this->formatters[$this->format];
            if ($formatter instanceof Formatter) {
                $this->content = $formatter->format($data);
            } else {
                $format = $this->format;
                $this->format = self::FORMAT_DEBUG;
                throw new ApiException("Формат '$format' не поддерживается.");
            }
        } elseif ($this->format === self::FORMAT_RAW) {
            $this->content = $data;
        } else {
            $format = $this->format;
            $this->format = self::FORMAT_DEBUG;
            throw new ApiException("Формат '$format' не поддерживается.");
        }
    }
}