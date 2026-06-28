-- Ajout de Sous-catégories
INSERT INTO sub_categories (id, category_id, name, slug, status, created_at, updated_at) VALUES 
(1, 1, 'Coiffure Homme', 'coiffure-homme', 1, NOW(), NOW()),
(2, 1, 'Coiffure Femme', 'coiffure-femme', 1, NOW(), NOW()),
(3, 1, 'Soins du visage', 'soins-du-visage', 1, NOW(), NOW()),
(4, 7, 'Dépannage fuite', 'depannage-fuite', 1, NOW(), NOW()),
(5, 7, 'Installation sanitaire', 'installation-sanitaire', 1, NOW(), NOW()),
(6, 3, 'Installation Split', 'installation-split', 1, NOW(), NOW()),
(7, 3, 'Entretien / Nettoyage', 'entretien-nettoyage-climatisation', 1, NOW(), NOW()),
(8, 9, 'Nettoyage à domicile', 'nettoyage-a-domicile', 1, NOW(), NOW()),
(9, 9, 'Nettoyage de bureau', 'nettoyage-de-bureau', 1, NOW(), NOW()),
(10, 13, 'Réparation Ordinateur', 'reparation-ordinateur', 1, NOW(), NOW()),
(11, 13, 'Installation Réseau', 'installation-reseau', 1, NOW(), NOW());

-- Ajout de Services de test
INSERT INTO services (id, admin_id, category_id, sub_category_id, title, slug, description, price, status, is_published, view, sold_count, is_featured, allocate_staff, created_at, updated_at) VALUES 
(1, 1, 1, 1, 'Coupe classique homme', 'coupe-classique-homme', 'Coupe de cheveux homme classique avec finition soignée.', 3000.00, 1, 1, 0, 0, 0, 0, NOW(), NOW()),
(2, 1, 1, 2, 'Tresses africaines complètes', 'tresses-africaines', 'Tresses africaines avec ou sans rajouts.', 15000.00, 1, 1, 0, 0, 1, 0, NOW(), NOW()),
(3, 1, 7, 4, 'Réparation de fuite d''eau urgente', 'reparation-fuite-eau', 'Intervention rapide pour stopper et réparer une fuite d''eau.', 5000.00, 1, 1, 0, 0, 1, 0, NOW(), NOW()),
(4, 1, 7, 4, 'Débouchage évier ou toilette', 'debouchage-evier-toilette', 'Débouchage professionnel et rapide.', 10000.00, 1, 1, 0, 0, 0, 0, NOW(), NOW()),
(5, 1, 3, 7, 'Entretien complet climatiseur split', 'entretien-climatiseur', 'Nettoyage des filtres, vérification du gaz et désinfection.', 12000.00, 1, 1, 0, 0, 1, 0, NOW(), NOW()),
(6, 1, 9, 8, 'Grand ménage appartement (3 pièces)', 'grand-menage-3-pieces', 'Nettoyage complet de toutes les pièces, y compris vitres et sols.', 20000.00, 1, 1, 0, 0, 0, 0, NOW(), NOW()),
(7, 1, 13, 10, 'Formatage et installation Windows', 'formatage-installation-windows', 'Réinstallation complète du système avec sauvegarde des données.', 15000.00, 1, 1, 0, 0, 0, 0, NOW(), NOW());
