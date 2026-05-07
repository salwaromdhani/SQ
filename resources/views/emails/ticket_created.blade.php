<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket créé</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111;">
    <h1>Votre ticket a bien été créé</h1>
    <p>Bonjour {{ $ticket->full_name }},</p>
    <p>Votre ticket <strong>{{ $ticket->ticket_number }}</strong> a été créé pour le service <strong>{{ $ticket->service->name }}</strong>.</p>
    <p>Temps d'attente estimé : <strong>{{ $ticket->estimated_wait_time }} minutes</strong>.</p>
    <p>Vous pouvez suivre votre ticket en ligne ici : <a href="{{ route('client.tickets.show', $ticket) }}">Consulter mon ticket</a>.</p>
    <p>Merci d'utiliser notre file d'attente virtuelle.</p>
</body>
</html>
