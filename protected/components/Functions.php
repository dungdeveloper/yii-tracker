<?php

/**
 * Various common functions
 */
class Functions extends CApplicationComponent {

    /**
     * Internal incrementing integar for the alternator() method
     * @var int
     */
    private $alternator = 1;

    public function init() {
        
    }

    /**
     * Figure out the sort value by the order currently used 
     * @param string $key
     * @return string
     */
    public static function getSortBy($key) {
        if (Yii::app()->request->getParam('order') == $key) {
            return Yii::app()->request->getParam('sort') == 'asc' ? 'desc' : 'asc';
        }
        return 'asc';
    }

    /**
     * Print an ajax error message
     * @param string $message
     * @return void
     */
    public static function ajaxError($message) {
        header('HTTP/1.0 500 Internal Server Error');
        echo $message;
        Yii::app()->end();
    }

    /**
     * Print an ajax message
     * @param string $message
     * @return void
     */
    public static function ajaxString($message) {
        header('HTTP/1.0 200 OK');
        echo $message;
        Yii::app()->end();
    }

    /**
     * Print an ajax json encoded message
     * @param array $array
     * @return void
     */
    public static function ajaxJsonString($array) {
        header('HTTP/1.0 200 OK');
        echo CJSON::encode($array);
        Yii::app()->end();
    }

    /**
     * Will return true if the number passed in is even, false if odd.
     *
     * @param int $number
     * @return boolean
     */
    public function isEven($number) {
        return (Boolean) ($number % 2 == 0);
    }

    /**
     * Returns an alternating Boolean, useful to generate alternating background colors
     * Eg.:
     * $colors = array(true => 'gray', false => 'white');
     * echo '<div style="background: ' . $colors[$html->alternator()] . ';">...</div>'; //gray background
     * echo '<div style="background: ' . $colors[$html->alternator()] . ';">...</div>'; //white background
     * echo '<div style="background: ' . $colors[$html->alternator()] . ';">...</div>'; //gray background
     *
     * @return Boolean
     */
    public function alternator() {
        return $this->isEven(++$this->alternator);
    }

    /**
     * Alternate between two css classes or styles
     * @param string $onCss
     * @param string $offCss
     * @return string
     */
    public function alternateCss($onCss, $offCss) {
        return $this->isEven(++$this->alternator) ? $onCss : $offCss;
    }

    /**
     * Set a flash message for a user
     * @param string $message
     * @param string $key
     * @return void
     */
    static public function setFlash($message, $key = 'message') {
        Yii::app()->user->setFlash($key, $message);
    }

    /**
     * Set a class on/off by the current action in the current controller
     * @param string/array $action
     * @param string $onClass
     * @param string $offClass
     * @return string
     */
    public function setClassByAction($action, $onClass = '', $offClass = '') {
        if (is_array($action) && count($action)) {
            if (in_array(Yii::app()->getController()->getAction()->id, $action)) {
                return $onClass;
            } else {
                return $offClass;
            }
        }
        return Yii::app()->getController()->getAction()->id == $action ? $onClass : $offClass;
    }

    /**
     * Display an image based on the value (tick or cross image)
     */
    public function adminYesNoImage($value, $url = null, $imageOptions = array(), $linkOptions = array()) {
        $true = Yii::app()->themeManager->baseUrl . '/images/icons/tick_circle.png';
        $false = Yii::app()->themeManager->baseUrl . '/images/icons/cross_circle.png';

        $image = $value ? $true : $false;

        if ($url) {
            return CHtml::link(CHtml::image($image, '', $imageOptions), $url, $linkOptions);
        } else {
            return CHtml::image($image, '', $imageOptions);
        }
    }

    /**
     * Compare two texts and show the differences report
     * @param string $a
     * @param string $b
     * @return string
     */
    public static function diffTexts($a, $b) {
        Yii::import('ext.TextDiff.*');
        Yii::import('ext.TextDiff.Text.*');
        $diffObj = new TextDiff;
        // For some reason without the space this does not work.
        // Have no clue why but at least the space thing solves it
        $diffObj->getDiff(' ' . $a . ' ', ' ' . $b . ' ');

        return $diffObj->myOutput ? $diffObj->myOutput : Yii::t('global', 'Error trying to show the differences.');
    }

    /**
     * load zend components and autoloader
     */
    public function loadZend() {
        Yii::import('ext.*');
        require_once 'Zend/Loader/Autoloader.php';
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        spl_autoload_register(array('Zend_Loader_Autoloader', 'autoload'));
        spl_autoload_register(array('YiiBase', 'autoload'));
    }

    /**
     * Display RSS Data
     */
    public function displayRss($array, $type = 'rss') {
        $this->loadZend();
        $feed = Zend_Feed::importArray($array, $type);
        $feed->send();
        exit;
    }

    /**
     * Download content as text
     */
    public function downloadAs($title, $name, $content, $type = 'text') {
        $types = array(
            'text' => 'text/plain',
            'pdf' => 'application/pdf',
            'word' => 'application/msword'
        );

        $exts = array(
            'text' => 'txt',
            'pdf' => 'pdf',
            'word' => 'doc'
        );

        // Load anything?
        if ($type == 'pdf') {
            $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8');
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(Yii::app()->name);
            $pdf->SetTitle($title);
            $pdf->SetSubject($title);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->writeHTML($content, true, 0, true, 0);
            $pdf->Output($name . '.' . $exts[$type], "I");
        }

        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Pragma: no-cache');
        header("Content-Type: " . $types[$type] . "");
        header("Content-Disposition: attachment; filename=\"" . $name . '.' . $exts[$type] . "\";");
        header("Content-Length: " . mb_strlen($content));
        echo $content;
        exit;
    }

    /**
     * Convert bytes to human readable format
     *
     * @param integer bytes Size in bytes to convert
     * @return string
     */
    public function bytesToSize($bytes, $precision = 2) {
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;

        if (($bytes >= 0) && ($bytes < $kilobyte)) {
            return $bytes . ' B';
        } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
            return round($bytes / $kilobyte, $precision) . ' KB';
        } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
            return round($bytes / $megabyte, $precision) . ' MB';
        } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
            return round($bytes / $gigabyte, $precision) . ' GB';
        } elseif ($bytes >= $terabyte) {
            return round($bytes / $gigabyte, $precision) . ' TB';
        } else {
            return $bytes . ' B';
        }
    }

    /**
     * Convert a string into it's safe form for use in urls
     * @param string $string
     * @return string
     */
    public function makeAlias($string) {
        // If we have nothing then return empty string
        if (!$string) {
            return 'no-alias'; // this is here to break functionality with url rules
        }

        // Array of chars to strip
        $strip = array(
            "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "���", "���", ",", "<", ".", ">", "/", "?"
        );

        // Replace spaces and remove tags    
        $string = trim(str_replace($strip, "", strip_tags($string)));
        // Replace spaces with dash
        $string = preg_replace('/\s+/', "-", $string);
        // Replace extensive dash usage with a single dash
        $string = preg_replace('/^[\-]+/', '', $string);
        $string = preg_replace('/[\-]+$/', '', $string);
        $string = preg_replace('/[\-]{2,}/', '-', $string);
        // Convert into lowercase and support utf8 strings
        return mb_strtolower($string, 'UTF-8');
    }

}