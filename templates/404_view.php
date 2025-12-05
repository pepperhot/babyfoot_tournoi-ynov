<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur 404 - Page Non Trouvée</title>

    <!-- Chemin CORRECT pour le CSS (car DocumentRoot = public) -->
    <link rel="stylesheet" href="/css/style.css">

    <style>
        .error-container {
            text-align: center;
            padding: 50px;
        }
        .error-code {
            font-size: 8em;
            color: #e74c3c;
            margin: 0;
            line-height: 1;
        }
        .error-message {
            font-size: 1.5em;
            margin: 10px 0 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">

        <nav>
            <!-- Correct -->
            <a href="/dashboard.php">Accueil</a>
        </nav>

        <div class="error-container">
            <h1 class="error-code">404</h1>
            <h2 class="error-message">Oups ! Cette page n'existe pas.</h2>
            <p>
                Il semble que vous ayez suivi un lien invalide ou que la page ait été déplacée. 
            </p>

            <p style="margin-top: 30px;">
                <!-- Correct -->
                <a href="/dashboard.php" style="background:#27ae60;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
                    Retourner au Tableau de Bord
                </a>
            </p>
        </div>

    </div>
</body>
</html>
