<?php

namespace App\Controller\Ticket;

use App\Entity\Ticket;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Doctrine\ORM\EntityManager;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use App\Repository\TicketRepository;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\ValidationException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ticketController 
{
    private $result;
    
     /**
     * @Route("/generate_qr_code", name="generate_qr_code")
     */
    public function generateQrCode(Request $request)
    {
        $writer = new PngWriter();
        $ticketKey = $request->request->get('ticket_key');
    
        // Générer le QR code correspondant à $ticketKey
        // Utilisez votre logique pour générer le QR code à partir de $ticketKey
    
        // Exemple : créer un objet QrCode avec la bibliothèque Endroid\QrCode
        $qrCode = QrCode::create($ticketKey);
    
        // Convertir le QR code en image PNG
        // $qrCode->writeFile('chemin_vers_le_fichier.png');
        $this->result = $writer->write($qrCode);
    
        // Envoyer le fichier PNG en réponse
        // $response = new Response(file_get_contents('chemin_vers_le_fichier.png'));
        // $response->headers->set('Content-Type', 'image/png');
        $dataUri = $this->result->getDataUri();
        return new Response('<img src="'.$dataUri.'" alt="QR Code">');
    }
    
}