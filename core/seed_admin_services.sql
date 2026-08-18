-- ==========================================================
-- BARA-CHAP : Insertion des Catégories, Sous-catégories et Services Admin
-- ==========================================================

-- 1. Catégories
INSERT INTO categories (id, name, slug, status, created_at, updated_at) VALUES 
(1, 'Coiffure et beauté', 'coiffure-et-beaute', 1, NOW(), NOW()),
(2, 'Menuiserie', 'menuiserie', 1, NOW(), NOW()),
(3, 'Climatisation', 'climatisation', 1, NOW(), NOW()),
(4, 'Electricité', 'electricite', 1, NOW(), NOW()),
(5, 'Transport', 'transport', 1, NOW(), NOW()),
(6, 'Lessive', 'lessive', 1, NOW(), NOW()),
(7, 'Plomberie', 'plomberie', 1, NOW(), NOW()),
(8, 'Peinture', 'peinture', 1, NOW(), NOW()),
(9, 'Nettoyage', 'nettoyage', 1, NOW(), NOW()),
(10, 'Jardinage', 'jardinage', 1, NOW(), NOW()),
(11, 'Bricolage', 'bricolage', 1, NOW(), NOW()),
(12, 'Déménagement', 'demenagement', 1, NOW(), NOW()),
(13, 'Informatique', 'informatique', 1, NOW(), NOW()),
(14, 'Mécanique', 'mecanique', 1, NOW(), NOW()),
(15, 'Soutien scolaire', 'soutien-scolaire', 1, NOW(), NOW()),
(16, 'Garde d''enfants', 'garde-d-enfants', 1, NOW(), NOW()),
(17, 'Pâtisserie', 'patisserie', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name), status=1, updated_at=NOW();

-- 2. Sous-catégories
INSERT INTO sub_categories (id, category_id, name, slug, status, created_at, updated_at) VALUES 
(1, 1, 'Esthétique / Soin du visage', 'esthetique-soin-du-visage', 1, NOW(), NOW()),
(2, 2, 'Aménagement intérieur', 'amenagement-interieur', 1, NOW(), NOW()),
(3, 3, 'Maintenance', 'maintenance-climatisation', 1, NOW(), NOW()),
(4, 4, 'Installation électrique', 'installation-electrique', 1, NOW(), NOW()),
(5, 12, 'Particuliers', 'particuliers-demenagement', 1, NOW(), NOW()),
(6, 6, 'Pressing / Lavage au kilo', 'pressing-lavage-au-kilo', 1, NOW(), NOW()),
(7, 7, 'Dépannage', 'depannage-plomberie', 1, NOW(), NOW()),
(8, 8, 'Rénovation', 'renovation-peinture', 1, NOW(), NOW()),
(9, 9, 'Nettoyage spécialisé', 'nettoyage-specialise', 1, NOW(), NOW()),
(10, 10, 'Entretien d''espaces verts', 'entretien-despaces-verts', 1, NOW(), NOW()),
(11, 11, 'Petits travaux', 'petits-travaux-bricolage', 1, NOW(), NOW()),
(12, 13, 'Dépannage logiciel', 'depannage-logiciel', 1, NOW(), NOW()),
(13, 14, 'Entretien auto', 'entretien-auto', 1, NOW(), NOW()),
(14, 15, 'Collège / Lycée', 'college-lycee-soutien', 1, NOW(), NOW()),
(15, 16, 'Baby-sitting', 'baby-sitting', 1, NOW(), NOW()),
(16, 17, 'Gâteaux d''événements', 'gateaux-devenements', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name), category_id=VALUES(category_id), status=1, updated_at=NOW();

-- 3. Services Admin
INSERT INTO services (id, admin_id, category_id, sub_category_id, title, slug, description, video_url, price, discount_price, unit, status, is_published, published_at, is_featured, allocate_staff, disable_staff, created_at, updated_at) VALUES 
(1, 1, 1, 1, 'Soin du visage et Maquillage professionnel', 'soin-du-visage-et-maquillage-professionnel', 'Offrez-vous un moment de pure détente avec notre soin du visage complet suivi d\'un maquillage professionnel. Ce service est idéal pour préparer un événement spécial ou simplement pour prendre soin de vous. Nous utilisons des produits de haute qualité, adaptés à votre type de peau, pour un teint lumineux, purifié et éclatant de santé.', 'https://www.youtube.com/watch?v=exemple_beaute', 25000.00, 20000.00, 'Prestation', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(2, 1, 2, 2, 'Fabrication et Pose de Dressing Sur-Mesure', 'fabrication-et-pose-de-dressing-sur-mesure', 'Optimisez votre espace de vie avec un dressing sur-mesure conçu spécifiquement pour répondre à vos besoins de rangement. Notre équipe de menuisiers qualifiés prend en charge la conception 3D, le choix des matériaux robustes et l\'installation à domicile. Finitions soignées garanties pour s\'intégrer parfaitement à votre décoration.', 'https://www.youtube.com/watch?v=exemple_menuiserie', 150000.00, 140000.00, 'Projet', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(3, 1, 3, 3, 'Entretien et Nettoyage Profond de Climatiseur (Split)', 'entretien-et-nettoyage-profond-de-climatiseur-split', 'Prolongez la durée de vie de votre appareil et améliorez la qualité de l\'air de votre maison avec ce service d\'entretien complet. Nous démontons les filtres, nettoyons l\'unité intérieure et extérieure avec des produits antibactériens, et vérifions le niveau de gaz réfrigérant pour assurer un refroidissement optimal et silencieux.', NULL, 15000.00, 12000.00, 'Unité (par split)', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(4, 1, 4, 4, 'Installation de Luminaires et Mise aux Normes de Prises', 'installation-de-luminaires-et-mise-aux-normes-de-prises', 'Vous souhaitez changer vos éclairages ou ajouter de nouvelles prises de courant ? Ce forfait comprend l\'installation sécurisée de vos lustres, appliques murales ou plafonniers, ainsi que la pose ou le remplacement de prises défectueuses. Travail réalisé par un électricien certifié respectant scrupuleusement les normes de sécurité en vigueur.', NULL, 10000.00, 8000.00, 'Heure', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(5, 1, 12, 5, 'Déménagement Résidentiel Formule Confort', 'demenagement-residentiel-formule-confort', 'Déménagez sans aucun stress grâce à notre formule confort tout inclus. Nos déménageurs professionnels s\'occupent de la mise en carton de vos objets fragiles, du démontage de vos meubles imposants, du transport sécurisé dans un camion capitonné, et du remontage dans votre nouveau domicile. Assurance casse incluse pour une tranquillité totale.', NULL, 80000.00, 75000.00, 'Forfait', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(6, 1, 6, 6, 'Nettoyage et Repassage de Vêtements au Kilo', 'nettoyage-et-repassage-de-vetements-au-kilo', 'Libérez-vous de la corvée du linge avec notre service de lavage, séchage et repassage soigné. Nous prenons en charge vos vêtements du quotidien, draps et serviettes. Le linge est trié par couleur, lavé avec une lessive hypoallergénique, séché délicatement, puis repassé et plié pour être prêt à être rangé directement dans votre armoire.', NULL, 3000.00, 2500.00, 'Kilo', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(7, 1, 7, 7, 'Débouchage de Canalisations et Recherche de Fuite', 'debouchage-de-canalisations-et-recherche-de-fuite', 'Vos éviers sont bouchés ou vous remarquez une infiltration d\'eau ? Notre plombier expert intervient rapidement pour diagnostiquer et réparer vos installations sanitaires. Nous utilisons un équipement professionnel (furet électrique, caméra d\'inspection) pour déboucher vos tuyaux, réparer les fuites sous évier ou remplacer les joints défectueux.', NULL, 20000.00, 18000.00, 'Intervention', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(8, 1, 8, 8, 'Rafraîchissement Peinture Intérieure (Mur et Plafond)', 'rafraichissement-peinture-interieure-mur-et-plafond', 'Redonnez un coup de jeune à votre salon ou votre chambre avec notre service de peinture intérieure. Nous prenons soin de protéger minutieusement vos sols et meubles avant de procéder au rebouchage des petites fissures, au ponçage, puis à l\'application de deux couches de peinture de haute qualité pour un résultat lisse et sans traces.', NULL, 3500.00, 3000.00, 'Mètre carré (m²)', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(9, 1, 9, 9, 'Nettoyage en Profondeur (Fin de chantier / Grand ménage)', 'nettoyage-en-profondeur-fin-de-chantier-grand-menage', 'Idéal après des travaux de rénovation ou pour un grand ménage de printemps. Notre équipe désinfecte et décrasse chaque recoin de votre domicile : lessivage des murs, désinfection des sanitaires, nettoyage approfondi de la cuisine (four, frigo), lavage des vitres et traitement des sols pour faire disparaître toute trace de poussière ou de ciment.', NULL, 40000.00, 35000.00, 'Forfait (Appartement F3)', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(10, 1, 10, 10, 'Entretien Complet du Jardin et Tonte de Pelouse', 'entretien-complet-du-jardin-et-tonte-de-pelouse', 'Profitez d\'un bel espace vert sans faire le moindre effort. Ce service d\'entretien paysager comprend la tonte de votre gazon, le désherbage manuel des allées, la taille de vos petites haies et arbustes, ainsi que le ramassage des feuilles mortes. Nous redonnons à votre jardin son aspect soigné et structuré en toute saison.', NULL, 25000.00, 22000.00, 'Intervention', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(11, 1, 11, 11, 'Montage de Meubles en Kit et Fixations Murales', 'montage-de-meubles-en-kit-et-fixations-murales', 'Vous avez acheté des meubles chez Ikea ou d\'autres magasins et vous manquez de temps (ou de patience) pour les monter ? Notre "homme toutes mains" s\'occupe de l\'assemblage rapide et solide de vos commodes, lits ou armoires. Nous proposons également la fixation de vos téléviseurs au mur, la pose de tringles à rideaux ou d\'étagères.', NULL, 15000.00, 12000.00, 'Heure', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(12, 1, 13, 12, 'Dépannage, Nettoyage PC et Installation de Logiciels', 'depannage-nettoyage-pc-et-installation-de-logiciels', 'Votre ordinateur est devenu lent ou affiche des erreurs bizarres ? Ce service d\'assistance informatique comprend le diagnostic de votre machine, l\'éradication des virus et malwares, le nettoyage des fichiers temporaires pour accélérer le système, et l\'installation d\'une suite bureautique ou d\'un nouvel antivirus si nécessaire.', NULL, 20000.00, 15000.00, 'Intervention', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(13, 1, 14, 13, 'Vidange et Révision Complète Automobile à Domicile', 'vidange-et-revision-complete-automobile-a-domicile', 'Ne perdez plus de temps au garage ! Notre mécanicien mobile se déplace chez vous ou sur votre lieu de travail pour effectuer la vidange de votre moteur. Ce service inclut le remplacement de l\'huile moteur, le changement du filtre à huile, la mise à niveau des liquides (frein, refroidissement, lave-glace) et un contrôle de sécurité de vos freins et pneus.', NULL, 15000.00, 10000.00, 'Main d\'œuvre', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(14, 1, 15, 14, 'Cours Particuliers de Mathématiques et Physique', 'cours-particuliers-de-mathematiques-et-physique', 'Aidez votre enfant à surmonter ses difficultés scolaires avec un accompagnement personnalisé. Nos professeurs expérimentés proposent des cours de soutien en sciences (Maths, Physique-Chimie) adaptés au rythme de l\'élève. Le service comprend la reprise des bases, l\'aide aux devoirs, la préparation aux devoirs surveillés et aux examens nationaux (Brevet, Bac).', NULL, 8000.00, 7000.00, 'Heure', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(15, 1, 16, 15, 'Baby-sitting en Soirée et Aide au Coucher', 'baby-sitting-en-soiree-et-aide-au-coucher', 'Profitez de votre soirée en toute sérénité en confiant vos enfants à notre nounou expérimentée et de confiance. Le service comprend la préparation du repas du soir, l\'animation d\'activités calmes ou de jeux éducatifs, l\'aide à la toilette, la lecture d\'une histoire et la mise au lit en douceur en respectant la routine habituelle de l\'enfant.', NULL, 5000.00, 4500.00, 'Heure', 1, 1, NOW(), 1, 0, 0, NOW(), NOW()),
(16, 1, 17, 16, 'Création de Gâteau d''Anniversaire Personnalisé (Cake Design)', 'creation-de-gateau-danniversaire-personnalise-cake-design', 'Éblouissez vos invités avec un gâteau aussi beau que délicieux. Notre chef pâtissier conçoit des gâteaux sur-mesure (Layer cakes, Number cakes, Cake design) selon le thème de votre choix. Les saveurs des génoises et des crèmes sont 100% personnalisables. Nous garantissons l\'utilisation d\'ingrédients frais et de qualité pour un rendu spectaculaire.', NULL, 35000.00, 32000.00, 'Pièce (10 à 12 parts)', 1, 1, NOW(), 1, 0, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), price=VALUES(price), discount_price=VALUES(discount_price), status=1, is_published=1, updated_at=NOW();

-- 4. Éléments inclus (service_includes)
DELETE FROM service_includes WHERE service_id BETWEEN 1 AND 16;
INSERT INTO service_includes (service_id, title, description, created_at, updated_at) VALUES 
(1, 'Nettoyage et Maquillage', 'Gommage, masque hydratant, et maquillage de soirée.', NOW(), NOW()),
(2, 'Dressing complet', 'Prise de mesures, fourniture du bois, montage et pose des tiroirs.', NOW(), NOW()),
(3, 'Révision Split', 'Nettoyage des filtres, dépoussiérage du compresseur, test de fonctionnement.', NOW(), NOW()),
(4, 'Pose standard', 'Fixation des luminaires, raccordement au réseau, test de sécurité.', NOW(), NOW()),
(5, 'Déménagement complet', 'Camion, 3 manutentionnaires, sangles, couvertures et démontage/remontage.', NOW(), NOW()),
(6, 'Lavage complet', 'Tri, lavage, séchage, repassage et emballage.', NOW(), NOW()),
(7, 'Dépannage', 'Déplacement, diagnostic de la fuite, débouchage manuel ou mécanique.', NOW(), NOW()),
(8, 'Peinture complète', 'Protection de la pièce, préparation des murs (enduit léger), application de 2 couches.', NOW(), NOW()),
(9, 'Nettoyage total', 'Matériel de nettoyage professionnel, produits détergents, 2 agents d\'entretien.', NOW(), NOW()),
(10, 'Entretien standard', 'Tonte, taille légère, désherbage et balayage des terrasses.', NOW(), NOW()),
(11, 'Bricolage à domicile', 'Outillage complet, montage de meubles, perçage et fixations murales.', NOW(), NOW()),
(12, 'Maintenance PC', 'Suppression de virus, mises à jour Windows, optimisation de la vitesse.', NOW(), NOW()),
(13, 'Révision de base', 'Déplacement, vidange, contrôles visuels de sécurité.', NOW(), NOW()),
(14, 'Cours personnalisé', 'Explication du cours, exercices d\'application, méthodologie d\'apprentissage.', NOW(), NOW()),
(15, 'Garde sécurisée', 'Surveillance active, repas, activités ludiques, rituel du coucher.', NOW(), NOW()),
(16, 'Gâteau complet', 'Création, décoration en pâte à sucre ou crème, boîte de transport.', NOW(), NOW());

-- 5. Éléments exclus (service_excludes)
DELETE FROM service_excludes WHERE service_id BETWEEN 1 AND 16;
INSERT INTO service_excludes (service_id, title, description, created_at, updated_at) VALUES 
(1, 'Non inclus', 'Épilation des sourcils, pose de faux cils.', NOW(), NOW()),
(2, 'Non inclus', 'Peinture murale derrière le meuble.', NOW(), NOW()),
(3, 'Non inclus', 'Recharge de gaz (facturée séparément si nécessaire), réparation de pièces cassées.', NOW(), NOW()),
(4, 'Non inclus', 'Fourniture des lustres et ampoules (à la charge du client).', NOW(), NOW()),
(5, 'Non inclus', 'Débranchement des appareils de plomberie et gaz.', NOW(), NOW()),
(6, 'Non inclus', 'Nettoyage à sec pour costumes ou robes de mariée.', NOW(), NOW()),
(7, 'Non inclus', 'Remplacement complet d\'un équipement sanitaire (lavabo, WC).', NOW(), NOW()),
(8, 'Non inclus', 'La fourniture de la peinture (le client choisit et achète sa couleur).', NOW(), NOW()),
(9, 'Non inclus', 'Le débarras de gros gravats de construction.', NOW(), NOW()),
(10, 'Non inclus', 'L\'élagage de très grands arbres nécessitant une nacelle.', NOW(), NOW()),
(11, 'Non inclus', 'La modification sur mesure du meuble (découpe de bois).', NOW(), NOW()),
(12, 'Non inclus', 'Le remplacement de composants physiques (disque dur, RAM).', NOW(), NOW()),
(13, 'Non inclus', 'L\'achat de l\'huile et des filtres (facturés selon le modèle du véhicule).', NOW(), NOW()),
(14, 'Non inclus', 'Fournitures scolaires (cahiers, calculatrice).', NOW(), NOW()),
(15, 'Non inclus', 'Le ménage de la maison (hors rangement des jouets utilisés).', NOW(), NOW()),
(16, 'Non inclus', 'La livraison à domicile (retrait en atelier par défaut).', NOW(), NOW());

-- 6. Addons (service_addons)
DELETE FROM service_addons WHERE service_id BETWEEN 1 AND 16;
INSERT INTO service_addons (service_id, title, price, quantity, description, created_at, updated_at) VALUES 
(1, 'Pose de faux cils', 5000.00, 1, 'Pose soignée de faux cils pour sublimer votre regard.', NOW(), NOW()),
(2, 'Installation d\'éclairage LED intégré', 25000.00, 1, 'Bandeaux LED encastrés avec interrupteur automatique.', NOW(), NOW()),
(3, 'Recharge de gaz R410A', 15000.00, 1, 'Recharge complète en fluide frigorigène R410A.', NOW(), NOW()),
(4, 'Diagnostic complet du tableau électrique', 10000.00, 1, 'Vérification des disjoncteurs, mise à la terre et différentiels.', NOW(), NOW()),
(5, 'Fourniture de 20 cartons renforcés', 15000.00, 1, 'Lot de 20 cartons solides avec rouleau d\'adhésif.', NOW(), NOW()),
(6, 'Service express (livraison en 24h)', 2000.00, 1, 'Traitement prioritaire et restitution sous 24 heures.', NOW(), NOW()),
(7, 'Remplacement de robinet mitigeur (main d\'oeuvre)', 10000.00, 1, 'Pose et raccordement de votre nouveau robinet mitigeur.', NOW(), NOW()),
(8, 'Fourniture de la peinture blanche mate (par m²)', 1500.00, 1, 'Peinture professionnelle acrylique lavable blanche.', NOW(), NOW()),
(9, 'Shampouinage d\'un tapis ou moquette', 10000.00, 1, 'Injection/extraction pour éliminer taches et acariens.', NOW(), NOW()),
(10, 'Évacuation des déchets verts vers la décharge', 15000.00, 1, 'Chargement et transport des résidus végétaux vers le centre de recyclage.', NOW(), NOW()),
(11, 'Achat et fourniture de chevilles spéciales (Placo)', 3000.00, 1, 'Pack de chevilles Molly haute résistance pour plaques de plâtre.', NOW(), NOW()),
(12, 'Récupération de données effacées', 15000.00, 1, 'Restauration de fichiers supprimés ou partition endommagée.', NOW(), NOW()),
(13, 'Changement des plaquettes de frein avant (main d\'œuvre)', 12000.00, 1, 'Remplacement des plaquettes avec contrôle des disques de frein.', NOW(), NOW()),
(14, 'Bilan de compétences initial', 5000.00, 1, 'Évaluation complète du niveau et identification des lacunes.', NOW(), NOW()),
(15, 'Aide aux devoirs avant le repas', 2000.00, 1, 'Supervision des leçons et des devoirs scolaires.', NOW(), NOW()),
(16, 'Livraison sécurisée à domicile', 5000.00, 1, 'Livraison réfrigérée directement sur le lieu de l\'événement.', NOW(), NOW());

-- 7. FAQs (service_faqs)
DELETE FROM service_faqs WHERE service_id BETWEEN 1 AND 16;
INSERT INTO service_faqs (service_id, title, description, created_at, updated_at) VALUES 
(1, 'Fournissez-vous les produits ?', 'Oui, tous les produits cosmétiques sont inclus dans le service.', NOW(), NOW()),
(2, 'Quel est le délai de fabrication ?', 'Environ 7 à 10 jours ouvrés après validation des mesures.', NOW(), NOW()),
(3, 'L\'intervention salit-elle le mur ?', 'Non, nous utilisons des bâches de protection étanches.', NOW(), NOW()),
(4, 'Faut-il couper le courant pendant les travaux ?', 'Oui, pour des raisons de sécurité, le disjoncteur sera temporairement coupé.', NOW(), NOW()),
(5, 'Le carburant est-il inclus ?', 'Oui, pour les trajets dans un rayon de 30 km.', NOW(), NOW()),
(6, 'Venez-vous récupérer le linge à domicile ?', 'Oui, la collecte est incluse à partir de 5 kilos.', NOW(), NOW()),
(7, 'Intervenez-vous en urgence le week-end ?', 'Oui, un supplément de 5000 est appliqué les dimanches.', NOW(), NOW()),
(8, 'Faut-il vider la pièce ?', 'Il est préférable de regrouper les meubles au centre, nous les bâcherons.', NOW(), NOW()),
(9, 'Fournissez-vous les produits ménagers ?', 'Oui, notre équipe vient avec tout le matériel nécessaire.', NOW(), NOW()),
(10, 'Venez-vous avec votre tondeuse ?', 'Oui, nous sommes entièrement équipés (tondeuse, taille-haie, etc.).', NOW(), NOW()),
(11, 'Débarrassez-vous les cartons des meubles ?', 'Oui, nous pouvons les plier et les jeter dans vos poubelles.', NOW(), NOW()),
(12, 'Intervenez-vous sur Mac et PC ?', 'Oui, nos techniciens maîtrisent Windows et macOS.', NOW(), NOW()),
(13, 'Repartez-vous avec l\'huile usagée ?', 'Oui, nous la recyclons dans des centres agréés.', NOW(), NOW()),
(14, 'Le professeur peut-il donner des cours en ligne ?', 'Oui, via Zoom ou Google Meet si vous le souhaitez.', NOW(), NOW()),
(15, 'La nounou a-t-elle une formation aux premiers secours ?', 'Oui, tout notre personnel est formé aux gestes de premiers secours.', NOW(), NOW()),
(16, 'Combien de temps à l\'avance dois-je commander ?', 'Il est conseillé de passer commande au moins 72 heures à l\'avance.', NOW(), NOW());
