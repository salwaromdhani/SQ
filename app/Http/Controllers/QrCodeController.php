<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    /**
     * Générer et afficher le QR code d'un ticket
     */
    public function show(Ticket $ticket)
    {
        // Vérifier que l'utilisateur peut voir ce ticket
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->isAdmin() || $user->isEmployee()) {
                // Admin et employés peuvent voir tous les tickets
            } elseif ($user->email === $ticket->email) {
                // Les clients peuvent voir leurs propres tickets
            } else {
                abort(403, 'Accès non autorisé.');
            }
        } else {
            // Pour les utilisateurs non connectés, vérifier par email dans l'URL
            $email = request('email');
            if (!$email || $email !== $ticket->email) {
                abort(403, 'Accès non autorisé.');
            }
        }

        // Créer le QR code avec les informations du ticket
        $qrData = json_encode([
            'ticket_number' => $ticket->ticket_number,
            'service' => $ticket->service->name,
            'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
            'status' => $ticket->status,
            'url' => route('client.tickets.show', $ticket)
        ]);

        $qrCode = new QrCode(
            data: $qrData,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Retourner l'image directement
        return response($result->getString())
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Télécharger le QR code
     */
    public function download(Ticket $ticket)
    {
        // Vérifier les permissions comme dans show()
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->isAdmin() || $user->isEmployee()) {
                // Admin et employés peuvent voir tous les tickets
            } elseif ($user->email === $ticket->email) {
                // Les clients peuvent voir leurs propres tickets
            } else {
                abort(403, 'Accès non autorisé.');
            }
        } else {
            $email = request('email');
            if (!$email || $email !== $ticket->email) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $qrData = json_encode([
            'ticket_number' => $ticket->ticket_number,
            'service' => $ticket->service->name,
            'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
            'status' => $ticket->status,
            'url' => route('client.tickets.show', $ticket)
        ]);

        $qrCode = new QrCode(
            data: $qrData,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $filename = 'ticket-' . $ticket->ticket_number . '-qrcode.png';

        return response($result->getString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Afficher la page avec le QR code
     */
    public function page(Ticket $ticket)
    {
        // Vérifier les permissions
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->isAdmin() || $user->isEmployee()) {
                // Admin et employés peuvent voir tous les tickets
            } elseif ($user->email === $ticket->email) {
                // Les clients peuvent voir leurs propres tickets
            } else {
                abort(403, 'Accès non autorisé.');
            }
        } else {
            $email = request('email');
            if (!$email || $email !== $ticket->email) {
                abort(403, 'Accès non autorisé.');
            }
        }

        return view('qr-code', compact('ticket'));
    }
}
