<?php

class ErrorResponse
{
	private $source_image;
	private $output_image;

    function __construct($severity, $message) {
       $this->render($severity, $message);
    }

    private function render($severity, $msg, $args = array()){
         header('Cache-Control: no-store');
         echo json_encode(array(
            'status' => 'Failed',
            'severity' => $severity,
            'message' => $msg,
            'additional' => json_encode($args)));
        die();
    }
}