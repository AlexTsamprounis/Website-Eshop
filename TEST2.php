<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
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
        <a class="hero-btn secondary" href="#form">Γινε μελος</a>
      </div>
    </div>
  </div>

  <div class="hero-slide" style="background-image:url('Assets/Images/at collecton 2024/697.jpg')">
    <div class="hero-overlay">
      <h1>Premium Leather</h1>
      <p>Χειροποίητες τσάντες από το 1954</p>
      <div class="hero-actions">
        <a class="hero-btn" href="Backpacks.php">Backpacks</a>
        <a class="hero-btn secondary" href="CrossbodyBags.php">Crossbody</a>
      </div>
    </div>
  </div>

  <button class="hero-nav prev" type="button" onclick="heroPrev()">‹</button>
  <button class="hero-nav next" type="button" onclick="heroNext()">›</button>

  <div class="hero-dots">
    <button type="button" class="dot active" onclick="heroGo(0)"></button>
    <button type="button" class="dot" onclick="heroGo(1)"></button>
  </div>
</section>


<section class="welcome-strip">
  <div class="welcome-left">
    <h2>Καλώς ήρθατε στην AT.COLLECTION</h2>
    <p>Ανακαλύψτε δερμάτινες τσάντες με διαχρονικό design. Γίνετε μέλος για να ολοκληρώνετε αγορές & να βλέπετε ιστορικό παραγγελιών.</p>
  </div>
  <div class="welcome-right">
    <a class="welcome-cta" href="#best-sellers">Αγόρασε τώρα</a>
    <a class="welcome-cta secondary" href="#" data-open="register">Εγγραφή</a>
  </div>
</section>









<section class="best-sellers" id="best-sellers">
    <div class="best-sellers__header">
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
            $total_products = mysqli_num_rows($result);
            $product_index = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                $product_index++;

                $name = $row['name'] ?? '';
                $desc = $row['Description'] ?? ($row['description'] ?? '');
                $price = (float)($row['price'] ?? 0);
                $img = $row['image_path'] ?? '';

                // (Προαιρετικό) ειδική λογική τελευταίας σειράς όπως είχες
                if ($product_index === ($total_products - 1)) {
                    echo '<div class="last-row-wrapper">';

                    // προτελευταίο
                    ?>
                    <article class="product-card">
                        <img class="product-card__img" src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($name); ?>">
                        <div class="product-card__body">
                            <h3 class="product-card__name"><?php echo htmlspecialchars($name); ?></h3>
                            <p class="product-card__desc"><?php echo htmlspecialchars($desc); ?></p>
                            <p class="product-card__price"><?php echo number_format($price, 2); ?> €</p>

                            <button onclick='addToCart(<?php echo json_encode($name); ?>, <?php echo $price; ?>)'
                                    class="auth-button"
                                    style="width:100%; cursor:pointer; margin-bottom:10px; background-color:#ff9d00; border:none; padding:10px;">
                                Add to Cart
                            </button>
                        </div>
                    </article>
                    <?php

                    // τελευταίο προϊόν
                    $last = mysqli_fetch_assoc($result);
                    if ($last) {
                        $lastName = $last['name'] ?? '';
                        $lastDesc = $last['Description'] ?? ($last['description'] ?? '');
                        $lastPrice = (float)($last['price'] ?? 0);
                        $lastImg = $last['image_path'] ?? '';
                        ?>
                        <article class="product-card">
                            <img class="product-card__img" src="<?php echo htmlspecialchars($lastImg); ?>" alt="<?php echo htmlspecialchars($lastName); ?>">
                            <div class="product-card__body">
                                <h3 class="product-card__name"><?php echo htmlspecialchars($lastName); ?></h3>
                                <p class="product-card__desc"><?php echo htmlspecialchars($lastDesc); ?></p>
                                <p class="product-card__price"><?php echo number_format($lastPrice, 2); ?> €</p>

                                <button onclick='addToCart(<?php echo json_encode($name); ?>, <?php echo $price; ?>)'
                                        class="auth-button"
                                        style="width:100%; cursor:pointer; margin-bottom:10px; background-color:#ff9d00; border:none; padding:10px;">
                                    Add to Cart
                                </button>
                            </div>
                        </article>
                        <?php
                    }

                    echo '</div>';
                    break; // τελειώσαμε με τα 2 τελευταία
                }

                // κανονικό output
                ?>
                <article class="product-card">
                    <img class="product-card__img" src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($name); ?>">

                    <div class="product-card__body">
                        <h3 class="product-card__name"><?php echo htmlspecialchars($name); ?></h3>
                        <p class="product-card__desc"><?php echo htmlspecialchars($desc); ?></p>
                        <p class="product-card__price"><?php echo number_format($price, 2); ?> €</p>

                        <button onclick='addToCart(<?php echo json_encode($name); ?>, <?php echo $price; ?>)'
                                class="auth-button"
                                style="width:100%; cursor:pointer; margin-bottom:10px; background-color:#ff9d00; border:none; padding:10px;">
                            Add to Cart
                        </button>
                    </div>
                </article>
                <?php
            } // end while
        } // end else
        ?>
    </div>
</section>

        <!-- REGISTER MODAL -->
<div id="registerModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-label="Register form">
        <button type="button" class="modal-close" id="btnCloseRegister" aria-label="Close">×</button>

        <h2 class="modal-title">Register</h2>

        <!-- ✅ Το ίδιο form που είχες, απλά τώρα είναι μέσα στο modal -->
        <form method="post" action="register.php">
        <fieldset class="introduction" id="introduction">
            <legend class="personal-data">Personal Details</legend>

            <div class="form-group firstname">
            <label for="firstname"><span style="color: red;">*</span> First Name:</label>
            <input value="" type="text" name="firstname" id="firstname" placeholder="John" required>
            </div>

            <div class="form-group lastname">
            <label for="lastname"><span style="color: red;">*</span> Last Name:</label>
            <input type="text" name="lastname" id="lastname" placeholder="Doe" required>
            </div>
        </fieldset>

        <div class="form-group gender">
            <label for="formGender">* Gender:</label>
            <select id="formGender" name="formGender" required>
            <option value="null" selected>Choose your gender !</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            </select>
        </div>

        <div class="form-group e-mail">
            <label for="emailAdress"><span style="color: red;">*</span> E-mail:</label>
            <input type="email" name="emailAdress" id="emailAdress" placeholder="example@gmail.com" required>
        </div>

        <div class="form-group password">
            <label for="formPassword"><span style="color: red;">*</span> Password:</label>
            <input type="password" name="formPassword" id="formPassword" required>
        </div>

        <div class="form-group comments">
            <div>
            <label for="formComments">* Comments</label>
            </div>
            <textarea name="formComments" maxlength="400" id="formComments" placeholder="Add your comments here !!"></textarea>
            <div class="text-danger" id="charNum">
            Your comments must be no more than 400 characters.
            </div>
            <div class="char-remaining">
            Remaining characters: <strong id="remainingChars"></strong>
            </div>
        </div>

        <fieldset class="form-group newsletter">
            <legend>Please sign up to our newsletter</legend>
            <p class="newsletter-text">
            <span style="color: rgb(255, 136, 0);">*</span>Sign up to our newsletter !
            </p>
            <div class="newsletter-options">
            <label>
                <input type="radio" name="formNewsletter" value="yes"> <span>YES</span>
            </label>
            <label>
                <input type="radio" name="formNewsletter" value="no"><span>NO</span>
            </label>
            </div>
        </fieldset>

        <div class="form-group agreeTerms">
            <label for="agreeTerms"><span style="color: red;">*</span> I have read the terms of Service !</label>
            <input id="agreeTerms" name="agreeTerms" type="checkbox" required>
        </div>

        <div class="form-group action">
            <input type="submit" value="Register" name="formAction" id="formAction">
        </div>
        </form>
    </div>
</div>

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
                    <a class="contact-cta" href="#" data-open="register">Register / Form</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>