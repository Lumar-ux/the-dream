<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>TheDream</title>
</head>
<body>
  <main>
    <section>
      <h1>Convertisseur de devises</h1>
      <form method="POST" action="process.php">
        <?php 
          $lst_country=["NAD"=>"Dollar namibien","EUR"=>"Euro","USD"=>"Dollar américain","CAD"=>"Dollar canadien"];
        ?>
        <div class="area-form">
          <article class="form-groupe01">
            <label for="currency_source">Devise source:</label>
            <select name="currency_source" id="currency_source">
                <option value="" selected>Devise source</option>
                <?php 
                  foreach ($lst_country as $valeur => $libelle) {
                    echo "<option value='$valeur'>$libelle</option>";
                  }
                ?>
            </select>
          </article>
          <button id="exchange" name="exchange">
            <img src="asset/swap_horiz_32dp_000000_FILL0_wght400_GRAD0_opsz40.svg" alt="swap arrow">
          </button>
          <article class="form-groupe02">
            <label for="currency_target">Devise cible:</label>
            <select name="currency_target" id="currency_target" style="margin-bottom: 15px;">
              <option value="">Devise source</option>
              <?php 
                  foreach ($lst_country as $valeur => $libelle) {
                    echo "<option value='$valeur'>$libelle</option>";
                  }
                  $OptSelec = isset($_POST['currency_source']) ? $_POST['currency_source'] : null;
                  echo "<option value='$valeur' $OptSelec>$libelle</option>";
                ?>
            </select>
          </article>
          <article class="form-groupe03"> 
            <label for="amount">Montant:</label>
            <input type="number" id="amount" name="amount">
          </article>
        </div>
        <div class="grp-form-conversion">
          <button type="submit" name="submit" id="sub">Convertir</button>
          <?php
            
            if(isset($_POST['exchange'])){
              
            }
          ?>
          <article id="result">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
              $currency = filter_input(INPUT_POST, 'currency_source', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-Z]{3}$/")));
              $currency_dest = filter_input(INPUT_POST, 'currency_target', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-Z]{3}$/")));
              $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
              if (!$currency || !$currency_dest || !$amount) {
                    echo "<p>Erreur : Veuillez remplir tous les champs avec des valeurs valides.</p>";
                } else {
                  $curl = curl_init();

                  curl_setopt_array($curl, [
                    CURLOPT_URL => "https://currency-converter5.p.rapidapi.com/currency/convert?format=json&from=$currency&to=$currency_dest&amount=$amount&language=en",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => [
                      "x-rapidapi-host: currency-converter5.p.rapidapi.com",
                      "x-rapidapi-key: 4137d6301fmsh6ff4603ebb2129bp198c3fjsned2cb12ab807"
                    ],
                  ]);
                  
                  $response = curl_exec($curl);
                  $err = curl_error($curl);
                  
                  curl_close($curl);
                  
                  if ($err) {
                    echo "cURL Error #:" . $err;
                  } else {
                    $jsonData = json_decode($response, true);
                    $rate = $jsonData['rates'][$currency_dest]['rate_for_amount'];
                    $res= $rate." ".$currency_dest;
                    echo $res;
                  }
                }
            }
            ?>
          </article>
        </div>
      </form>
      
    </section>
  </main>
  <footer>
      Powered by <a href="https://www.amdoren.com">Amdoren</a>
  </footer>
</body>
</html>
