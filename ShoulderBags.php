<?php
session_start();
require_once __DIR__ . '/db_connect.php';

// Page meta (ΠΡΙΝ το header)
$pageTitle  = "AT.COLLECTION | Shoulder Bags";
$pageClass  = "page-category page-shoulder";
$loadCartJs = true; // ✅ για cart count + add-to-cart

require_once __DIR__ . '/includes/header.php';
?>

<section class="best-sellers" id="category">
  <div class="best-sellers__header">
    <h2 class="best-sellers__title">Shoulder Bags Collection!!</h2>
  </div>

  <div class="products-flex">
    <?php
    $query  = "SELECT id, name, description, Description, price, image_path FROM products WHERE category = 'shoulder'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
      echo "<p style='color:#ff6b6b;'>DB Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    } elseif (mysqli_num_rows($result) === 0) {
      echo "<p style='color:white;'>Δεν βρέθηκαν Shoulder Bags στη βάση δεδομένων.</p>";
    } else {
      while ($row = mysqli_fetch_assoc($result)) {
        $id    = (int)($row['id'] ?? 0);
        $name  = (string)($row['name'] ?? '');

        // ✅ fallback: κάποιοι πίνακες έχουν description ή Description
        $desc  = (string)($row['description'] ?? ($row['Description'] ?? ''));

        $price = (float)($row['price'] ?? 0);
        $img   = (string)($row['image_path'] ?? '');
        ?>

        <article class="product-card">
          <img
            class="product-card__img"
            src="<?php echo htmlspecialchars($img); ?>"
            alt="<?php echo htmlspecialchars($name); ?>"
          >

          <div class="product-card__body">
            <h3 class="product-card__name"><?php echo htmlspecialchars($name); ?></h3>
            <p class="product-card__desc"><?php echo htmlspecialchars($desc); ?></p>

            <div class="product-card__footer">
              <span class="product-card__price"><?php echo number_format($price, 2); ?> €</span>

              <!-- ✅ NEW: no inline onclick, ID-based -->
              <button
                type="button"
                class="add-to-cart-btn"
                data-add-to-cart
                data-product-id="<?php echo (int)$id; ?>">
                Add to Cart
              </button>
            </div>
          </div>
        </article>

        <?php
      }
    }
    ?>
  </div>
</section>

<!-- MAIN SECTION -->
<section id="main-section">
  <div class="container">
    <h1>Λίγα λόγια για την εταιρία!</h1>

    <section class="AT-introduction">
      <div class="AT-introduction_media">
        <img src="Assets/Images/Workshop.jpg" alt="AT COLLECTION Workshop" width="100%">
      </div>

      <div class="AT-introduction_content">
        <h2>AT. COLLECTION - ΑΦΟΙ ΤΣΑΠΡΟΥΝΗ Ο.Ε</h2>

        <p>Η εταιρία μας δραστηριοποιείται στον χώρο της παραγωγής και εμπορίας δερμάτινων ειδών από το 1956, όταν ιδρύθηκε από τον Αλέξανδρο Τσαμπρούνη.</p>
        <p>Από τα πρώτα της βήματα έως και σήμερα, παραμένει πιστή στις αξίες της ποιότητας, της αξιοπιστίας και της προσοχής στη λεπτομέρεια.</p>
        <p>Τα προϊόντα μας συνδυάζουν διαχρονικό σχεδιασμό, ανθεκτικά υλικά και σύγχρονη αισθητική, καλύπτοντας τις απαιτήσεις της καθημερινής χρήσης.</p>
        <p>Μέσα από το ηλεκτρονικό μας κατάστημα, προσφέρουμε ασφαλείς αγορές και εύκολη περιήγηση στις συλλογές μας.</p>

        <a class="intro-cta" href="index.php#best-sellers" target="_blank" rel="noopener">Best Sellers</a>
      </div>
    </section>

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
  </div>
</section>

<!-- SECONDARY SECTION -->
<section id="secondary-section">
  <div class="menu secondary-menu">
    <h2>A.T COLLECTION</h2>

    <div class="secondary-grid">
      <aside class="secondary-right" aria-label="Company contact details">
        <h3 class="contact-title">Επικοινωνία</h3>

        <ul class="contact-list">
          <li><span class="contact-label">Εταιρία:</span> <span class="contact-value">ΑΦΟΙ ΤΣΑΠΡΟΥΝΗ Ο.Ε. (AT. COLLECTION)</span></li>
          <li><span class="contact-label">Τηλέφωνο:</span> <a class="contact-link" href="tel:+302103213394">+30 210 3213394</a></li>
          <li><span class="contact-label">Email:</span> <a class="contact-link" href="mailto:at.collection@hotmail.com">at.collection@hotmail.com</a></li>
          <li><span class="contact-label">Διεύθυνση:</span> <span class="contact-value">Αβραμιώτου 11, Μοναστηράκι, Αθήνα</span></li>
          <li><span class="contact-label">ΤΚ:</span> <span class="contact-value">10551</span></li>
          <li><span class="contact-label">Ώρες:</span> <span class="contact-value">Δευ–Παρ 09:00–17:00<br>Σάββατο 09:00–14:00</span></li>
        </ul>

        <div class="contact-actions">
          <a class="contact-cta" href="mailto:at.collection@hotmail.com">Email</a>
          <a class="contact-cta" href="#" data-open="register">Register / Form</a>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>