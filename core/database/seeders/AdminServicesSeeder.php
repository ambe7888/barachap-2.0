<?php

namespace Database\Seeders;

use App\Models\Backend\Admin;
use App\Models\Backend\Category;
use App\Models\Backend\SubCategory;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\ServiceExclude;
use App\Models\ServiceFaq;
use App\Models\ServiceInclude;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminServicesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();
        $adminId = $admin ? $admin->id : 1;

        $servicesData = [
            [
                'category' => 'Coiffure et beauté',
                'category_slug' => 'coiffure-et-beaute',
                'sub_category' => 'Esthétique / Soin du visage',
                'sub_category_slug' => 'esthetique-soin-du-visage',
                'title' => 'Soin du visage et Maquillage professionnel',
                'slug' => 'soin-du-visage-et-maquillage-professionnel',
                'description' => 'Offrez-vous un moment de pure détente avec notre soin du visage complet suivi d\'un maquillage professionnel. Ce service est idéal pour préparer un événement spécial ou simplement pour prendre soin de vous. Nous utilisons des produits de haute qualité, adaptés à votre type de peau, pour un teint lumineux, purifié et éclatant de santé.',
                'video_url' => 'https://www.youtube.com/watch?v=exemple_beaute',
                'price' => 25000,
                'discount_price' => 20000,
                'unit' => 'Prestation',
                'includes' => [
                    ['title' => 'Nettoyage et Maquillage', 'description' => 'Gommage, masque hydratant, et maquillage de soirée.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Épilation des sourcils, pose de faux cils.']
                ],
                'addons' => [
                    ['title' => 'Pose de faux cils', 'price' => 5000, 'quantity' => 1, 'description' => 'Pose soignée de faux cils pour sublimer votre regard.']
                ],
                'faqs' => [
                    ['title' => 'Fournissez-vous les produits ?', 'description' => 'Oui, tous les produits cosmétiques sont inclus dans le service.']
                ]
            ],
            [
                'category' => 'Menuiserie',
                'category_slug' => 'menuiserie',
                'sub_category' => 'Aménagement intérieur',
                'sub_category_slug' => 'amenagement-interieur',
                'title' => 'Fabrication et Pose de Dressing Sur-Mesure',
                'slug' => 'fabrication-et-pose-de-dressing-sur-mesure',
                'description' => 'Optimisez votre espace de vie avec un dressing sur-mesure conçu spécifiquement pour répondre à vos besoins de rangement. Notre équipe de menuisiers qualifiés prend en charge la conception 3D, le choix des matériaux robustes et l\'installation à domicile. Finitions soignées garanties pour s\'intégrer parfaitement à votre décoration.',
                'video_url' => 'https://www.youtube.com/watch?v=exemple_menuiserie',
                'price' => 150000,
                'discount_price' => 140000,
                'unit' => 'Projet',
                'includes' => [
                    ['title' => 'Dressing complet', 'description' => 'Prise de mesures, fourniture du bois, montage et pose des tiroirs.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Peinture murale derrière le meuble.']
                ],
                'addons' => [
                    ['title' => 'Installation d\'éclairage LED intégré', 'price' => 25000, 'quantity' => 1, 'description' => 'Bandeaux LED encastrés avec interrupteur automatique.']
                ],
                'faqs' => [
                    ['title' => 'Quel est le délai de fabrication ?', 'description' => 'Environ 7 à 10 jours ouvrés après validation des mesures.']
                ]
            ],
            [
                'category' => 'Climatisation',
                'category_slug' => 'climatisation',
                'sub_category' => 'Maintenance',
                'sub_category_slug' => 'maintenance-climatisation',
                'title' => 'Entretien et Nettoyage Profond de Climatiseur (Split)',
                'slug' => 'entretien-et-nettoyage-profond-de-climatiseur-split',
                'description' => 'Prolongez la durée de vie de votre appareil et améliorez la qualité de l\'air de votre maison avec ce service d\'entretien complet. Nous démontons les filtres, nettoyons l\'unité intérieure et extérieure avec des produits antibactériens, et vérifions le niveau de gaz réfrigérant pour assurer un refroidissement optimal et silencieux.',
                'video_url' => null,
                'price' => 15000,
                'discount_price' => 12000,
                'unit' => 'Unité (par split)',
                'includes' => [
                    ['title' => 'Révision Split', 'description' => 'Nettoyage des filtres, dépoussiérage du compresseur, test de fonctionnement.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Recharge de gaz (facturée séparément si nécessaire), réparation de pièces cassées.']
                ],
                'addons' => [
                    ['title' => 'Recharge de gaz R410A', 'price' => 15000, 'quantity' => 1, 'description' => 'Recharge complète en fluide frigorigène R410A.']
                ],
                'faqs' => [
                    ['title' => 'L\'intervention salit-elle le mur ?', 'description' => 'Non, nous utilisons des bâches de protection étanches.']
                ]
            ],
            [
                'category' => 'Electricité',
                'category_slug' => 'electricite',
                'sub_category' => 'Installation électrique',
                'sub_category_slug' => 'installation-electrique',
                'title' => 'Installation de Luminaires et Mise aux Normes de Prises',
                'slug' => 'installation-de-luminaires-et-mise-aux-normes-de-prises',
                'description' => 'Vous souhaitez changer vos éclairages ou ajouter de nouvelles prises de courant ? Ce forfait comprend l\'installation sécurisée de vos lustres, appliques murales ou plafonniers, ainsi que la pose ou le remplacement de prises défectueuses. Travail réalisé par un électricien certifié respectant scrupuleusement les normes de sécurité en vigueur.',
                'video_url' => null,
                'price' => 10000,
                'discount_price' => 8000,
                'unit' => 'Heure',
                'includes' => [
                    ['title' => 'Pose standard', 'description' => 'Fixation des luminaires, raccordement au réseau, test de sécurité.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Fourniture des lustres et ampoules (à la charge du client).']
                ],
                'addons' => [
                    ['title' => 'Diagnostic complet du tableau électrique', 'price' => 10000, 'quantity' => 1, 'description' => 'Vérification des disjoncteurs, mise à la terre et différentiels.']
                ],
                'faqs' => [
                    ['title' => 'Faut-il couper le courant pendant les travaux ?', 'description' => 'Oui, pour des raisons de sécurité, le disjoncteur sera temporairement coupé.']
                ]
            ],
            [
                'category' => 'Déménagement',
                'category_slug' => 'demenagement',
                'sub_category' => 'Particuliers',
                'sub_category_slug' => 'particuliers-demenagement',
                'title' => 'Déménagement Résidentiel Formule Confort',
                'slug' => 'demenagement-residentiel-formule-confort',
                'description' => 'Déménagez sans aucun stress grâce à notre formule confort tout inclus. Nos déménageurs professionnels s\'occupent de la mise en carton de vos objets fragiles, du démontage de vos meubles imposants, du transport sécurisé dans un camion capitonné, et du remontage dans votre nouveau domicile. Assurance casse incluse pour une tranquillité totale.',
                'video_url' => null,
                'price' => 80000,
                'discount_price' => 75000,
                'unit' => 'Forfait',
                'includes' => [
                    ['title' => 'Déménagement complet', 'description' => 'Camion, 3 manutentionnaires, sangles, couvertures et démontage/remontage.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Débranchement des appareils de plomberie et gaz.']
                ],
                'addons' => [
                    ['title' => 'Fourniture de 20 cartons renforcés', 'price' => 15000, 'quantity' => 1, 'description' => 'Lot de 20 cartons solides avec rouleau d\'adhésif.']
                ],
                'faqs' => [
                    ['title' => 'Le carburant est-il inclus ?', 'description' => 'Oui, pour les trajets dans un rayon de 30 km.']
                ]
            ],
            [
                'category' => 'Lessive',
                'category_slug' => 'lessive',
                'sub_category' => 'Pressing / Lavage au kilo',
                'sub_category_slug' => 'pressing-lavage-au-kilo',
                'title' => 'Nettoyage et Repassage de Vêtements au Kilo',
                'slug' => 'nettoyage-et-repassage-de-vetements-au-kilo',
                'description' => 'Libérez-vous de la corvée du linge avec notre service de lavage, séchage et repassage soigné. Nous prenons en charge vos vêtements du quotidien, draps et serviettes. Le linge est trié par couleur, lavé avec une lessive hypoallergénique, séché délicatement, puis repassé et plié pour être prêt à être rangé directement dans votre armoire.',
                'video_url' => null,
                'price' => 3000,
                'discount_price' => 2500,
                'unit' => 'Kilo',
                'includes' => [
                    ['title' => 'Lavage complet', 'description' => 'Tri, lavage, séchage, repassage et emballage.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Nettoyage à sec pour costumes ou robes de mariée.']
                ],
                'addons' => [
                    ['title' => 'Service express (livraison en 24h)', 'price' => 2000, 'quantity' => 1, 'description' => 'Traitement prioritaire et restitution sous 24 heures.']
                ],
                'faqs' => [
                    ['title' => 'Venez-vous récupérer le linge à domicile ?', 'description' => 'Oui, la collecte est incluse à partir de 5 kilos.']
                ]
            ],
            [
                'category' => 'Plomberie',
                'category_slug' => 'plomberie',
                'sub_category' => 'Dépannage',
                'sub_category_slug' => 'depannage-plomberie',
                'title' => 'Débouchage de Canalisations et Recherche de Fuite',
                'slug' => 'debouchage-de-canalisations-et-recherche-de-fuite',
                'description' => 'Vos éviers sont bouchés ou vous remarquez une infiltration d\'eau ? Notre plombier expert intervient rapidement pour diagnostiquer et réparer vos installations sanitaires. Nous utilisons un équipement professionnel (furet électrique, caméra d\'inspection) pour déboucher vos tuyaux, réparer les fuites sous évier ou remplacer les joints défectueux.',
                'video_url' => null,
                'price' => 20000,
                'discount_price' => 18000,
                'unit' => 'Intervention',
                'includes' => [
                    ['title' => 'Dépannage', 'description' => 'Déplacement, diagnostic de la fuite, débouchage manuel ou mécanique.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Remplacement complet d\'un équipement sanitaire (lavabo, WC).']
                ],
                'addons' => [
                    ['title' => 'Remplacement de robinet mitigeur (main d\'oeuvre)', 'price' => 10000, 'quantity' => 1, 'description' => 'Pose et raccordement de votre nouveau robinet mitigeur.']
                ],
                'faqs' => [
                    ['title' => 'Intervenez-vous en urgence le week-end ?', 'description' => 'Oui, un supplément de 5000 est appliqué les dimanches.']
                ]
            ],
            [
                'category' => 'Peinture',
                'category_slug' => 'peinture',
                'sub_category' => 'Rénovation',
                'sub_category_slug' => 'renovation-peinture',
                'title' => 'Rafraîchissement Peinture Intérieure (Mur et Plafond)',
                'slug' => 'rafraichissement-peinture-interieure-mur-et-plafond',
                'description' => 'Redonnez un coup de jeune à votre salon ou votre chambre avec notre service de peinture intérieure. Nous prenons soin de protéger minutieusement vos sols et meubles avant de procéder au rebouchage des petites fissures, au ponçage, puis à l\'application de deux couches de peinture de haute qualité pour un résultat lisse et sans traces.',
                'video_url' => null,
                'price' => 3500,
                'discount_price' => 3000,
                'unit' => 'Mètre carré (m²)',
                'includes' => [
                    ['title' => 'Peinture complète', 'description' => 'Protection de la pièce, préparation des murs (enduit léger), application de 2 couches.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'La fourniture de la peinture (le client choisit et achète sa couleur).']
                ],
                'addons' => [
                    ['title' => 'Fourniture de la peinture blanche mate (par m²)', 'price' => 1500, 'quantity' => 1, 'description' => 'Peinture professionnelle acrylique lavable blanche.']
                ],
                'faqs' => [
                    ['title' => 'Faut-il vider la pièce ?', 'description' => 'Il est préférable de regrouper les meubles au centre, nous les bâcherons.']
                ]
            ],
            [
                'category' => 'Nettoyage',
                'category_slug' => 'nettoyage',
                'sub_category' => 'Nettoyage spécialisé',
                'sub_category_slug' => 'nettoyage-specialise',
                'title' => 'Nettoyage en Profondeur (Fin de chantier / Grand ménage)',
                'slug' => 'nettoyage-en-profondeur-fin-de-chantier-grand-menage',
                'description' => 'Idéal après des travaux de rénovation ou pour un grand ménage de printemps. Notre équipe désinfecte et décrasse chaque recoin de votre domicile : lessivage des murs, désinfection des sanitaires, nettoyage approfondi de la cuisine (four, frigo), lavage des vitres et traitement des sols pour faire disparaître toute trace de poussière ou de ciment.',
                'video_url' => null,
                'price' => 40000,
                'discount_price' => 35000,
                'unit' => 'Forfait (Appartement F3)',
                'includes' => [
                    ['title' => 'Nettoyage total', 'description' => 'Matériel de nettoyage professionnel, produits détergents, 2 agents d\'entretien.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Le débarras de gros gravats de construction.']
                ],
                'addons' => [
                    ['title' => 'Shampouinage d\'un tapis ou moquette', 'price' => 10000, 'quantity' => 1, 'description' => 'Injection/extraction pour éliminer taches et acariens.']
                ],
                'faqs' => [
                    ['title' => 'Fournissez-vous les produits ménagers ?', 'description' => 'Oui, notre équipe vient avec tout le matériel nécessaire.']
                ]
            ],
            [
                'category' => 'Jardinage',
                'category_slug' => 'jardinage',
                'sub_category' => 'Entretien d\'espaces verts',
                'sub_category_slug' => 'entretien-despaces-verts',
                'title' => 'Entretien Complet du Jardin et Tonte de Pelouse',
                'slug' => 'entretien-complet-du-jardin-et-tonte-de-pelouse',
                'description' => 'Profitez d\'un bel espace vert sans faire le moindre effort. Ce service d\'entretien paysager comprend la tonte de votre gazon, le désherbage manuel des allées, la taille de vos petites haies et arbustes, ainsi que le ramassage des feuilles mortes. Nous redonnons à votre jardin son aspect soigné et structuré en toute saison.',
                'video_url' => null,
                'price' => 25000,
                'discount_price' => 22000,
                'unit' => 'Intervention',
                'includes' => [
                    ['title' => 'Entretien standard', 'description' => 'Tonte, taille légère, désherbage et balayage des terrasses.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'L\'élagage de très grands arbres nécessitant une nacelle.']
                ],
                'addons' => [
                    ['title' => 'Évacuation des déchets verts vers la décharge', 'price' => 15000, 'quantity' => 1, 'description' => 'Chargement et transport des résidus végétaux vers le centre de recyclage.']
                ],
                'faqs' => [
                    ['title' => 'Venez-vous avec votre tondeuse ?', 'description' => 'Oui, nous sommes entièrement équipés (tondeuse, taille-haie, etc.).']
                ]
            ],
            [
                'category' => 'Bricolage',
                'category_slug' => 'bricolage',
                'sub_category' => 'Petits travaux',
                'sub_category_slug' => 'petits-travaux-bricolage',
                'title' => 'Montage de Meubles en Kit et Fixations Murales',
                'slug' => 'montage-de-meubles-en-kit-et-fixations-murales',
                'description' => 'Vous avez acheté des meubles chez Ikea ou d\'autres magasins et vous manquez de temps (ou de patience) pour les monter ? Notre "homme toutes mains" s\'occupe de l\'assemblage rapide et solide de vos commodes, lits ou armoires. Nous proposons également la fixation de vos téléviseurs au mur, la pose de tringles à rideaux ou d\'étagères.',
                'video_url' => null,
                'price' => 15000,
                'discount_price' => 12000,
                'unit' => 'Heure',
                'includes' => [
                    ['title' => 'Bricolage à domicile', 'description' => 'Outillage complet, montage de meubles, perçage et fixations murales.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'La modification sur mesure du meuble (découpe de bois).']
                ],
                'addons' => [
                    ['title' => 'Achat et fourniture de chevilles spéciales (Placo)', 'price' => 3000, 'quantity' => 1, 'description' => 'Pack de chevilles Molly haute résistance pour plaques de plâtre.']
                ],
                'faqs' => [
                    ['title' => 'Débarrassez-vous les cartons des meubles ?', 'description' => 'Oui, nous pouvons les plier et les jeter dans vos poubelles.']
                ]
            ],
            [
                'category' => 'Informatique',
                'category_slug' => 'informatique',
                'sub_category' => 'Dépannage logiciel',
                'sub_category_slug' => 'depannage-logiciel',
                'title' => 'Dépannage, Nettoyage PC et Installation de Logiciels',
                'slug' => 'depannage-nettoyage-pc-et-installation-de-logiciels',
                'description' => 'Votre ordinateur est devenu lent ou affiche des erreurs bizarres ? Ce service d\'assistance informatique comprend le diagnostic de votre machine, l\'éradication des virus et malwares, le nettoyage des fichiers temporaires pour accélérer le système, et l\'installation d\'une suite bureautique ou d\'un nouvel antivirus si nécessaire.',
                'video_url' => null,
                'price' => 20000,
                'discount_price' => 15000,
                'unit' => 'Intervention',
                'includes' => [
                    ['title' => 'Maintenance PC', 'description' => 'Suppression de virus, mises à jour Windows, optimisation de la vitesse.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Le remplacement de composants physiques (disque dur, RAM).']
                ],
                'addons' => [
                    ['title' => 'Récupération de données effacées', 'price' => 15000, 'quantity' => 1, 'description' => 'Restauration de fichiers supprimés ou partition endommagée.']
                ],
                'faqs' => [
                    ['title' => 'Intervenez-vous sur Mac et PC ?', 'description' => 'Oui, nos techniciens maîtrisent Windows et macOS.']
                ]
            ],
            [
                'category' => 'Mécanique',
                'category_slug' => 'mecanique',
                'sub_category' => 'Entretien auto',
                'sub_category_slug' => 'entretien-auto',
                'title' => 'Vidange et Révision Complète Automobile à Domicile',
                'slug' => 'vidange-et-revision-complete-automobile-a-domicile',
                'description' => 'Ne perdez plus de temps au garage ! Notre mécanicien mobile se déplace chez vous ou sur votre lieu de travail pour effectuer la vidange de votre moteur. Ce service inclut le remplacement de l\'huile moteur, le changement du filtre à huile, la mise à niveau des liquides (frein, refroidissement, lave-glace) et un contrôle de sécurité de vos freins et pneus.',
                'video_url' => null,
                'price' => 15000,
                'discount_price' => 10000,
                'unit' => 'Main d\'œuvre',
                'includes' => [
                    ['title' => 'Révision de base', 'description' => 'Déplacement, vidange, contrôles visuels de sécurité.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'L\'achat de l\'huile et des filtres (facturés selon le modèle du véhicule).']
                ],
                'addons' => [
                    ['title' => 'Changement des plaquettes de frein avant (main d\'œuvre)', 'price' => 12000, 'quantity' => 1, 'description' => 'Remplacement des plaquettes avec contrôle des disques de frein.']
                ],
                'faqs' => [
                    ['title' => 'Repartez-vous avec l\'huile usagée ?', 'description' => 'Oui, nous la recyclons dans des centres agréés.']
                ]
            ],
            [
                'category' => 'Soutien scolaire',
                'category_slug' => 'soutien-scolaire',
                'sub_category' => 'Collège / Lycée',
                'sub_category_slug' => 'college-lycee-soutien',
                'title' => 'Cours Particuliers de Mathématiques et Physique',
                'slug' => 'cours-particuliers-de-mathematiques-et-physique',
                'description' => 'Aidez votre enfant à surmonter ses difficultés scolaires avec un accompagnement personnalisé. Nos professeurs expérimentés proposent des cours de soutien en sciences (Maths, Physique-Chimie) adaptés au rythme de l\'élève. Le service comprend la reprise des bases, l\'aide aux devoirs, la préparation aux devoirs surveillés et aux examens nationaux (Brevet, Bac).',
                'video_url' => null,
                'price' => 8000,
                'discount_price' => 7000,
                'unit' => 'Heure',
                'includes' => [
                    ['title' => 'Cours personnalisé', 'description' => 'Explication du cours, exercices d\'application, méthodologie d\'apprentissage.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Fournitures scolaires (cahiers, calculatrice).']
                ],
                'addons' => [
                    ['title' => 'Bilan de compétences initial', 'price' => 5000, 'quantity' => 1, 'description' => 'Évaluation complète du niveau et identification des lacunes.']
                ],
                'faqs' => [
                    ['title' => 'Le professeur peut-il donner des cours en ligne ?', 'description' => 'Oui, via Zoom ou Google Meet si vous le souhaitez.']
                ]
            ],
            [
                'category' => 'Garde d\'enfants',
                'category_slug' => 'garde-d-enfants',
                'sub_category' => 'Baby-sitting',
                'sub_category_slug' => 'baby-sitting',
                'title' => 'Baby-sitting en Soirée et Aide au Coucher',
                'slug' => 'baby-sitting-en-soiree-et-aide-au-coucher',
                'description' => 'Profitez de votre soirée en toute sérénité en confiant vos enfants à notre nounou expérimentée et de confiance. Le service comprend la préparation du repas du soir, l\'animation d\'activités calmes ou de jeux éducatifs, l\'aide à la toilette, la lecture d\'une histoire et la mise au lit en douceur en respectant la routine habituelle de l\'enfant.',
                'video_url' => null,
                'price' => 5000,
                'discount_price' => 4500,
                'unit' => 'Heure',
                'includes' => [
                    ['title' => 'Garde sécurisée', 'description' => 'Surveillance active, repas, activités ludiques, rituel du coucher.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'Le ménage de la maison (hors rangement des jouets utilisés).']
                ],
                'addons' => [
                    ['title' => 'Aide aux devoirs avant le repas', 'price' => 2000, 'quantity' => 1, 'description' => 'Supervision des leçons et des devoirs scolaires.']
                ],
                'faqs' => [
                    ['title' => 'La nounou a-t-elle une formation aux premiers secours ?', 'description' => 'Oui, tout notre personnel est formé aux gestes de premiers secours.']
                ]
            ],
            [
                'category' => 'Pâtisserie',
                'category_slug' => 'patisserie',
                'sub_category' => 'Gâteaux d\'événements',
                'sub_category_slug' => 'gateaux-devenements',
                'title' => 'Création de Gâteau d\'Anniversaire Personnalisé (Cake Design)',
                'slug' => 'creation-de-gateau-danniversaire-personnalise-cake-design',
                'description' => 'Éblouissez vos invités avec un gâteau aussi beau que délicieux. Notre chef pâtissier conçoit des gâteaux sur-mesure (Layer cakes, Number cakes, Cake design) selon le thème de votre choix. Les saveurs des génoises et des crèmes sont 100% personnalisables. Nous garantissons l\'utilisation d\'ingrédients frais et de qualité pour un rendu spectaculaire.',
                'video_url' => null,
                'price' => 35000,
                'discount_price' => 32000,
                'unit' => 'Pièce (10 à 12 parts)',
                'includes' => [
                    ['title' => 'Gâteau complet', 'description' => 'Création, décoration en pâte à sucre ou crème, boîte de transport.']
                ],
                'excludes' => [
                    ['title' => 'Non inclus', 'description' => 'La livraison à domicile (retrait en atelier par défaut).']
                ],
                'addons' => [
                    ['title' => 'Livraison sécurisée à domicile', 'price' => 5000, 'quantity' => 1, 'description' => 'Livraison réfrigérée directement sur le lieu de l\'événement.']
                ],
                'faqs' => [
                    ['title' => 'Combien de temps à l\'avance dois-je commander ?', 'description' => 'Il est conseillé de passer commande au moins 72 heures à l\'avance.']
                ]
            ],
        ];

        foreach ($servicesData as $item) {
            // 1. Get or create category
            $category = Category::firstOrCreate(
                ['slug' => $item['category_slug']],
                [
                    'name' => $item['category'],
                    'status' => 1,
                ]
            );

            // 2. Get or create subcategory
            $subCategory = SubCategory::firstOrCreate(
                [
                    'category_id' => $category->id,
                    'slug' => $item['sub_category_slug']
                ],
                [
                    'name' => $item['sub_category'],
                    'status' => 1,
                ]
            );

            // 3. Create or update service for Admin
            $service = Service::updateOrCreate(
                [
                    'admin_id' => $adminId,
                    'slug' => $item['slug'],
                ],
                [
                    'category_id' => $category->id,
                    'sub_category_id' => $subCategory->id,
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'unit' => $item['unit'],
                    'price' => $item['price'],
                    'discount_price' => $item['discount_price'],
                    'video_url' => $item['video_url'],
                    'status' => 1,
                    'is_published' => 1,
                    'published_at' => now(),
                    'is_featured' => 1,
                    'allocate_staff' => 0,
                    'disable_staff' => 0,
                ]
            );

            // 4. Update Includes
            $service->includes()->forceDelete();
            foreach ($item['includes'] as $inc) {
                ServiceInclude::create([
                    'service_id' => $service->id,
                    'title' => $inc['title'],
                    'description' => $inc['description'],
                ]);
            }

            // 5. Update Excludes
            $service->excludes()->forceDelete();
            foreach ($item['excludes'] as $exc) {
                ServiceExclude::create([
                    'service_id' => $service->id,
                    'title' => $exc['title'],
                    'description' => $exc['description'],
                ]);
            }

            // 6. Update Addons
            $service->addons()->forceDelete();
            foreach ($item['addons'] as $addon) {
                ServiceAddon::create([
                    'service_id' => $service->id,
                    'title' => $addon['title'],
                    'price' => $addon['price'],
                    'quantity' => $addon['quantity'],
                    'description' => $addon['description'],
                ]);
            }

            // 7. Update FAQs
            $service->faqs()->forceDelete();
            foreach ($item['faqs'] as $faq) {
                ServiceFaq::create([
                    'service_id' => $service->id,
                    'title' => $faq['title'],
                    'description' => $faq['description'],
                ]);
            }
        }
    }
}
