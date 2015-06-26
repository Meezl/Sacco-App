<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

//register custom kenyan mobile number validator
Validator::extend('kmobile', 'App\Validators\PhoneValidator@validate');



Route::get('callback', function() {
   return view('callback');
});

Route::post('callback', 'MessageController@postHandleCallback');

//Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

Route::group(['middleware' => 'auth'], function() {

    Route::get('/', 'DashBoardController@getIndex');

    Route::controller('user', 'UserController');

    Route::controller('contacts', 'ContactController');

    Route::controller('account', 'AccountController');

    Route::controller('category', 'CategoryController');

    Route::controller('campaign', 'CampaignController');

    Route::controller('message', 'MessageController');

    Route::controller('stats', 'StatsController');
});


Route::controller('auth', 'Auth\AuthController');

Route::get('test', function() {

    class MYPDF extends TCPDF {

        public function LoadData($file) {
            $lines = file($file);
            $data = [];
            foreach ($lines as $line) {
                $data[] = explode(';', chop($line));
            }
            return $data;
        }

        public function ColoredTable($header, $data) {
            // Colors, line width and bold font
            $this->SetFillColor(255, 0, 0);
            $this->SetTextColor(255);
            $this->SetDrawColor(128, 0, 0);
            $this->SetLineWidth(0.3);
            $this->SetFont('', 'B');
            // Header
            $w = array(40, 35, 40, 45);
            $num_headers = count($header);
            for ($i = 0; $i < $num_headers; ++$i) {
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            }
            $this->Ln();
            // Color and font restoration
            $this->SetFillColor(224, 235, 255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Data
            $fill = 0;
            foreach ($data as $row) {
                $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
                $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
                $this->Ln();
                $fill = !$fill;
            }
            $this->Cell(array_sum($w), 0, '', 'T');
        }

    }

    $pdf = new \MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //document information
    $pdf->SetCreator("James Kamau");
    $pdf->SetAuthor('Egerton Sacco Survey System');
    $pdf->SetSubject('Campaign Report');
    $pdf->SetKeywords('Report, Survey, SMS System, Egerton Sacco');

    //650 * 100
    $logo = '../../../../../public/img/logo.png';
    $pdf->setHeaderData($logo, 150);

    $pdf->Footer(array(0, 64, 0), array(0, 64, 128));

    //set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

    //$pdf->setFontSubsetting(true);

    $pdf->SetFont('dejavusans', '', 14, '', true);

    $pdf->AddPage();

    // $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
    /// Set some content to print
    $html = <<<EOD

<div class="well well-lg">
    <h3>Actual Text Sent</h3>
    How useful was this training?
<ul style="list-style-type: none">
    <li>A: Extremely Conveniet</li>
    <li>B: Very Convenien</li>
    <li>C: Moderately</li>
    <li>D: Slightly Convenient</li>
    <li>E: Not at all convenient</li>
</ul>
Reply for free to 20880 in the format( EGERS X30 A )where A is your reply 
</div>
EOD;

// Print text using writeHTMLCell()
    $pdf->SetFillColor(245, 245, 245);
    $pdf->SetDrawColor(227,227,227);
    $pdf->writeHTMLCell(0, 0, '', '', $html, 1, 1, 1, true, '', true);
    

    // column titles
    $header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');

    // data loading
    $data = $pdf->LoadData(public_path('data/table_data_demo.txt'));
    
    $pdf->ColoredTable($header, $data);
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
    $pdf->Output('example_001.pdf', 'I');
});
