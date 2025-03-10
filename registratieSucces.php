<?php
// registratieSucces.php
include_once __DIR__ . '/incs/sessie.php';

$title = "Registratie Succesvol";
$menu = 'normaal';
include_once __DIR__ . '/incs/top.php';
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <style>
    /* Container met styling vergelijkbaar met het registratieformulier */
    .message-container {
      max-width: 600px;
      margin: 2rem auto;
      padding: 2rem;
      border: 1px dashed #ccc;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      text-align: center;
    }

    .message-container h2 {
      border-bottom: 2px dashed var(--groen);
      padding-bottom: 10px;
      margin-bottom: 20px;
      color: var(--groen);
    }

    .message-container p {
      font-size: 1em;
      margin: 10px 0;
    }

    .message-container a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: var(--groen);
      font-weight: bold;
    }
  </style>
</head>

<body>
  <?php include_once __DIR__ . '/incs/menu.php'; ?>
  <main>
    <div class="message-container">
      <h2>Registratie Succesvol</h2>
      <p>Er is een bevestigingsmail naar uw e-mailadres verzonden.</p>
      <p>Klik op de link in die mail om uw account te activeren.</p>
      <p>Mocht u nog vragen hebben, neem dan gerust <a href="contact.php">contact met ons op</a>.</p>
      <a href="index.php">Terug naar de homepage</a>
    </div>
  </main>
</body>

</html>