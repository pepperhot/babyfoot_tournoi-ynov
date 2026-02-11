<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partie en cours - Babyfoot</title>
    <style>
        .game-container {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 40px auto;
        }
        .vs-badge {
            background: #e74c3c;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 1.5rem;
            margin: 20px 0;
            display: inline-block;
        }
        .player-name {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="game-container">
    <h2 style="color: #667eea; margin-bottom: 30px;">⚽ Partie en cours</h2>
    
    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
        <div class="player-name">Vous</div>
        <div class="vs-badge pulse">VS</div>
        <div class="player-name"><?= htmlspecialchars($opponent['name']) ?></div>
    </div>

    <div style="margin-top: 50px;">
        <p style="color: #7f8c8d; margin-bottom: 30px;">La partie est lancée ! Que le meilleur gagne.</p>
        
        <a href="match-result.php?id=<?= $game['id'] ?>" class="btn-save" style="background: linear-gradient(135deg, #e74c3c, #c0392b); text-decoration: none; padding: 15px 30px; display: inline-block; font-size: 1.2rem;">
            🏁 Quitter la partie (Entrer le score)
        </a>
    </div>
</div>

</body>
</html>
