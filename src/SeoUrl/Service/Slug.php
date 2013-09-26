<?php
/**
 * Generate SEO URL
 * Class transfer any string with non latin chars to string with only latin chars in it.
 * Usefull to create user friendly SEO URLs.
 * 
 * For example 
 * String IN: "Here is strange string, needs to be converver now!"
 * String OUT: "here-is-strange-string-needs-to-be-converver-now"
 * 
 * @package SeoUrl
 * @subpackage Service
 * @author Aleksander Cyrkulewski
 */
namespace SeoUrl\Service;

class Slug
{

    /**
     * @var array
     */
    protected $config = array();
    
    /**
     * @var string
     */
    protected $separator;
    
    /**
     * @var int
     */
    protected $min_length;
    
    /**
     * @var int
     */
    protected $max_length;
    
    /**
     * @var string
     */
    protected $string_encoding;
    
    /**
     * @var array
     */
    protected $foreign_chars = array();
    
    
    public function __construct($config)
    {
        $this->config           = $config;
        $this->separator        = $config['separator'];
        $this->min_length       = (int) $config['min_length'];
        $this->max_length       = (int) $config['max_length'];
        $this->string_encoding  = $config['string_encoding'];
        $this->foreign_chars    = $config['foreign_chars'];
    }
    

    /**
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

	/**
     * @param int $min_length
     */
    public function setMinLength($min_length)
    {
        $this->min_length = (int) $min_length;
    }

	/**
     * @param int $max_length
     */
    public function setMaxLength($max_length)
    {
        $this->max_length = (int) $max_length;
    }

	/**
     * @param string $string_encoding
     */
    public function setStringEncoding($string_encoding)
    {
        $this->string_encoding = $string_encoding;
    }
    
	/**
     * @param array $foreign_chars
     */
    public function setForeignChars(array $foreign_chars)
    {
        $this->foreign_chars = $foreign_chars;
    }

    
    /**
     * Generate new slug based on given value
     * 
     * @param string $str
     * @throws \Exception
     * @return string
     */
    public function create($str)
    {
        $str = trim($str);
        
        if ($this->validateStr($str) != true) {
            throw new \Exception('String does not have a valid format.');
        }
        
        $separator = $this->separator;
        $q_separator = preg_quote($separator, '#');
        
        $trans = array(
            '&.+?;' => '', 
            '[^a-z0-9 _-]' => '', 
            '\s+' => $separator, 
            '(' . $q_separator . ')+' => $separator,
        );
        
        $str = strip_tags($str);
        $str = $this->convertAccentedCharacters($str);
        foreach ($trans as $key => $val) {
            $str = preg_replace('#' . $key . '#i', $val, $str);
        }
        
        $str = strtolower($str);
        $str = substr($str, 0, $this->max_length);
        return trim(trim($str, $separator));
    }

    
    /**
     * Check if given var is good enought to be a source of slug
     * 
     * @param string $str
     * @throws \Exception
     * @return boolean
     */
    private function validateStr($str)
    {
        if (empty($str)) {
            throw new \Exception('No string was supplied.');
        }
        
        if (! is_string($str)) {
            throw new \Exception('Given variable is not string.');
        }
        
        if (mb_strlen($str, $this->string_encoding) < $this->min_length) {
            throw new \Exception('Given string is too short.');
        }
        
        return true;
    }
    
    /**
     * Transcript any characters to latin
     * 
     * @param string $str
     * @return string
     */
    private function convertAccentedCharacters($str)
    {
        static $array_from, $array_to;
        
        if (! is_array($array_from)) {
            $array_from = array_keys($this->foreign_chars);
            $array_to = array_values($this->foreign_chars);
        }
        
        return preg_replace($array_from, $array_to, $str);
    }
}