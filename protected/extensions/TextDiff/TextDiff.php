<?php

// If this is not set then the pear classes throws
// lots of strict standards errors
if(!defined('TEXTDIFF_DISABLE_ERROR_REPORTING')) {
    error_reporting(0);
}

// The pear library should be included to get this working
if(!defined('TEXTDIFF_DISABLE_PEAR_INCLUDE')) {
    include_once('Text/Diff.php');
    include_once('Text/Diff/Renderer.php');
    include_once('Text/Diff/Renderer/unified.php');
    include_once('Text/Diff/Renderer.php');
}
/**
 * PHP 5 Text Difference Wrapper
 * -----------------------------
 * Uses PEAR's classes to compare and
 * show the difference between two texts.
 * This was originally written by Ciprian Popovic
 * @website http://software.zuavra.net/inline-diff/
 * This is just a wrapper to make the usage easier and
 * PHP 5 OOP compliant.
 *
 * Usage:
 * ------------------------------
 * $string1 = 'i love green color';
 * $string2 = 'i love red colour';
 * require_once('TextDiff.php');
 * $diffObj = new TextDiff;
 * echo $diffObj->getDiff($string1, $strign2);
 *
 * Notes:
 * ------------------------------
 * 1. By default this class will set the error reporting level
 *    to 0, So the PEAR Text classes won't thrown any strict standards errors.
 *    You can disable this by just defineing a constant named 'TEXTDIFF_DISABLE_ERROR_REPORTING'
 *    define('TEXTDIFF_DISABLE_ERROR_REPORTING', true);
 *    before loading the TextDiff.php file.
 * 
 * 2. By default this class will try to include the PEAR Text classes from the directory Text/*    
 *    Under the current working directory. If you already have your PEAR classes included or would like to include
 *    them by yourself from a different location just set:
 *    define('TEXTDIFF_DISABLE_PEAR_INCLUDE', true);
 *    Before including the TextDiff.php class file. And make sure you include the PEAR Text classes 
 *    Before you include the TextDiff.php class file.
 *
 *
 * @date 11-17-2010
 * @author Ciprian Popovic, Vadim Gabriel
 * @copyright All rights reserved (C) PEAR, 
 * @link http://vadimg.com/
 * @website http://vadimg.com/
 */
class TextDiff extends Text_Diff_Renderer
{
    /**
     * @var int - Number of trailing context "lines" to preserve.
     */
    public $contextTrailingLines = 100000;
    /**
     * @var int - Number of leading context "lines" to preserve.
     */
    public $contextLeadingLines = 100000;
    /**
     * @var string - insert text prefix
     * This string will be prefixed to each word or letter that
     * is added to the difference context
     */
    public $insertPrefix = '<ins>';
    /**
     * @var string - insert text suffix
     * This string will be suffixed to each word or letter that
     * is added to the difference context
     */
    public $insertSuffix = '</ins>';
    /**
     * @var string - deleted text prefix
     * This string will be prefixed to each word or letter that
     * was deleted from the difference context
     */
    public $deletePrefix = '<del>';
    /**
     * @var string - deleted text suffix
     * This string will be suffixed to each word or letter that
     * was deleted from the difference context
     */
    public $deleteSuffix = '</del>';
    /**
     * @var string - all the output
     */
    public $myOutput = '';
    
    /**
     * Init, Class Constructor
     */
    public function __construct() {
        $this->_leading_context_lines = $this->contextLeadingLines;
        $this->_trailing_context_lines = $this->contextTrailingLines;
    }
    
    /**
     * show the difference between two text strings
     * 
     * @param string $strFrom
     * @param string $strTo
     * @return string
     */    
    public function getDiff($strFrom, $strTo) {
        // create the hacked lines for each file
    	$strFromChunked = chunk_split($strFrom, 1, "\n");
    	$strToChunked = chunk_split($strTo, 1, "\n");
    
    	// convert the hacked texts to arrays
    	$strFromLines = str_split($strFromChunked, 2);
    	$strToLines = str_split($strToChunked, 2);
    
    	$strFrom = str_replace("\n", " \n",$strFrom);
    	$strTo = str_replace("\n", " \n",$strTo);
    
    	$strFromLines = explode(" ", $strFrom);
    	$strToLines = explode(" ", $strTo);
    
    	// create the diff object
    	$diff = new Text_Diff($strFromLines, $strToLines);
    
    	// get the diff in unified format
    	return $this->render($diff);
    }
    
    /**
     * show the difference between two text strings
     * 
     * @param string $strFrom
     * @param string $strTo
     * @return string
     */    
    public function getDiffByFile($fileFrom, $fileTo) {    
    	// create the diff object
    	$diff = new Text_Diff(file($fileFrom), file($fileTo));
    
    	// get the diff in unified format
    	return $this->render($diff);
    }

    /**
     * Inherited from the parent class and overriden
     * loop the lines
     */
    public function _lines($lines) {
        foreach ($lines as $line) {
            $this->myOutput .= "$line ";
        }
    }
    /**
     * Inherited from the parent class and overriden
     * context block header
     */
    public function _blockHeader($xbeg, $xlen, $ybeg, $ylen) {
		$this->myOutput .= "";
    }
    /**
     * Inherited from the parent class and overriden
     * start a new context block
     */
    public function _startBlock($header) {
        $this->myOutput .= $header;
    }
    /**
     * Inherited from the parent class and overriden
     * add the inserted lines
     */
    public function _added($lines) {
		$this->myOutput .= $this->insertPrefix;
        $this->_lines($lines);
		$this->myOutput .= $this->insertSuffix;
    }
    /**
     * Inherited from the parent class and overriden
     * add the deleted lines
     */
    public function _deleted($lines) {
		$this->myOutput .= $this->deletePrefix;
        $this->_lines($lines);
		$this->myOutput .= $this->deleteSuffix;
    }
    /**
     * Inherited from the parent class and overriden
     * show what's changed
     */
    public function _changed($orig, $final) {
        $this->_deleted($orig);
        $this->_added($final);
    }
}