<?php $__env->startSection('content'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Work+Sans:wght@300;400;500;600;700&display=swap');

    :root {
        --primary: #0EA5E9;
        --primary-dark: #0284C7;
        --secondary: #38BDF8;
        --cta: #F97316;
        --cta-hover: #EA580C;
        --bg-light: #F0F9FF;
        --text-dark: #0C4A6E;
        --text-muted: #475569;
        --card-bg: rgba(255, 255, 255, 0.85);
        --font-heading: 'Outfit', sans-serif;
        --font-body: 'Work Sans', sans-serif;
    }

    body {
        font-family: var(--font-body);
        color: var(--text-dark);
        background-color: #FAFDFE;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
        font-weight: 700;
        color: var(--text-dark);
    }

    /* Global Container */
    .landing-wrapper {
        overflow-x: hidden;
    }

    /* 1. Hero Section */
    .hero-app {
        background: radial-gradient(100% 100% at 50% 0%, rgba(14, 165, 233, 0.15) 0%, rgba(240, 249, 255, 0.5) 100%), #FFFFFF;
        padding: 120px 0 80px 0;
        position: relative;
    }

    .hero-app::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: linear-gradient(to top, #FAFDFE, transparent);
        pointer-events: none;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 50px;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    @media (max-width: 991px) {
        .hero-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .hero-right {
            display: none;
        }
    }

    .hero-left h1 {
        font-size: 3.8rem;
        line-height: 1.15;
        margin-bottom: 20px;
        letter-spacing: -0.02em;
    }

    .hero-left h1 span {
        background: linear-gradient(135deg, var(--primary) 0%, #0284C7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-left p {
        font-size: 1.25rem;
        line-height: 1.6;
        color: var(--text-muted);
        margin-bottom: 40px;
        max-width: 600px;
    }

    @media (max-width: 991px) {
        .hero-left p {
            margin: 0 auto 40px auto;
        }
    }

    /* Search Box */
    .search-box-container {
        background: white;
        padding: 8px;
        border-radius: 100px;
        display: flex;
        box-shadow: 0 20px 40px rgba(12, 74, 110, 0.08);
        border: 1px solid rgba(14, 165, 233, 0.15);
        max-width: 650px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .search-box-container:focus-within {
        border-color: var(--primary);
        box-shadow: 0 20px 40px rgba(14, 165, 233, 0.15);
    }

    @media (max-width: 991px) {
        .search-box-container {
            margin: 0 auto;
        }
    }

    .search-box-container input {
        border: none;
        padding: 15px 25px;
        flex-grow: 1;
        border-radius: 100px;
        outline: none;
        color: var(--text-dark);
        font-size: 1.05rem;
    }

    .search-box-container button {
        background: var(--cta);
        color: white;
        border: none;
        padding: 15px 35px;
        border-radius: 100px;
        font-weight: 600;
        font-size: 1.05rem;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .search-box-container button:hover {
        background-color: var(--cta-hover);
    }

    .search-box-container button:active {
        transform: scale(0.98);
    }

    .popular-tags {
        margin-top: 25px;
        font-size: 0.95rem;
        color: var(--text-muted);
    }

    .popular-tags a {
        color: var(--primary);
        font-weight: 500;
        text-decoration: none;
        margin: 0 8px;
        border-bottom: 1px dashed var(--primary);
        transition: color 0.3s;
    }

    .popular-tags a:hover {
        color: var(--primary-dark);
    }

    /* Device Preview Hero */
    .hero-right {
        display: flex;
        justify-content: center;
        position: relative;
    }

    .hero-circle-bg {
        position: absolute;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, rgba(240, 249, 255, 0) 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    /* CSS Phone Mockup */
    .phone-mockup {
        position: relative;
        width: 270px;
        height: 540px;
        background: #000;
        border-radius: 38px;
        box-shadow: 0 30px 60px rgba(12, 74, 110, 0.25);
        border: 10px solid #1E293B;
        z-index: 2;
        overflow: hidden;
    }

    .phone-notch {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 110px;
        height: 18px;
        background: #1E293B;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        z-index: 10;
    }

    .phone-screen {
        width: 100%;
        height: 100%;
        background: var(--bg-light);
        border-radius: 28px;
        overflow: hidden;
        position: relative;
        padding: 40px 15px 15px 15px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .phone-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .phone-logo {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 1rem;
        color: var(--primary);
    }

    .phone-avatar {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: var(--secondary);
    }

    .phone-card {
        background: white;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .phone-card h4 {
        font-size: 0.8rem;
        margin-bottom: 5px;
    }

    .phone-card p {
        font-size: 0.65rem;
        color: var(--text-muted);
        margin: 0;
    }

    .phone-btn {
        background: var(--primary);
        color: white;
        font-size: 0.7rem;
        text-align: center;
        padding: 8px;
        border-radius: 6px;
        font-weight: 600;
        margin-top: 5px;
    }

    .phone-services-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .phone-service-item {
        background: white;
        border-radius: 8px;
        padding: 8px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    .phone-service-item i {
        font-size: 1.2rem;
        color: var(--primary);
        margin-bottom: 5px;
        display: block;
    }

    .phone-service-item span {
        font-size: 0.6rem;
        font-weight: 500;
        display: block;
    }

    /* Trust Stats Section */
    .trust-stats {
        margin-top: -30px;
        position: relative;
        z-index: 10;
        padding: 0 20px;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 50px rgba(12, 74, 110, 0.06);
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    @media (max-width: 767px) {
        .stats-card {
            grid-template-columns: 1fr;
            gap: 25px;
        }
    }

    .stat-item h3 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .stat-item p {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 500;
    }

    /* 2. Services Section */
    .services-section {
        padding: 100px 0;
        background: #FAFDFE;
    }

    .section-header {
        text-align: center;
        max-width: 600px;
        margin: 0 auto 60px auto;
        padding: 0 20px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .section-header p {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .service-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        box-shadow: 0 10px 30px rgba(12, 74, 110, 0.03);
        border: 1px solid rgba(14, 165, 233, 0.05);
        transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
        text-decoration: none;
        color: var(--text-dark);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .service-card:hover {
        border-color: rgba(14, 165, 233, 0.2);
    }

    .service-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        color: var(--primary);
        font-size: 1.8rem;
        transition: background-color 0.3s, color 0.3s;
    }

    .service-card:hover .service-icon-wrapper {
        background-color: var(--primary);
        color: white;
    }

    .service-card h3 {
        font-size: 1.3rem;
        margin-bottom: 12px;
    }

    .service-card p {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0;
    }

    /* 3. Advantages Section */
    .advantages-section {
        padding: 100px 0;
        background: var(--bg-light);
        position: relative;
    }

    .advantages-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    @media (max-width: 991px) {
        .advantages-grid {
            grid-template-columns: 1fr;
        }
    }

    .advantage-item {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(12, 74, 110, 0.03);
        text-align: center;
    }

    .advantage-icon-wrapper {
        width: 70px;
        height: 70px;
        background: rgba(14, 165, 233, 0.1);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px auto;
        font-size: 2rem;
    }

    .advantage-item h3 {
        font-size: 1.4rem;
        margin-bottom: 15px;
    }

    .advantage-item p {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    /* 4. Features Section (Split Showcase) */
    .features-section {
        padding: 100px 0;
        background: white;
    }

    .features-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    @media (max-width: 991px) {
        .features-grid {
            grid-template-columns: 1fr;
            gap: 50px;
        }
        .features-left {
            order: 2;
        }
    }

    .features-left {
        display: flex;
        justify-content: center;
    }

    .features-right h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .features-right p.lead {
        font-size: 1.15rem;
        color: var(--text-muted);
        margin-bottom: 30px;
    }

    .feature-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .feature-list-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .feature-icon {
        background: var(--bg-light);
        color: var(--primary);
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .feature-info h4 {
        font-size: 1.15rem;
        margin-bottom: 5px;
    }

    .feature-info p {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }

    /* 5. FAQ Section */
    .faq-section {
        padding: 100px 0;
        background: var(--bg-light);
    }

    .faq-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .faq-item {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(12, 74, 110, 0.02);
        border: 1px solid rgba(14, 165, 233, 0.05);
    }

    .faq-item details {
        width: 100%;
    }

    .faq-item summary {
        padding: 22px 30px;
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.15rem;
        color: var(--text-dark);
        cursor: pointer;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        outline: none;
    }

    .faq-item summary::-webkit-details-marker {
        display: none;
    }

    .faq-item summary::after {
        content: '\2b'; /* Plus sign */
        font-family: Arial, sans-serif;
        font-size: 1.2rem;
        color: var(--primary);
        transition: transform 0.3s;
    }

    .faq-item details[open] summary::after {
        content: '\2212'; /* Minus sign */
    }

    .faq-content {
        padding: 0 30px 25px 30px;
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.6;
    }

    /* 6. Dual CTA Section */
    .cta-banner-section {
        padding: 100px 0;
        background: white;
    }

    .cta-banner-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    @media (max-width: 991px) {
        .cta-banner-grid {
            grid-template-columns: 1fr;
        }
    }

    .cta-card-box {
        border-radius: 24px;
        padding: 50px 40px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .cta-card-box.client-cta {
        background: linear-gradient(135deg, #0EA5E9 0%, #0369A1 100%);
    }

    .cta-card-box.provider-cta {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
    }

    .cta-card-box h3 {
        color: white;
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .cta-card-box p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.1rem;
        margin-bottom: 35px;
        line-height: 1.5;
        max-width: 450px;
    }

    .app-badges {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .badge-btn {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: white;
        transition: background 0.3s, transform 0.2s;
    }

    .badge-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-2px);
    }

    .badge-btn i {
        font-size: 1.6rem;
    }

    .badge-btn span {
        display: block;
        text-align: left;
    }

    .badge-btn span small {
        font-size: 0.65rem;
        text-transform: uppercase;
        display: block;
        opacity: 0.8;
    }

    .badge-btn span strong {
        font-size: 0.95rem;
        display: block;
    }

    .cta-link-btn {
        display: inline-block;
        background: var(--cta);
        color: white;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s, transform 0.2s;
    }

    .cta-link-btn:hover {
        background: var(--cta-hover);
        color: white;
        transform: translateY(-2px);
    }

    .app-showcase-img {
        max-width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 45px rgba(12, 74, 110, 0.12);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .app-showcase-img:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(12, 74, 110, 0.18);
    }
</style>

<div class="landing-wrapper">
    <!-- 1. Hero Section -->
    <section class="hero-app">
        <div class="hero-grid">
            <div class="hero-left">
                <h1>Simplifiez vos travaux. <br>Trouvez un <span>Professionnel</span></h1>
                <p>Des électriciens, plombiers, coiffeurs et nettoyeurs qualifiés prêts à intervenir chez vous en quelques clics.</p>
                
                <form action="<?php echo e(url('/services')); ?>" method="GET">
                    <div class="search-box-container">
                        <input type="text" name="q" placeholder="Quel service recherchez-vous ? (Plomberie, Ménage...)" required>
                        <button type="submit">Rechercher</button>
                    </div>
                </form>
                
                <div class="popular-tags">
                    <span>Recherches fréquentes :</span>
                    <a href="<?php echo e(url('/services?q=Coiffure')); ?>">Coiffure</a>
                    <a href="<?php echo e(url('/services?q=Plomberie')); ?>">Plomberie</a>
                    <a href="<?php echo e(url('/services?q=Ménage')); ?>">Ménage</a>
                    <a href="<?php echo e(url('/services?q=Électricité')); ?>">Électricité</a>
                </div>
            </div>
            
            <div class="hero-right">
                <div class="hero-circle-bg"></div>
                <img src="<?php echo e(asset('assets/frontend/img/client-app-preview.png')); ?>" alt="BaraChap Client App" class="app-showcase-img" style="z-index: 2; position: relative;">
            </div>
        </div>
    </section>

    <!-- Trust Stats Section -->
    <div class="trust-stats">
        <div class="stats-card">
            <div class="stat-item">
                <h3>10k+</h3>
                <p>Missions réussies</p>
            </div>
            <div class="stat-item">
                <h3>5k+</h3>
                <p>Professionnels</p>
            </div>
            <div class="stat-item">
                <h3>4.8★</h3>
                <p>Note moyenne</p>
            </div>
        </div>
    </div>

    <!-- 2. Services Section -->
    <section class="services-section">
        <div class="section-header">
            <h2>Nos Catégories de Services</h2>
            <p>Explorez nos catégories les plus populaires et trouvez l'expert idéal pour vos besoins.</p>
        </div>
        
        <div class="services-grid">
            <?php $__currentLoopData = $categories->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="service-card">
                    <div class="service-icon-wrapper">
                        <?php if(!empty($cat->image)): ?>
                            <?php echo render_image_markup_by_attachment_id($cat->image, 'cat-icon'); ?>

                        <?php else: ?>
                            <i class="<?php echo e($cat->icon ?? 'fa-solid fa-layer-group'); ?>"></i>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo e($cat->name); ?></h3>
                    <p><?php echo e(\Illuminate\Support\Str::limit($cat->description ?? '', 100)); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php if($categories->count() > 6): ?>
            <div style="text-align: center; margin-top: 40px; font-size: 1.15rem; color: var(--text-muted); font-weight: 600; font-family: var(--font-heading);">
                <i class="fa-solid fa-ellipsis" style="color: var(--primary); margin-right: 8px;"></i>
                <?php echo e(__('Et bien d\'autres catégories de services disponibles sur l\'application...')); ?>

            </div>
        <?php endif; ?>
    </section>

    <!-- 3. Advantages Section -->
    <section class="advantages-section">
        <div class="section-header">
            <h2>Pourquoi choisir BaraChap ?</h2>
            <p>La plateforme de référence pour connecter clients et prestataires en toute confiance.</p>
        </div>
        
        <div class="advantages-grid">
            <div class="advantage-item">
                <div class="advantage-icon-wrapper">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3>Rapidité</h3>
                <p>Trouvez un professionnel disponible près de chez vous en moins de 10 minutes pour toutes vos urgences.</p>
            </div>
            
            <div class="advantage-item">
                <div class="advantage-icon-wrapper">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3>Sécurité & Confiance</h3>
                <p>Tous nos prestataires sont vérifiés et notés par la communauté. Vos paiements sont sécurisés et bloqués jusqu'à validation de la mission.</p>
            </div>
            
            <div class="advantage-item">
                <div class="advantage-icon-wrapper">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3>Qualité garantie</h3>
                <p>Des experts qualifiés sélectionnés rigoureusement pour vous assurer un service impeccable et professionnel.</p>
            </div>
        </div>
    </section>

    <!-- 4. Features Section -->
    <section class="features-section">
        <div class="features-grid">
            <div class="features-left">
                <img src="<?php echo e(asset('assets/frontend/img/provider-app-preview.png')); ?>" alt="BaraChap Provider App" class="app-showcase-img">
            </div>
            
            <div class="features-right">
                <h2>L'application BaraChap, toujours dans votre poche</h2>
                <p class="lead">Gérez toutes vos demandes de services de manière simple et intuitive grâce à notre application mobile.</p>
                
                <div class="feature-list">
                    <div class="feature-list-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div class="feature-info">
                            <h4>Géolocalisation précise</h4>
                            <p>Trouvez des experts situés à quelques kilomètres de chez vous pour une intervention express.</p>
                        </div>
                    </div>
                    
                    <div class="feature-list-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <div class="feature-info">
                            <h4>Messagerie instantanée</h4>
                            <p>Discutez directement avec le prestataire pour planifier les détails et envoyer des photos du problème.</p>
                        </div>
                    </div>
                    
                    <div class="feature-list-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div class="feature-info">
                            <h4>Paiement en ligne sécurisé</h4>
                            <p>Payez en toute sécurité par Mobile Money ou carte bancaire. Le prestataire n'est payé qu'une fois la tâche finie.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. FAQ Section -->
    <section class="faq-section">
        <div class="section-header">
            <h2>Questions Fréquentes (FAQ)</h2>
            <p>Tout ce que vous devez savoir pour démarrer avec la plateforme BaraChap.</p>
        </div>
        
        <div class="faq-container">
            <div class="faq-item">
                <details open>
                    <summary>Comment fonctionne la réservation sur BaraChap ?</summary>
                    <div class="faq-content">
                        C'est très simple ! Recherchez le service dont vous avez besoin, comparez les profils des prestataires disponibles (tarifs, avis, distance), discutez avec eux via la messagerie intégrée et réservez directement.
                    </div>
                </details>
            </div>
            
            <div class="faq-item">
                <details>
                    <summary>Comment sont vérifiés les prestataires de services ?</summary>
                    <div class="faq-content">
                        Chaque prestataire de BaraChap doit soumettre une pièce d'identité officielle, des justificatifs professionnels ou certifications, et passer une vérification de profil avant de pouvoir proposer ses services.
                    </div>
                </details>
            </div>
            
            <div class="faq-item">
                <details>
                    <summary>Que faire en cas de problème avec une prestation ?</summary>
                    <div class="faq-content">
                        Notre support client est disponible 24h/24 et 7j/7. De plus, les paiements restent bloqués sur un compte de confiance et ne sont reversés au prestataire que lorsque vous confirmez que le travail est bien finalisé.
                    </div>
                </details>
            </div>
            
            <div class="faq-item">
                <details>
                    <summary>Comment s'inscrire en tant que prestataire ?</summary>
                    <div class="faq-content">
                        Cliquez sur "Devenir Prestataire" en haut de la page, remplissez vos informations, ajoutez vos compétences et vos tarifs. Une fois votre profil validé par nos équipes, vous commencerez à recevoir des demandes d'intervention.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- 6. Dual CTA Section -->
    <section class="cta-banner-section">
        <div class="cta-banner-grid">
            <!-- Client CTA -->
            <div class="cta-card-box client-cta">
                <h3>Besoin d'un coup de main ?</h3>
                <p>Téléchargez l'application BaraChap sur votre mobile pour trouver instantanément des professionnels autour de vous.</p>
                <div class="app-badges">
                    <a href="#" class="badge-btn">
                        <i class="fa-brands fa-apple"></i>
                        <span>
                            <small>Télécharger sur l'</small>
                            <strong>App Store</strong>
                        </span>
                    </a>
                    <a href="#" class="badge-btn">
                        <i class="fa-brands fa-google-play"></i>
                        <span>
                            <small>Disponible sur</small>
                            <strong>Google Play</strong>
                        </span>
                    </a>
                </div>
            </div>
            
            <!-- Provider CTA -->
            <div class="cta-card-box provider-cta">
                <h3>Vous êtes professionnel ?</h3>
                <p>Développez votre activité et trouvez facilement de nouveaux clients en rejoignant la communauté des prestataires BaraChap.</p>
                <a href="<?php echo e(route('user.register')); ?>" class="cta-link-btn">Devenir Prestataire</a>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\core\resources\views/frontend/pages/frontend-home.blade.php ENDPATH**/ ?>