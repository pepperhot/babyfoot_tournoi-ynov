# 🔧 Guide de Résolution des Problèmes

## Problèmes Identifiés

### 1. ❌ Le classement ne s'affiche pas dans le profil
**Cause** : Les colonnes `pseudo` et `score` manquent dans la table `users`

### 2. ❌ L'admin ne peut pas modifier les utilisateurs
**Cause** : Les colonnes `pseudo` et `score` n'existent pas dans la base de données

## 🚀 Solution : Exécuter la Migration

### Méthode 1 : Via le navigateur (RECOMMANDÉ)

1. Ouvrez votre navigateur
2. Allez sur : `http://localhost/migrate.php`
3. La migration s'exécutera automatiquement
4. Vérifiez que tout est ✓ vert ou ℹ orange (déjà existant)

### Méthode 2 : Via phpMyAdmin

1. Ouvrez phpMyAdmin
2. Sélectionnez votre base de données `babyfoot_db`
3. Allez dans l'onglet "SQL"
4. Copiez et exécutez le code suivant :

```sql
-- Ajouter les colonnes manquantes
ALTER TABLE users ADD COLUMN IF NOT EXISTS pseudo VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS score INT DEFAULT 0;
ALTER TABLE tournaments ADD COLUMN IF NOT EXISTS event_date DATETIME DEFAULT NULL;

-- Mettre à jour les pseudos vides
UPDATE users SET pseudo = username WHERE pseudo IS NULL OR pseudo = '';

-- Créer les index
CREATE INDEX IF NOT EXISTS idx_users_pseudo ON users(pseudo);
CREATE INDEX IF NOT EXISTS idx_users_score ON users(score);
```

### Méthode 3 : Via Terminal MySQL

```bash
# Se connecter à MySQL
mysql -u babyuser -p babyfoot_db

# Exécuter le fichier de migration
source sql/migration_add_columns.sql

# Ou exécuter directement les commandes
ALTER TABLE users ADD COLUMN pseudo VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN score INT DEFAULT 0;
UPDATE users SET pseudo = username WHERE pseudo IS NULL OR pseudo = '';
```

## ✅ Vérification

Après la migration, vérifiez que :

1. **Page Profil** (`/public/profile.php`) :
   - ✓ Les statistiques s'affichent
   - ✓ Le classement apparaît (si vous avez joué des matchs)
   - ✓ Vous pouvez modifier votre pseudo et email

2. **Panel Admin** (`/public/admin/users.php`) :
   - ✓ Le tableau affiche la colonne "Score"
   - ✓ Le bouton "✏️ Modifier" ouvre un modal
   - ✓ Vous pouvez modifier : email, pseudo, score, statut admin
   - ✓ Les modifications sont enregistrées

## 🎯 Test Rapide

1. Connectez-vous en tant qu'admin
2. Allez sur `/public/admin/users.php`
3. Cliquez sur "✏️ Modifier" pour un utilisateur
4. Changez le score à 100
5. Enregistrez
6. Vérifiez que le score est mis à jour dans le tableau

## ⚠️ Notes Importantes

- La colonne `score` dans `users` est pour un affichage rapide
- Les vrais points sont calculés depuis la table `scores`
- Si vous modifiez le score manuellement, il peut différer du total des matchs
- Le classement se base sur la somme des points de la table `scores`, pas sur `users.score`

## 🐛 Si ça ne fonctionne toujours pas

1. Vérifiez les erreurs PHP : `tail -f /var/log/apache2/error.log` (Linux) ou consultez les logs XAMPP
2. Vérifiez la connexion à la base : `config/db.php`
3. Vérifiez que les colonnes existent :
   ```sql
   DESCRIBE users;
   ```
4. Videz le cache du navigateur (Ctrl + F5)

## 📞 Support

Si le problème persiste, vérifiez :
- Version MySQL : doit être >= 5.7
- Version PHP : doit être >= 7.4
- Extensions PHP activées : PDO, pdo_mysql
