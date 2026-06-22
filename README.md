# EVSolutions — site statique

Site vitrine monopage en français pour EVSolutions, construit avec HTML5, CSS3, JavaScript vanilla et un endpoint `contact.php` compatible PHP 8.

## Déploiement cPanel

1. Ouvrir **File Manager** dans cPanel.
2. Envoyer tous les fichiers à la racine du domaine ou du sous-domaine : `index.html`, `style.css`, `script.js`, `contact.php` et `README.md`.
3. Vérifier que PHP 8 ou supérieur est sélectionné dans **Select PHP Version**.
4. Ouvrir le site sur le domaine public et vérifier que les fichiers CSS/JS se chargent correctement.
5. Tester `contact.php` depuis le domaine public avec le formulaire, puis vérifier que la réponse affichée est bien un message JSON interprété par le JavaScript. Certains hébergeurs désactivent `mail()` en environnement de prévisualisation.

## Changer l'email de contact

Modifier la variable `$to` dans `contact.php`. Mettre aussi à jour les métadonnées `email` du JSON-LD dans `index.html` si l'adresse publique change.

## Changer le numéro WhatsApp ou téléphone

Dans `index.html`, remplacer les valeurs `+212786749186` et `212786749186` dans les liens `tel:` et `https://wa.me/`. Mettre également à jour le champ `telephone` du JSON-LD.

## Images et logos

Aucun fichier JPG, PNG ou SVG n’est inclus, car les fichiers binaires ne sont pas pris en charge dans ce livrable. Les visuels produit et logos sont donc rendus en HTML/CSS. Pour ajouter de vraies images plus tard, héberger les fichiers côté serveur puis mettre à jour les emplacements correspondants dans `index.html` et les styles dans `style.css`.

## Mettre à jour la spécification

- Si des images sont ajoutées ultérieurement, garder des chemins relatifs.
- Ne pas ajouter de certifications, statistiques, avis clients ou déclarations de distribution officielle sans preuve vérifiable.
- Ne pas promettre une conformité ONEE ferme sans audit technique et validation réglementaire.
- Les seules animations prévues sont : grille énergétique du hero, reveal au scroll avec IntersectionObserver et spotlight desktop des cartes produit.

## Placeholders à personnaliser

- URL canonique `https://www.evsolutions.ma/` dans `index.html`.
- Email destinataire `contact@evsolutions.ma` dans `contact.php`.
- Numéro téléphone/WhatsApp `+212786749186`.
- Visuels actuels générés en CSS, sans fichiers binaires.

## Dépannage mail

Pour tester `contact.php`, envoyer le formulaire depuis la page après plus de 4 secondes, avec le champ anti-spam masqué vide. Le script doit toujours répondre en JSON. Si le formulaire retourne une erreur serveur :

1. Vérifier que la fonction PHP `mail()` est autorisée par l'hébergeur.
2. Utiliser une adresse `From` appartenant au domaine hébergé, par exemple `no-reply@votre-domaine.ma`.
3. Configurer SPF, DKIM et DMARC dans la zone DNS.
4. Consulter les logs cPanel ou demander à l'hébergeur si l'envoi SMTP authentifié est requis.
5. Si nécessaire, remplacer `mail()` par une bibliothèque SMTP compatible PHP 8, par exemple PHPMailer, en conservant la validation serveur existante.
