<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ThumbsUpSurvey_FunctionsClass
{
    private $available_clients = [
        "web", "form", "salesforce"
    ];

    public function __construct()
    {
    }

    public function get_available_clients() {
        return $this->available_clients;
    }

    public function escape_css_selector($selector) {
        // Replace all characters that are not alphanumeric, dots, hashes, or greater-than signs with a hyphen
        $escaped_selector = preg_replace('/[^a-zA-Z0-9\.\#\-_ ]/', '-', $selector);
        return $escaped_selector;
    }
    

}
$ThumbsUpSurvey_Functions = new ThumbsUpSurvey_FunctionsClass();