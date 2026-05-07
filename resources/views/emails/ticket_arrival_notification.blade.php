<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre ticket approche</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111;">
    <h1>Votre ticket approche</h1>
    <p>Bonjour {{ $ticket->full_name }},</p>
    <p>Votre ticket <strong>{{ $ticket->ticket_number }}</strong> pour le service <strong>{{ $ticket->service->name }}</strong> approche. Le temps d'attente estimé est maintenant de <strong>{{ $ticket->estimated_wait_time }} minutes</strong>.</p>
    <p>Nous vous recommandons de vous préparer pour le guichet.</p>
    <p>Vous pouvez consulter votre ticket ici : <a href="{{ route('client.tickets.show', $ticket) }}">Voir mon ticket</a>.</p>
</body>
</html>
