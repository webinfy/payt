<?php

/**
 * Component for working with Mpdf class.
 * Mpdf has to be in the vendors directory.
 */

namespace App\Controller\Component;

use App\Controller;
use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Mpdf\Mpdf;

class MpdfComponent extends Component {

    /**
     * Instance of Mpdf class
     *
     * @var object
     */
    protected $pdf;

    /**
     * Default values for Mpdf constructor
     * @var array
     */
    protected $_configuration = array(
        // mode: 'c' for core fonts only, 'utf8-s' for subset etc.
        'mode' => 'utf8-s',
        // page format: 'A0' - 'A10', if suffixed with '-L', force landscape
        'format' => 'A4',
        // default font size in points (pt)
        'font_size' => 0,
        // default font
        'font' => NULL,
        // page margins in mm
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9
    );

    /**
     * Flag set to true if Mpdf was initialized
     * @var bool
     */
    protected $_init = false;

    /**
     * Name of the file on the output
     * @var string
     */
    protected $_filename = NULL;

    /**
     * Destination - posible values are I, D, F, S
     * @var string
     */
    protected $_output = 'I';

    /**
     * Initialize
     * Add vendor and define Mpdf class.
     */
    public function init($configuration = array()) {
        // Mpdf class has many notices - suppress them
        error_reporting(0);

        // import Mpdf
//        App::import('Vendor', 'mpdf/mpdf');
//        if (!class_exists('Mpdf'))
//            throw new Exception('Vendor class Mpdf not found!');
        // override default values
        $c = array_merge($this->_configuration, $configuration);

        // initialize
        $this->pdf = new Mpdf($c);
        $this->_init = true;
    }

    /**
     * Set filename of the output file
     */
    public function setFilename($filename) {
        $this->_filename = (string) $filename;
    }

    /**
     * Set destination of the output
     * DF option used for creating & downloading file.
     */
    public function setOutput($output) {
        if (in_array($output, array('I', 'D', 'F', 'S', 'DF')))
            $this->_output = $output;
    }

    /**
     * Shutdown of the component
     * View is rendered but not yet sent to browser.
     */
    public function shutdown($controller) {
        if ($this->_init) {
            $this->pdf->WriteHTML((string) $this->response);
            //This will crete and save file on server and download the generated pdf file.
            if ($this->_output == 'DF') {
                $this->pdf->Output($this->_filename, 'F');
                $this->_output = 'D';
                $this->_filename = basename($this->_filename);
            }//create & download end
            $this->pdf->Output($this->_filename, $this->_output);
        }
    }

    /**
     * Passing method calls and variable setting to Mpdf library.
     */
    public function __set($name, $value) {
        $this->pdf->$name = $value;
    }

    public function __get($name) {
        return $this->pdf->$name;
    }

    public function __isset($name) {
        return isset($this->pdf->$name);
    }

    public function __unset($name) {
        unset($this->pdf->$name);
    }

    public function __call($name, $arguments) {
        call_user_func_array(array($this->pdf, $name), $arguments);
    }

}
