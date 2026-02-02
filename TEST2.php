<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$pageTitle = "AT.COLLECTION | Home";
$pageClass = "page-home";
$loadCartJs = true; // για να γράφει σωστά το cart count στο header
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero-slider" id="hero">
  <div class="hero-slide active" style="background-image:url('Assets/Images/Workshop.jpg')">
    <div class="hero-overlay">
      <h1>Χειμερινές Προσφορές</h1>
      <p>−20% σε επιλεγμένες δερμάτινες τσάντες</p>
      <div class="hero-actions">
        <a class="hero-btn" href="#best-sellers">Δες Best Sellers</a>
        <a class="hero-btn secondary" href="register.php">Γίνε μέλος</a>
      </div>
    </div>
  </div>

  <div class="hero-slide" style="background-image:url('Assets/Images/at_collection_2024/697.jpg')">
    <div class="hero-overlay">
      <h1>Premium Leather</h1>
      <p>Χειροποίητες τσάντες από το 1954</p>
      <div class="hero-actions">
        <a class="hero-btn" href="Backpacks.php">Backpacks</a>
        <a class="hero-btn secondary" href="CrossbodyBags.php">Crossbody</a>
      </div>
    </div>
  </div>

  <button class="hero-nav prev" type="button" data-hero-prev>‹</button>
  <button class="hero-nav next" type="button" data-hero-next>›</button>

  <div class="hero-dots">
  <button type="button" class="dot active" data-hero-dot data-hero-index="0"></button>
  <button type="button" class="dot" data-hero-dot data-hero-index="1"></button>
  </div>
</section>


<section class="welcome-strip">
  <div class="welcome-left">
    <h2>Καλώς ήρθατε στην AT.COLLECTION</h2>
    <p>Ανακαλύψτε δερμάτινες τσάντες με διαχρονικό design. Γίνετε μέλος για να ολοκληρώνετε αγορές & να βλέπετε ιστορικό παραγγελιών.</p>
  </div>
  <div class="welcome-right">
    <a class="welcome-cta" href="#best-sellers">Αγόρασε τώρα</a>
    <a class="welcome-cta secondary" href="register.php">Εγγραφή</a>
  </div>
</section>


<section class="best-sellers" id="best-sellers">
  <div class="best-sellers-header">
    <h2 class="best-sellers__title">Best Sellers!!</h2>
  </div>

  <div class="products-flex">
    <?php
    // Φέρνουμε Best Sellers από DB
    $sql = "SELECT * FROM products WHERE category = 'bestseller'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<p style='color:#ff6b6b;'>DB Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {

            $id    = (int)$row['id'];
            $name  = $row['name'] ?? '';
            $desc  = $row['description'] ?? '';
            $price = (float)($row['price'] ?? 0);
            $img   = $row['image_path'] ?? '';
            ?>
            
            <article class="product-card">
              <img
                class="product-card__img"
                src="<?= htmlspecialchars($img) ?>"
                alt="<?= htmlspecialchars($name) ?>"
              >

              <div class="product-card__body">
                <h3 class="product-card__name">
                  <?= htmlspecialchars($name) ?>
                </h3>

                <p class="product-card__desc">
                  <?= htmlspecialchars($desc) ?>
                </p>

                <div class="product-card__footer">
                  <span class="product-card__price">
                    <?= number_format($price, 2) ?> €
                  </span>

                  <button
                      type="button"
                      class="add-to-cart-btn"
                      data-add-to-cart
                      data-product-id="<?= (int)$id ?>">
                      Add to Cart
                    </button>
                </div>
              </div>
            </article>

            <?php
        } // end while
    } // end else
    ?>
  </div>
</section>


<section id="main-section">
    <div class="container">
        <h1>Λίγα λόγια για την εταιρία!</h1>

	        <section class="AT-introduction">
	            <div class="AT-introduction__media">
                <img src="Assets/Images/Workshop.jpg" alt="AT COLLECTION Workshop" width="100%">
            </div>

	            <div class="AT-introduction__content">
                <h2>AT. COLLECTION - ΑΦΟΙ ΤΣΑΠΡΟΥΝΗ Ο.Ε</h2>
                <p>Η εταιρία μας δραστηριοποιείται στον χώρο της παραγωγής και εμπορίας δερμάτινων ειδών από το 1956, όταν ιδρύθηκε από τον Αλέξανδρο Τσαμπρούνη.</p>
                <p>Από τα πρώτα της βήματα έως και σήμερα, παραμένει πιστή στις αξίες της ποιότητας, της αξιοπιστίας και της προσοχής στη λεπτομέρεια.</p>
                <p>Τα προϊόντα μας συνδυάζουν διαχρονικό σχεδιασμό, ανθεκτικά υλικά και σύγχρονη αισθητική, καλύπτοντας τις απαιτήσεις της καθημερινής χρήσης.</p>
                <p>Μέσα από το ηλεκτρονικό μας κατάστημα, προσφέρουμε ασφαλείς αγορές και εύκολη περιήγηση στις συλλογές μας.</p>
                <a class="intro-cta" href="#best-sellers">Best Sellers</a>
            </div>
        </section>

        <section class="hero">
            <div class="multimedia">
                <div class="multimedia video">
                    <h4>Σύντομο video !!</h4>
                    <video controls width="450" height="auto">
                        <source src="Assets/Video/PixVerse_V5.5_Image_Text_360P.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </section>
    </div>
</section>

<section id="secondary-section">
    <div class="menu secondary-menu">
        <h2>A.T COLLECTION</h2>

        <div class="secondary-grid">
            <aside class="secondary-right">
                <h3 class="contact-title">Επικοινωνία</h3>
                <ul class="contact-list">
                    <li><span class="contact-label">Εταιρία:</span> ΑΦΟΙ ΤΣΑΠΡΟΥΝΗ Ο.Ε.</li>
                    <li><span class="contact-label">Τηλέφωνο:</span> <a class="contact-link" href="tel:+302103213394">+30 210 3213394</a></li>
                    <li><span class="contact-label">Email:</span> <a class="contact-link" href="mailto:at.collection@hotmail.com">at.collection@hotmail.com</a></li>
                    <li><span class="contact-label">Διεύθυνση:</span> Αβραμιώτου 11, Μοναστηράκι</li>
                    <li><span class="contact-label">ΤΚ:</span><span class="contact-value">10551</span></li>
                    <li><span class="contact-label">Ώρες:</span><span class="contact-value">Δευ–Παρ 09:00–17:00<br>Σάββατο 09:00–14:00</span></li>
                </ul>

	                <div class="contact-actions">
	                    <a class="contact-cta" href="mailto:at.collection@hotmail.com">Email</a>
	                    <a class="contact-cta" href="register.php">Register</a>
	                </div>
            </aside>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>