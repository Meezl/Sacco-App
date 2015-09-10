<?php

namespace App\Models;

use App\Commands\GenerateStats;

//use App\Models\Contact;

/**
 * Description of Report
 *
 * @author jameskmb
 */
class Report extends \TCPDF
{

    /**
     *
     * @var Campaign
     */
    private $campaign;

    /**
     *
     * @var array
     */
    private $stats;

    //Page header
    public function Header()
    {
        // Logo
        $image_file = public_path() . '/img/logo-sm.jpg';
        $this->Image($image_file, 5, 5, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<<' . $this->campaign->id_string . '>>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function intro()
    {
        $this->Image(public_path() . '/img/logo.png', 25, 30, 150);
        $this->Ln(50);
        $this->SetFont('', 'B');
        $this->SetTextColor(128, 128, 128);
        $keys = [
            'Generated on: ',
            'Title: ',
            'Group: ',
            'Total  Responses: '
        ];
        $values = [
            date('Y-m-d H:i:s'),
            $this->campaign->title,
            $this->campaign->group->title,
            $this->campaign->total_responses
        ];
        $this->drawList(40, 53, 0, $keys, $values, 'R');
    }

    public function renderQuestion()
    {
        $this->SetFont('', 'B');
        $this->Cell(200, 0, 'Question', '', 1, 'C');
        $this->SetFont('', '', 12);
        $this->Write(0, $this->campaign->message);
        $this->Ln();
        if ($this->campaign->possible_responses) {
            $answers = explode("\n", $this->campaign->getResponseText());
            $this->drawList(20, 200, 5, $answers);
        }
        $this->Write(0, $this->campaign->getHelpText());
    }

    /**
     * Generate stats for camapign with possible responses
     * @return boolean
     */
    public function overview()
    {
        if ($this->campaign->possible_responses == 0) {
            return false;
        }
        $this->setFont('', 'B');
        $this->Cell(200, 20, 'Statistics Overview', 0, 1, 'C');
        $this->SetFont('', '', 8);
        $headers = ['options', 'text', 'count', '%'];
        $values = $this->generateStats();

        $this->drawTable($headers, $values, [20, 125, 15, 15], 10, ['C', 'L', 'C', 'C'], true);
        return true;
    }

    public function renderMessages()
    {
        $this->SetFont('', 'B', 20);
        $this->Cell(200, 20, 'Messages', 0, 1, 'C');
        $this->SetFont('', '', 8);
        if ($this->campaign->messages->isEmpty()) {
            $this->Write(0, 'No User Response Received');
            return;
        }         
        $header = ['id', 'message', 'sender', 'time'];
        $values = [];
        foreach ($this->campaign->messages as $m) {
            $values[] = [
                $m->id, $this->trim($m->text, 62), $m->sender, (string) $m->created_at
            ];
        }

        $this->drawTable($header, $values, [15, 100, 35, 35], 10, 'C', true);
    }

    /**
     * Camapign stats results
     * @return array
     */
    private function generateStats()
    {
        $values = [];
        foreach ($this->stats as $s) {
            $values[] = [$s->key, $this->trim($s->val, 78),
                $s->count, number_format($s->percent, 2) . '%'];
        }
        return $values;
    }

    private function trim($string, $length)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length) . '...';
        }

        return $string;
    }

    /**
     * Draw an unordered list of items. if values are present
     * it will be drawn as <b>key</b>value
     * @param int $margin left margin
     * @param int $width
     * @param int $height 
     * @param array $keys
     * @param array $values
     * @param string $align 
     */
    public function drawList($margin, $width, $height, array $keys, array $values = null, $align = '')
    {
        $bolden = !is_null($values);
        for ($i = 0; $i < count($keys); $i++) {
            //draw margin
            $this->Cell($margin, $height);
            if ($bolden) {
                $this->SetFont('', 'B');
            }
            $this->Cell($width, $height, $keys[$i], 0, 0, $align);

            if ($bolden) {
                $this->SetFont('', '');
                $this->Cell($width, $height, $values[$i]);
            }

            $this->Ln();
        }
    }

    /**
     * Draw a table     
     * @param array $header
     * @param array $values
     * @param int $width an array of col widths or an int if col widths are equal
     * @param int $height
     * @param string Alignment an array of column alignment or a single alignment for all columns
     * keys are center aligned
     * @param boolean $numbers display numbering
     */
    public function drawTable(array $header, array $values, $width, $height, $alignment = 'L', $numbers = false)
    {
        $NUMBER_WIDTH = 10;
        $singleWidth = !is_array($width);
        $singleAlignment = !is_array($alignment);

        //draw header        
        $this->SetDrawColor(221);
        $this->SetFillColor(245);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        if ($numbers) {
            $this->Cell($NUMBER_WIDTH, $height, '#', 1, 0, 'C', true);
        }
        for ($i = 0; $i < count($header); $i++) {
            $w = $singleWidth ? $width : $width[$i];
            $this->Cell($w, $height, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        //restore font
        $this->SetFont('', '');
        $fill = false;
        for ($i = 0; $i < count($values); $i++) {
            if ($numbers) {
                $this->Cell($NUMBER_WIDTH, $height, $i + 1, 'LRB', 0, 'C', $fill);
            }

            $row = $values[$i];
            for ($x = 0; $x < count($row); $x++) {
                $w = $singleWidth ? $width : $width[$x]; //width
                $a = $singleAlignment ? $alignment : $alignment[$x]; //alignment
                $this->Cell($w, $height, $row[$x], 'LRB', 0, $a, $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    /**
     * Set default pdf settings
     */
    public function loadDefaults(Campaign $campaign, array $stats)
    {
        //correct counting anomaly
        $campaign->total_responses = 0;
        foreach ($stats as $s) {
            $campaign->total_responses += $s->count;
        }

        $this->campaign = $campaign;
        $this->stats = $stats;
        //document information
        $this->SetCreator("James Kamau");
        $this->SetAuthor('Egerton Sacco Survey System');
        $this->SetSubject('Campaign Report for' . $this->campaign->getIdString());
        $this->SetKeywords('Report, Survey, SMS System, Egerton Sacco');

        //650 * 100
//    $logo = '../../../../../public/img/logo.png';
//    $this->setHeaderData($logo, 150);

        $this->Footer(array(0, 64, 0), array(0, 64, 128));

        //set header and footer fonts
        $this->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->SetMargins(5, PDF_MARGIN_TOP, 5);
        $this->setHeaderMargin(PDF_MARGIN_HEADER);
        $this->setFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        //$this->setFontSubsetting(true);

        $this->SetFont('dejavusans', '', 14, '', true);
    }

}
