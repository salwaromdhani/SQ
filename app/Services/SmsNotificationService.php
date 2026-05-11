<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    private $twilio;
    private $fromNumber;

    public function __construct()
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $this->fromNumber = config('services.twilio.phone_number');

        if ($accountSid && $authToken) {
            $this->twilio = new Client($accountSid, $authToken);
        }
    }

    /**
     * Envoyer une notification SMS de création de ticket
     */
    public function sendTicketCreatedNotification($ticket, $phoneNumber)
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not configured, skipping notification');
            return false;
        }

        $message = sprintf(
            "ESPRIT File: Votre ticket #%s est créé. Service: %s. Position: En attente. Lien: %s",
            $ticket->ticket_number,
            $ticket->service->name,
            route('client.tickets.show', $ticket)
        );

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer une notification SMS d'appel
     */
    public function sendTicketCalledNotification($ticket, $phoneNumber)
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not configured, skipping notification');
            return false;
        }

        $message = sprintf(
            "ESPRIT File: C'est votre tour! Ticket #%s - Service %s. Présentez-vous maintenant.",
            $ticket->ticket_number,
            $ticket->service->name
        );

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer une notification SMS de completion
     */
    public function sendTicketCompletedNotification($ticket, $phoneNumber)
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not configured, skipping notification');
            return false;
        }

        $message = sprintf(
            "ESPRIT File: Votre service #%s est terminé. Merci d'avoir utilisé notre service!",
            $ticket->ticket_number
        );

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer une notification SMS d'annulation
     */
    public function sendTicketCanceledNotification($ticket, $phoneNumber)
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not configured, skipping notification');
            return false;
        }

        $message = sprintf(
            "ESPRIT File: Votre ticket #%s a été annulé. Merci!",
            $ticket->ticket_number
        );

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer une notification SMS personnalisée
     */
    public function sendCustomNotification($phoneNumber, $message)
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not configured, skipping notification');
            return false;
        }

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Envoyer un SMS à plusieurs destinataires
     */
    public function sendBulkNotifications($phoneNumbers, $message)
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS not configured, skipping bulk notification');
            return [];
        }

        $results = [];
        foreach ($phoneNumbers as $phone) {
            $results[$phone] = $this->sendSms($phone, $message);
        }

        return $results;
    }

    /**
     * Envoyer un SMS brut
     */
    private function sendSms($phoneNumber, $message)
    {
        try {
            if (!$this->twilio) {
                Log::warning('Twilio client not initialized');
                return false;
            }

            // Formater le numéro de téléphone
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $message = $this->twilio->messages->create(
                $formattedPhone,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            Log::info('SMS sent successfully', [
                'to' => $formattedPhone,
                'sid' => $message->sid
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'error' => $e->getMessage(),
                'to' => $phoneNumber
            ]);

            return false;
        }
    }

    /**
     * Formater le numéro de téléphone
     */
    private function formatPhoneNumber($phone)
    {
        // Supprimer tous les caractères non numériques
        $phone = preg_replace('/\D/', '', $phone);

        // Ajouter le préfixe de pays s'il n'existe pas
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        // Pour la Tunisie (exemple)
        if (strlen($phone) === 11 && substr($phone, 0, 2) === '+2') {
            // Déjà formaté
            return $phone;
        } elseif (strlen($phone) === 9) {
            // Ajouter le code pays tunisien
            $phone = '+216' . $phone;
        }

        return $phone;
    }

    /**
     * Vérifier si le service est configuré
     */
    public function isConfigured()
    {
        return config('services.twilio.account_sid') &&
               config('services.twilio.auth_token') &&
               config('services.twilio.phone_number');
    }
}