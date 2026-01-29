<?php
// includes/footer.php
?>
</main>

<!-- REGISTER MODAL (global) -->
<div id="registerModal" class="modal-overlay" aria-hidden="true">
  <div class="modal-box" role="dialog" aria-modal="true" aria-label="Register form">
    <button type="button" class="modal-close" id="btnCloseRegister" aria-label="Close">×</button>

    <h2 class="modal-title">Register</h2>

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
        <div class="text-danger" id="charNum">Your comments must be no more than 400 characters.</div>
        <div class="char-remaining">Remaining characters: <strong id="remainingChars"></strong></div>
      </div>

      <fieldset class="form-group newsletter">
        <legend>Please sign up to our newsletter</legend>
        <p class="newsletter-text">
          <span style="color: rgb(255, 136, 0);">*</span>Sign up to our newsletter !
        </p>
        <div class="newsletter-options">
          <label><input type="radio" name="formNewsletter" value="yes"> <span>YES</span></label>
          <label><input type="radio" name="formNewsletter" value="no"> <span>NO</span></label>
        </div>
      </fieldset>

      <div class="form-group agreeTerms">
        <label for="agreeTerms"><span style="color: red;">*</span> I have read the terms of Service !</label>
        <input id="agreeTerms" name="agreeTerms" type="checkbox" required>
      </div>

      <div class="form-group action">
        <input type="submit" value="Submit" name="formAction" id="formAction">
      </div>
    </form>
  </div>
</div>

<footer>
    <p>© 2025 AT.COLLECTION. All rights reserved.</p>
</footer>

</body>
</html>