<?php session_start(); include 'db_connect.php'; ?>
<!DOCTYPE html>
    <html>
        <head>
            <title>AT.COLLECTION | Crossbody Bags</title>
            <link href="test2.css" rel="stylesheet" type="text/css">
            <script>
                const currentUserEmail = "<?php echo $_SESSION['user']['email'] ?? 'guest'; ?>";
            </script>  
            <script src="Test2.js" type="text/javascript" defer></script>
            <script src="cart.js" type="text/javascript" defer></script>
            <script>document.addEventListener('DOMContentLoaded', function(){ if(typeof updateCartCount==='function') updateCartCount(); });</script>
            <meta name="robots" content="noindex,nofollow">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta charset="UTF-8">
        </head>
        <body>
            <header class="headline">
                <section class="header-container">
                    <!-- HEADER LEFT AREA-->
                    <div class="header-left">
                        <p class="main-Headline">AT.COLLECTION</p>
                        <h5>Since 1954</h5>
                    </div>
                    <!-- CENTER MENU -->
                    <nav class="primary-menu">
                        <ul>
                            <li><a href="TEST2.php" target="_self">HOME</a></li>
                            <li><a href="TEST2.php#main-section">PRODUCTS</a>
                                <ul class="dropdown">
                                    <li><a href="TEST2.php#best-sellers">Best Sellers</a></li>
                                    <li>
                                        Bags Collection
                                        <ul class="dropdown">
                                            <li><a href="Backpacks.php" target="_blank">Backpacks</a></li>
                                            <li><a href="CrossbodyBags.php" target="_blank">Crossbody Bags</a></li>
                                            <li><a href="ShoulderBags.php" target="_blank">Shoulder Bags</a></li>
                                            <li><a href="ShoppingBags.php" target="_blank">Shopping Bags</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a>ABOUT</a>
                                <ul class="dropdown">
                                    <li><a href="TEST2.php#main-section">History</a></li>
                                    <li>
                                        Team
                                        <ul class="dropdown">
                                            <li><a href="https://www.linkedin.com/in/alexandrostsamprounis/" target="_blank">ALEX</a></li>
                                            <li><a href="https://www.linkedin.com/in/panagiotiszois/" target="_blank">PANOS</a></li>
                                            <li><a href="https://www.linkedin.com/in/georgeorestisgiannakopoulos52611a253/" target="_blank">GEORGE</a></li>
                                        </ul>
                                    </li>
                                    <li>Careers</li>
                                </ul>
                            </li>
                            <li><a href="TEST2.php#secondary-section">CONTACT</a></li>
                            <li>
                                <a href="cart.php" style="color: #ff9d00; font-weight: bold;">
                                    🛒 CART (<span id="cart-count">0</span>)
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="header-right">
                        <?php if (!empty($_SESSION['user'])): ?>
                            <span style="color: #ffa503; margin-right:12px;">Γειά σου, <?php echo htmlspecialchars($_SESSION['user']['firstname']); ?></span>
                            <a href="logout.php" class="auth-link">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="auth-link">Login</a>
                            <a href="TEST2.php#form" class="auth-button">Register</a>
                        <?php endif; ?>
                    </div>
                </section>
            </header>
            <main class="main-content page-home">
                <!--BEST SELLERS SECTION STARTS-->
                <section class="best-sellers" id="category">
                    <div class="best-sellers__header">
                        <h2 class="best-sellers__title">Crossbody Bags Collection!!</h2>
                    </div>
<div class="products-flex">
    <?php
    // Εδώ ορίζουμε ότι θέλουμε μόνο τις Crossbody τσάντες
    $category = 'crossbody'; 
    
    // Η SQL φιλτράρει τα προϊόντα βάσει της κατηγορίας
    $query = "SELECT * FROM products WHERE category = 'crossbody'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <article class="product-card">
                <img class="product-card__img" src="<?php echo $row['image_path']; ?>" alt="Crossbody Bag">
                <div class="product-card__body">
                    <h3 class="product-card__name"><?php echo $row['name']; ?></h3>
                    <p class="product-card__desc"><?php echo htmlspecialchars($row['Description']); ?></p>
                    <p class="product-card__price"><?php echo number_format($row['price'], 2); ?> €</p>
                    
                    <button onclick="addToCart('<?php echo $row['name']; ?>', <?php echo $row['price']; ?>)"
                            class="auth-button" style="width:100%; background:#ff9d00; border:none; cursor:pointer; padding:10px;">
                        Add to Cart
                    </button>
                </div>
            </article>
        <?php } 
    } else {
        echo "<p style='color:white;'>Δεν βρέθηκαν Crossbody τσάντες στη βάση δεδομένων.</p>";
    }
    ?>
</div>
                </section>
                <!--BEST SELLERS SECTION ENDS-->
                <!--MAIN SECTION STARTS-->
                <section id="main-section">
                    <div class="container">
                        <h1>Λίγα λόγια για την εταιρία!</h1>
                        <section class="AT-introduction">
                            <div class="AT-introduction_media">
                                <img src="Assets/Images/Workshop.jpg" alt="AT COLLECTION Workshop" width="100%"><style>img {border-radius: 10px;}</style>
                            </div>
                            <div class="AT-introduction_content">
                                <h2>AT. COLLECTION - ΑΦΟΙ ΤΣΑΠΡΟΥΝΗ Ο.Ε</h2>

                                <p>Η εταιρία μας δραστηριοποιείται στον χώρο της παραγωγής και εμπορίας δερμάτινων ειδών από το 1956, όταν ιδρύθηκε από τον Αλέξανδρο Τσαμπρούνη.</p>

                                <p>Από τα πρώτα της βήματα έως και σήμερα, παραμένει πιστή στις αξίες της ποιότητας, της αξιοπιστίας και της προσοχής στη λεπτομέρεια.</p>

                                <p>Τα προϊόντα μας συνδυάζουν διαχρονικό σχεδιασμό, ανθεκτικά υλικά και σύγχρονη αισθητική, καλύπτοντας τις απαιτήσεις της καθημερινής χρήσης.</p>

                                <p>Μέσα από το ηλεκτρονικό μας κατάστημα, προσφέρουμε ασφαλείς αγορές και εύκολη περιήγηση στις συλλογές μας.</p>

                                <a class="intro-cta" href="TEST2.php#best-sellers" target="_blank">Best Sellers</a>
                            </div>
                        </section>
                        <!--HERO SECTION STARTS-->
                        <section class="hero">
                            <div class="multimedia">
                                <div class="multimedia video">
                                    <h4>Σύντομο video !!</h4>
                                    <video controls width="450" height="auto">
                                        <source src="Assets/Video/PixVerse_V5.5_Image_Text_360P.mp4" type="video/mp4">
                                        <source src="Assets/Video/PixVerse_V5.5_Image_Text_360P.ogv" type="video/ogv">
                                    </video>
                                </div>
                            </div>
                        </section>
                        <!--HERO SECTION ENDS-->
                    </div>  
                </section>
                <!--SECONDARY SECTION STARTS-->
                <section id="secondary-section">
                    <div class="menu secondary-menu">
                        <h2>A.T COLLECTION</h2>

                        <div class="secondary-grid">
                            <!-- LEFT: SVG -->
                            <div class="secondary-left">
                                <div class="svg">
                                    <!--  SVG  -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.573 511.573" width="320" height="320" role="img" aria-labelledby="t d">
                                        <title id="t">AT collection — leather logo (white bg)</title>
                                        <desc id="d">Σύμβολο δέρματος με λευκό φόντο και κείμενο AT collection.</desc>

                                        <!-- ΛΕΥΚΟ ΦΟΝΤΟ -->
                                        <rect x="0" y="0" width="100%" height="100%" fill="#ffffff"></rect>

                                        <style>
                                            /* Leather περίγραμμα */
                                            .hide path
                                            {
                                            fill:none; 
                                            stroke:#111; 
                                            stroke-width:12;
                                            stroke-linejoin:round; 
                                            stroke-linecap:round;
                                            }

                                            /* Τυπογραφία – με καθαρότητα σε άσπρο */
                                            .logoAT, .logoCollection
                                            {
                                            paint-order: stroke fill;       /* πρώτα stroke, μετά fill */
                                            stroke:#111; stroke-width:2;    /* λεπτό περίγραμμα στα γράμματα */
                                            }

                                            .logoAT
                                            {
                                            font-family: Playfair Display, "Times New Roman", Georgia, serif;
                                            font-weight:700; 
                                            letter-spacing:-3px; 
                                            fill:#111;
                                            }

                                            .logoCollection
                                            {
                                            font-family: "Great Vibes", "Brush Script MT", "Segoe Script", cursive;
                                            fill:#111;
                                            }

                                            /* Μικρά tweaks για μικρές οθόνες */
                                            @media (max-width: 420px)
                                            {
                                            .logoAT{ font-size:140px; }
                                            .logoCollection{ font-size:80px; }
                                            }
                                        </style>
                                        <!-- ΤΟ ΣΧΗΜΑ -->
                                        <g class="hide" transform="translate(1 1)">
                                            <g>
                                                <g>
                                                    <path d="M465.56,158.147c2.56-1.707,3.413-5.12,2.56-7.68v-0.853C462.147,120.6,445.933,95,422.04,77.08     
                                                        c-2.56-1.707-5.12-2.56-8.533-0.853c-29.867,10.24-53.76,10.24-72.533-0.853c-29.867-17.92-34.987-60.587-34.987-60.587     
                                                        c0-3.413-2.56-5.973-5.973-6.827c-29.013-11.947-61.44-11.947-91.307,0c-2.56,0.853-5.12,3.413-5.12,6.827    
                                                        c0,0-5.12,41.813-34.987,60.587c-18.773,11.947-43.52,11.947-73.387,0.853c-2.56-1.707-5.12-0.853-7.68,0.853     
                                                        C63.64,95,47.427,120.6,41.453,149.613v0.853c0,2.56,0.853,5.973,2.56,7.68c25.6,25.6,40.107,64,40.107,104.96     
                                                        c0,40.96-14.507,79.36-40.107,104.96c-2.56,1.707-3.413,5.12-2.56,7.68v0.853c5.973,29.013,22.187,54.613,46.08,72.533     
                                                        c2.56,1.707,5.12,2.56,7.68,1.707c5.12-1.707,112.64-38.4,151.893,54.613c0.853,3.413,4.267,5.12,7.68,5.12     
                                                        s6.827-1.707,7.68-5.973c40.107-92.16,147.627-56.32,151.893-54.613c2.56,1.707,5.12,0.853,7.68-0.853     
                                                        c23.893-17.92,40.107-43.52,46.08-72.533v-0.853c0-2.56-0.853-5.973-2.56-7.68C412.653,315.16,412.653,211.053,465.56,158.147z      
                                                        M415.213,432.067c-22.187-5.973-115.2-27.307-160.427,50.347c-27.307-46.08-71.68-57.173-107.52-57.173     
                                                        c-23.893,0-44.373,5.12-52.907,7.68c-17.067-14.507-29.867-34.133-34.987-56.32c26.453-28.16,41.813-69.12,41.813-113.493     
                                                        c0-44.373-15.36-85.333-41.813-114.347c5.12-22.187,17.92-41.813,34.987-56.32c33.28,11.093,61.44,10.24,83.627-3.413     
                                                        C207.853,70.253,217.24,34.413,219.8,20.76c23.04-7.68,46.933-7.68,69.973,0c2.56,13.653,11.947,49.493,41.813,68.267     
                                                        c22.187,13.653,50.347,14.507,83.627,3.413c17.92,14.507,29.867,34.133,34.987,56.32c-55.467,59.733-55.467,167.253,0,226.987     
                                                        C445.08,397.933,433.133,417.56,415.213,432.067z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>

                                        <!-- ΚΕΙΜΕΝΟ (οπτικά κεντραρισμένο) -->
                                        <text x="50%" y="40.5%" text-anchor="middle" class="logoAT" font-size="130">AT</text>
                                        <text x="50%" y="60.5%" text-anchor="middle" class="logoCollection" font-size="92">collection</text>
                                    </svg>
                                </div>
                            </div>

                            <!-- RIGHT: CONTACT DETAILS -->
                            <aside class="secondary-right" aria-label="Company contact details">
                                <h3 class="contact-title">Επικοινωνία</h3>

                                <ul class="contact-list">
                                    <li>
                                        <span class="contact-label">Εταιρία:</span>
                                        <span class="contact-value">ΑΦΟΙ ΤΣΑΠΡΟΥΝΗ Ο.Ε. (AT. COLLECTION)</span>
                                    </li>
                                    <li>
                                        <span class="contact-label">Τηλέφωνο:</span>
                                        <a class="contact-link" href="tel:+302103213394">+30 210 3213394</a>
                                    </li>
                                    <li>
                                        <span class="contact-label">Email:</span>
                                        <a class="contact-link" href="mailto:at.collection@hotmail.com">at.collection@hotmail.com</a>
                                    </li>
                                    <li>
                                        <span class="contact-label">Διεύθυνση:</span>
                                        <span class="contact-value">Αβραμιώτου 11, Μοναστηράκι, Αθήνα</span>
                                    </li>
                                    <li>
                                        <span class="contact-label">ΤΚ:</span>
                                        <span class="contact-value">10551</span>
                                    </li>
                                    <li>
                                        <span class="contact-label">Ώρες:</span>
                                        <span class="contact-value">
                                            Δευ–Παρ 09:00–17:00<br>
                                            Σάββατο 09:00–14:00
                                        </span>
                                    </li>
                                </ul>

                                <div class="contact-actions">
                                    <a class="contact-cta" href="mailto:at.collection@hotmail.com">Email</a>
                                    <a class="contact-cta" href="TEST2.php#form" target="_blank">Register / Form</a>
                                </div>
                            </aside>
                        </div>
                    </div>
                </section>
                <!--SECONDARY SECTION ENDS-->
            </main>
            <footer>
                <p>© 2025 AT.COLLECTION. All rights reserved.</p>
            </footer>
        </body>
    </html>

    
                           
                        
