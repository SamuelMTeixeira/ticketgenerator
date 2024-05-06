<?php

include('../../../inc/includes.php');
include('data.php');
include('../inc/pdf/fpdf.php');

global $DB;
Session::checkLoginUser();

class PDF extends FPDF
{
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// https://github.com/dompdf/dompdf

// Entity data
$pdf->SetDrawColor(204, 204, 204); // borda
$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 15); // font
$pdf->SetTextColor(0, 0, 0); // txt

$location = $Locations;
$ticketHeight = 37;

if(strlen($location) > 40 && strlen($location) < 80) {
    $ticketHeight += 5;
}
else if(strlen($location) >= 80 && strlen($location) < 120 ) {
    $ticketHeight += 10;
}
else if(strlen($location) >= 120 && strlen($location) < 160 ) {
    $ticketHeight += 15;
}
else if(strlen($location) >= 160 ) {
    $ticketHeight += 20;
}

$pdf->Rect(10, 8, 125, $ticketHeight);

// 		     (DISTANCIA HORIZONTAL, ALTURA, )
$pdf->Cell(125, 8, utf8_decode(" $OsId"), 0, 0, 'C', false);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetDrawColor(255, 255, 255); // borda
$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(18, 5, utf8_decode(" Abertura: "));

$pdf->SetFont('Arial', '', 10); // font
$pdf->Cell(50, 5, utf8_decode("$OsData"), 0, 0, 'L');
$pdf->Ln();

$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(23, 5, utf8_decode(" Requerente: "));

$pdf->SetFont('Arial', '', 10); // font
$pdf->Cell(50, 5, utf8_decode("$UserName"));
$pdf->Ln();

$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(18, 5, utf8_decode(" Telefone: "));

$pdf->SetFont('Arial', '', 10); // font
$pdf->Cell(50, 5, utf8_decode("$Phone"));
$pdf->Ln();

$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(13, 5, utf8_decode(" Email: "));

$pdf->SetFont('Arial', '', 10); // font
$pdf->Cell(50, 5, utf8_decode("$UserEmail"));
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(23, 5, utf8_decode(" Localização: "));

$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', '', 10); // font
$pdf->MultiCell(90, 5, utf8_decode($location), 0, 'L');
$pdf->Ln();


/*
// Details
$pdf->SetDrawColor(204, 204, 204); // borda
$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(190, 7, utf8_decode("MANUTENÇÃO DE COMPUTADORES - OS $OsId"), 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetDrawColor(255, 255, 255); // borda
$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(190, 7, utf8_decode("Pré formatação"), 1, 0, 'L', true);
$pdf->Ln();

$ManutencaoHeight = $ticketHeight+26.5;
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Realizar Backup dos arquivos"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(189, 7, utf8_decode("Verificar nome da máquina na rede / Versão S.O"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*1), 4, 4);
$pdf->Ln();

$pdf->SetDrawColor(255, 255, 255); // borda
$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(190, 7, utf8_decode("Pós formatação"), 1, 0, 'L', true);
$pdf->Ln();
$ManutencaoHeight += 7;

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Instalação e ativição do sistema operacional"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*2), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Instalação dos drivers (rede, vídeo, áudio)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*3), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Colocar computador no domínio (pmto-domain.corp)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*4), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Ativar o usuário administrador (Redefinir a senha)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*5), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Habilitar senha nunca expira (PC fora do domínio)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*5), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Desativar o firewall"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(205, $ManutencaoHeight+(7*6), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Restaurar backup"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(200, $ManutencaoHeight+(7*7), 4, 4);
$pdf->Ln();

$pdf->SetDrawColor(255, 255, 255); // borda
$pdf->setFillColor(204, 204, 204); // bg
$pdf->SetFont('Arial', 'B', 10); // font
$pdf->SetTextColor(0, 0, 0); // txt
$pdf->Cell(190, 7, utf8_decode("Programas"), 1, 0, 'L', true);
$pdf->Ln();
$ManutencaoHeight += 7;

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Anydesk (configurar senha e permissão de acesso)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*8), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Mesh"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*9), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Navagadores (chrome e mozilla)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, $ManutencaoHeight+(7*10), 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Office (Word, Excel e Power Point)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 176.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Leitor PDF Adobe ou Foxit Reader"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 183.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Winrar"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 190.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Print (Frameshot ou Lightshot)"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 197.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Driver de impressora"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 204.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Realizar limpeza do PC e troca de pasta térmica"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 211.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Remover IP da manutenção"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 218.5, 4, 4);
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Cell(190, 7, utf8_decode("Smart Capture"), 'B', 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(204, 204, 204);
$pdf->Rect(195, 225.5, 4, 4);
$pdf->Ln();
*/

// Output PDF
$fileName = 'Etiqueta - OS#' . $OsId . '.pdf';
$pdf->Output('I', $fileName);
