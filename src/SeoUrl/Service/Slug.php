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

    public function __construct($config)
    {
        $this->config = $config;
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
        
        $separator = $this->config['separator'];
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
        $str = substr($str, 0, $this->config['max_length']);
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
        
        if (mb_strlen($str, $this->config['string_encoding']) < $this->config['min_length']) {
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
            $array_from = array_keys($this->config['foreign_chars']);
            $array_to = array_values($this->config['foreign_chars']);
        }
        
        return preg_replace($array_from, $array_to, $str);
    }
}