<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(FCPATH . 'vendor/autoload.php');

use MatthiasMullie\Minify;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;

class AppBackend extends MX_Controller
{
    private $_charName = 'IEMED Support | PT. KAH';
    private $_permittedChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $_specialRoute = array('login', 'form');

    function __construct()
    {
        parent::__construct();

        $this->handle_access();
        $this->load->model(array(
            'SettingModel',
            'MenuModel',
            'UserModel',
            'NotificationModel',
        ));
        $this->load->library('form_validation');
        $this->template->set_template($this->app()->template_backend);
    }

    public function app()
    {
        $agent = new Mobile_Detect;
        $appData = $this->SettingModel->getAll();
        $config = array();

        if (count($appData) > 0) {
            foreach ($appData as $index => $item) {
                $config[$item->data] = $item->content;
            };
        };

        $config['is_mobile'] = $agent->isMobile();

        return (object) $config;
    }

    public function load_main_js($moduleName, $isSpecificPath = false, $variables = null)
    {
        if (!is_null($variables)) {
            extract($variables, EXTR_SKIP);
        };

        ob_start();

        if ($isSpecificPath === true) {
            @include FCPATH . '/application/modules/' . $moduleName;
        } else {
            @include FCPATH . '/application/modules/' . $moduleName . '/views/main.js.php';
        };

        $sourcePath = ob_get_clean();
        $minifier = new Minify\JS($sourcePath);

        return $minifier->minify();
        ob_end_clean();
    }

    public function handle_access()
    {
        $session = $this->session->userdata('user');
        $isLogin = (!is_null($session) && $session['is_login'] === true) ? true : false;

        if ($isLogin === false) {
            if (!in_array($this->router->fetch_class(), $this->_specialRoute)) {
                redirect(base_url('login'), 'location', 301);
            };
        } else {
            if (in_array($this->router->fetch_class(), $this->_specialRoute)) {
                redirect(base_url('dashboard'), 'location', 301);
            };
        };
    }

    public function handle_ajax_request()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        };
    }

    public function get_month($bulan)
    {
        switch ($bulan) {
            case '01':
                return 'Januari';
                break;
            case '02':
                return 'Februari';
                break;
            case '03':
                return 'Maret';
                break;
            case '04':
                return 'April';
                break;
            case '05':
                return 'Mei';
                break;
            case '06':
                return 'Juni';
                break;
            case '07':
                return 'Juli';
                break;
            case '08':
                return 'Agustus';
                break;
            case '09':
                return 'September';
                break;
            case '10':
                return 'Oktober';
                break;
            case '11':
                return 'November';
                break;
            case '12':
                return 'Desember';
                break;
            default:
                return 'Undefined';
                break;
        };
    }

    function get_day($hari)
    {
        switch ($hari) {
            case 'Sun':
                return 'Minggu';
                break;
            case 'Mon':
                return 'Senin';
                break;
            case 'Tue':
                return 'Selasa';
                break;
            case 'Wed':
                return 'Rabu';
                break;
            case 'Thu':
                return 'Kamis';
                break;
            case 'Fri':
                return 'Jumat';
                break;
            case 'Sat':
                return 'Sabtu';
                break;
            default:
                return 'Undefined';
                break;
        };
    }

    function get_day_by_num($hari)
    {
        switch ($hari) {
            case 7:
                return 'Minggu';
                break;
            case 1:
                return 'Senin';
                break;
            case 2:
                return 'Selasa';
                break;
            case 3:
                return 'Rabu';
                break;
            case 4:
                return 'Kamis';
                break;
            case 5:
                return 'Jumat';
                break;
            case 6:
                return 'Sabtu';
                break;
            default:
                return 'Undefined';
                break;
        };
    }

    public function set_notification($post, $role = null)
    {
        $payload = array();

        if (!is_null($role)) {
            $users = $this->UserModel->getAll(array('LOWER(role)' => strtolower($role)));

            if (count($users) > 0) {
                foreach ($users as $key => $item) {
                    $payload[] = array(
                        'user_from' => $this->session->userdata('user')['id'],
                        'user_to' => $item->id,
                        'ref' => $post['ref'],
                        'ref_id' => $post['ref_id'],
                        'description' => $post['description'],
                        'link' => $post['link']
                    );
                };
            };
        } else {
            $payload[] = array(
                'user_from' => $this->session->userdata('user')['id'],
                'user_to' => $post['user_to'],
                'ref' => $post['ref'],
                'ref_id' => $post['ref_id'],
                'description' => $post['description'],
                'link' => $post['link']
            );
        };

        return $this->NotificationModel->insertBatch($payload);
    }

    public function init_list($data, $value, $text, $default_value = null, $static = null)
    {
        $lists = '<option disabled selected>(No data available)</option>';

        if (count($data) > 0) {
            $is_selected_ph = (is_null($default_value)) ? 'selected' : '';
            $lists = '<option disabled ' . $is_selected_ph . '>Select &#8595;</option>';

            if (!is_null($static)) {
                $lists .= $static;
            };

            foreach ($data as $key => $item) {
                $item = (is_object($item) === false) ? (object) $item : $item;
                $is_selected = (!is_null($default_value) && ($item->{$value} === $default_value)) ? 'selected' : '';
                $lists .= '<option value="' . $item->{$value} . '" ' . $is_selected . '>' . $item->{$text} . '</option>';
            };
        };

        return $lists;
    }

    public function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function weekOfMonth($date)
    {
        $firstOfMonth = date('Y-m-01', strtotime($date));
        return intval(date('W', strtotime($date))) - intval(date('W', strtotime($firstOfMonth)));
    }

    public function searchInArrayObj($array, $key, $value)
    {
        $result = array();

        foreach ($array as $index => $item) {
            if ($item->{$key} == $value) {
                $result = $item;
            };
        };

        return $result;
    }

    function generateRandom($strength = 16, $input = null)
    {
        $input = (is_null($input)) ? $this->_permittedChars : $input;
        $input_length = strlen($input);
        $random_string = '';

        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        };

        return $random_string;
    }

    public function generateBarcode($path = 'directory/barcode/', $text = null, $textAsName = false, $removeSpace = false)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');

        $randomString = $this->generateRandom(10);
        $text = (is_null($text)) ? $randomString : $text;
        $text = ($removeSpace) ? preg_replace('/\s+/', '', $text) : $text;
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $text), array())->draw();
        $path = rtrim($path, '/') . '/';
        $imageDir = FCPATH . $path;
        $imageName = ($textAsName) ? $text . '.jpg' : $randomString . '.jpg';
        $isCreatedDir = true;

        if (!file_exists($imageDir)) {
            $isCreatedDir = mkdir($imageDir, 0777, true);
        };

        if ($isCreatedDir) {
            $create = imagejpeg($imageResource, $imageDir . $imageName);

            if ($create) {
                $response = array('status' => true, 'data' => 'Successfully create the barcode.', 'file_path' => $path . $imageName);
            } else {
                $response = array('status' => false, 'data' => 'Failed to create the barcode.', 'file_path' => null);
            };
        } else {
            $response = array('status' => false, 'data' => 'Failed to create directory: "' . $path . '"', 'file_path' => null);
        };

        return (object) $response;
    }

    public function generateBarcodeAsImage($text = null)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');

        Zend_Barcode::render('code128', 'image', array('text' => $text), array());
    }

    public function generateQrCode($path = 'directory/qrcode/', $text = null, $textAsName = false, $removeSpace = false)
    {
        $this->load->library('ciqrcode');

        $randomString = $this->generateRandom(10);
        $text = (is_null($text)) ? $randomString : $text;
        $text = ($removeSpace) ? preg_replace('/\s+/', '', $text) : $text;
        $path = rtrim($path, '/') . '/';
        $imageDir = FCPATH . $path;
        $imageName = ($textAsName) ? $text . '.jpg' : $randomString . '.jpg';
        $isCreatedDir = true;

        if (!file_exists($imageDir)) {
            $isCreatedDir = mkdir($imageDir, 0777, true);
        };

        if ($isCreatedDir) {
            $params['data'] = $text;
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = $imageDir . $imageName;
            $create = $this->ciqrcode->generate($params);

            if ($create) {
                $response = array('status' => true, 'data' => 'Successfully create the qrcode.', 'file_path' => $path . $imageName);
            } else {
                $response = array('status' => false, 'data' => 'Failed to create the qrcode.', 'file_path' => null);
            };
        } else {
            $response = array('status' => false, 'data' => 'Failed to create directory: "' . $path . '"', 'file_path' => null);
        };

        return (object) $response;
    }

    public function generateQrCodeAsImage($text = null)
    {
        $this->load->library('ciqrcode');

        header("Content-Type: image/png");
        $params['data'] = $text;
        $this->ciqrcode->generate($params);
    }

    public function jsonToString($data, $delimiter = ', ', $replaceSearch = null, $replaceWith = null)
    {
        $result = ($data) ? json_decode($data) : array();
        $result = (count($result) > 0) ? implode($delimiter, $result) : null;

        if (!is_null($replaceSearch) && !is_null($replaceWith)) {
            $result = (!is_null($result)) ? str_replace($replaceSearch, $replaceWith, $result) : $result;
        };

        return $result;
    }

    public function jsonToComnponent($data, $tagOpen = '<li>', $tagClose = '</li>', $replaceSearch = null, $replaceWith = null)
    {
        $result = ($data) ? json_decode($data) : array();
        $itemValue = '';

        if (count($result) > 0) {
            foreach ($result as $key => $item) {
                if (!is_null($replaceSearch) && !is_null($replaceWith)) {
                    $item = (!is_null($item)) ? str_replace($replaceSearch, $replaceWith, $item) : $item;
                };

                $itemValue .= $tagOpen . $item . $tagClose;
            };
        } else {
            $result = null;
        };

        return $itemValue;
    }

    public function getMailConfig()
    {
        $app = $this->app();
        $smtp = array(
            'protocol' => $app->smtp_protocol,
            'smtp_host' => $app->smtp_host,
            'smtp_port' => $app->smtp_port,
            'smtp_user' => $app->smtp_user,
            'smtp_pass' => $app->smtp_pass,
            'smtp_crypto' => $app->smtp_crypto,
            'smtp_timeout' => 30,
            'mailtype' => $app->smtp_mailtype,
            'wordwrap' => TRUE,
            'charset' => $app->smtp_charset
        );

        return $smtp;
    }

    public function sendMail($params = [])
    {
        error_reporting(0);
        $this->load->library('email');

        try {
            $this->config = $this->getMailConfig();
            $this->email->initialize($this->config);
            $this->email->set_newline("\r\n");
            $this->email->from($this->config['smtp_user'], $this->_charName);
            $this->email->to($params['receiver']);
            $this->email->subject($params['subject']);
            $this->email->message($params['message']);

            if ($this->email->send()) {
                return array('status' => true, 'data' => 'Email has been successfully sent to ' . $params['receiver']);
            } else {
                // DEBUG ONLY
                // print_r($this->email->print_debugger(['headers']));
                // die;
                // END ## DEBUG ONLY

                return array('status' => false, 'data' => 'Failed to send email.');
            };
        } catch (\Throwable $th) {
            return array('status' => false, 'data' => 'Failed to send email.', 'error' => $th);
        };
    }

    public static function excelChar()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
            'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
            'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
            'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
            'EA', 'EB', 'EC', 'EE', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ',
        );
    }

    public function generateExcelByTemplate($fileTemplate, $startAttributeRow, $startDataRow, $payload, $outputFileName = 'dump-excel.xlsx', $inject = null)
    {
        error_reporting(0); // Handle PHP 7.4 PHPOffice bug

        // Write excel
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($fileTemplate);
        $excelChar = $this->excelChar();

        if (count($payload) > 0) {
            $dataNo = 1;
            $attributes = array();
            $sheet = $spreadsheet->setActiveSheetIndex(0);

            // Collect attributes
            foreach ($excelChar as $key => $col) {
                $col = trim($col);
                $val = $sheet->getCell($col . $startAttributeRow)->getFormattedValue();

                if (!empty($val) && !is_null($val)) {
                    $attributes[] = $val;
                };
            };

            // Set value with attributes
            foreach ($payload as $index => $item) {
                $num = 0;
                foreach ($attributes as $key => $val) {
                    $value = ($val === 'no') ? $dataNo++ : $item->{$val};
                    $sheet->setCellValue($excelChar[$num] . $startDataRow, $value);
                    $num++;
                };
                $startDataRow++;
            };

            if (!is_null($inject)) {
                eval($inject);
            };

            // Output stream
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $outputFileName . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        } else {
            echo 'Tidak ditemukan data.';
        };
    }

    public function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates = [];
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        };
        return $dates;
    }

    public function arrayToSetter($payload = array())
    {
        $result = array();

        if (count($payload) > 0) {
            $fields = array_keys((array) $payload[0]);

            foreach ($fields as $index => $field) {
                $no = 1;
                $fieldValues = array();
                $fieldValues_no = array();

                foreach ($payload as $index_item => $item) {
                    $item = (array) $item;
                    $fieldValues[] = $item[$field];
                    $fieldValues_no[] = $no++;
                };

                $result['[no]'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $fieldValues_no);
                $result['[' . $field . ']'] = new ExcelParam(SPECIAL_ARRAY_TYPE, $fieldValues);
            };
        };

        return $result;
    }

    public function arrayToSetterSimple($payload = array())
    {
        $result = array();
        $strip_tag_keys = array('keterangan', 'catatan', 'catatan_atas_retur');

        if (!is_null($payload)) {
            foreach ($payload as $index => $item) {
                $value = $item;

                if (in_array($index, $strip_tag_keys)) {
                    $value = strip_tags($item);
                };

                $result['{' . $index . '}'] = $value;
            };
        };

        return $result;
    }
}
