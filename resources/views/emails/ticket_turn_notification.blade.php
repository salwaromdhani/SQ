<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre ticket est appelé</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111;">
    <h1>Votre ticket est en cours</h1>
    <p>Bonjour {{ $ticket->full_name }},</p>
    <p>Le ticket <strong>{{ $ticket->ticket_number }}</strong> pour le service <strong>{{ $ticket->service->name }}</strong> est maintenant en cours de traitement.</p>
    <p>Merci de vous présenter au guichet dès que possible.</p>
    <p>Si vous avez besoin d'aide, contactez-nous.</p>
</body>
</html>
