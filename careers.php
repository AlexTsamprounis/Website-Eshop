<?php
session_start();

$pageTitle = "Careers | AT.COLLECTION";
$loadCartJs = true;

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <section class="careers">
    <h1>Careers</h1>

    <p>
      Η AT.Collection είναι ένα εργαστήριο χειροποίητων δερμάτινων ειδών,
      δημιουργημένο από τεχνίτες με αγάπη για το δέρμα και τη λεπτομέρεια.
    </p>

    <p>
      Φτιάχνουμε δερμάτινες τσάντες με έμφαση στη διαχρονική ποιότητα
      και αναζητούμε ανθρώπους που μοιράζονται το ίδιο πάθος για τη χειροτεχνία
      και τη δημιουργία με ουσία.
    </p>

    <p class="careers-cta">
      Αν μοιράζεσαι το ίδιο πάθος με εμάς, στείλε μας το βιογραφικό σου πατώντας
      <a href="mailto:at.collection@hotmail.com">εδώ</a>.
    </p>
  </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>