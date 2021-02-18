<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Recaptcha {

    /**
     * Reference to CodeIgniter instance
     *
     * @var object
     */
    protected $CI;

    /**
     * @var signupURL
     * @access private
     */
    private $signupURL = "https://www.google.com/recaptcha/admin";

    /**
     * @var siteVerifyURL
     * @access private
     */
    private $siteVerifyURL = "https://www.google.com/recaptcha/api/siteverify?";

    /**
     * @var secret_key
     * @access private
     */
    private $secret_key;

    /**
     * @var site_key
     * @access private
     */
    private $site_key;

    /**
     * @var _version
     * @access private
     */
    private $_version = "php_1.0";

    /**
	 * Class Constructor
	 *
	 * @return Void
	 */
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('m_settings');
        $recaptcha = $this->CI->m_settings->get_recaptcha();
        $this->site_key = $recaptcha['recaptcha_site_key'];
        $this->secret_key = $recaptcha['recaptcha_secret_key'];
        if (!$this->site_key || !$this->secret_key) {
            die("Recaptcha site key dan secret key belum diatur. Silahkan masuk ke menu Pengaturan / Umum.");
        }
    }

    /**
     * Function to convert an array into query string
     * @param array $data Array of params
     * @return String query string of parameters
     */
    private function _encodeQS($data) {
        $req = "";
        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }
        return substr($req, 0, strlen($req) - 1);
    }

    /**
     * Function for verifying user's input
     * @param string $response User's input
     * @param string $remoteIp Remote IP you wish to send to reCAPTCHA, if NULL $this->input->ip_address() will be called
     * @return array Array of response
     */
    public function verifyResponse($response, $remoteIp = NULL) {
        if ($response == NULL || strlen($response) == 0) {
            $return = [
                'success' => FALSE,
                'error_codes' => 'missing-input'
            ];
        }
        $getResponse = $this->_submitHttpGet(
            $this->siteVerifyURL, [
                'secret' => $this->secret_key,
                'remoteip' => (!is_null($remoteIp)) ? $remoteIp : $this->CI->input->ip_address(),
                'v' => $this->_version,
                'response' => $response
            ]
        );
        $answers = json_decode($getResponse, TRUE);
        if (trim($answers ['success']) == TRUE) {
            $return = [
                'success' => TRUE,
                'error_codes' => ''
            ];
        } else {
            $return = [
                'success' => FALSE,
                'error_codes' => $answers['error-codes']
            ];
        }
        return $return;
    }

    /**
     * HTTP GET to communicate with reCAPTCHA server
     * @param string $path URL to GET
     * @param array $data Array of params
     * @return string JSON response from reCAPTCHA server
     */
    private function _submitHTTPGet($path, $data) {
        $req = $this->_encodeQS($data);
        $response = file_get_contents($path . $req);
        return $response;
    }
}