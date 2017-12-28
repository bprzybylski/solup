<?php $_CONFIG = require("config.php"); ?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $_CONFIG['title']; ?></title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <link rel="stylesheet" href="css/main.css" type="text/css" media="screen">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.countdown.min.js"></script>
    <link href="css/fonts.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/html5.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#clock').countdown('<?php echo date("Y/m/d H:i:s", strtotime($_CONFIG['deadline'])); ?>', function(event) {
          $(this).html(event.offset.totalDays + event.strftime(' dni, %H godzin(y), %M minut(y) i %S sekund(y)'));
        });
      });
    </script>
</head>
<body>
    <form id="uploader" action="#" method="post" enctype="multipart/form-data">
        <h1><?php echo $_CONFIG['title']; ?></h1>
        <h2>Możesz jeszcze wysłać swoje rozwiązanie przez<br /><span id="clock"></span></h2>
        <p>Wypełnij odpowiednie pola i zatwierdź wysyłkę (dopuszczalne typy plików: <?php echo implode(array_keys($_CONFIG['acceptedFiles']), ", "); ?>). Maksymalny rozmiar pliku, który możesz wysłać, to <?php echo $_CONFIG['maxFileSize']; ?>MB. Możesz wysłać swoje rozwiązanie maksymalnie <?php echo $_CONFIG['uploadsAllowed']; ?> razy, ale musisz zmieścić się w zadanym czasie.</p>
        <?php
          if(isset($_POST['uploader'])) {
            try {
              if(time() > strtotime($_CONFIG['deadline']) + $_CONFIG['tolerance']){
                throw new RuntimeException('Minął już termin przesyłania rozwiązań.');
              }

              $file = $_FILES['file'];

              switch ($file['error']) {
                  case UPLOAD_ERR_OK:
                      break;
                  case UPLOAD_ERR_NO_FILE:
                      throw new RuntimeException('Nie wysłano pliku.');
                  case UPLOAD_ERR_INI_SIZE:
                  case UPLOAD_ERR_FORM_SIZE:
                      throw new RuntimeException('Przesłany plik jest za duży.');
                  default:
                      throw new RuntimeException('Wystąpił nieznany błąd.');
              }

              // Sprawdź, czy plik nie jest za duży
              if(!$file['size'] > $_CONFIG['maxFileSize']*1024*1024){
                throw new RuntimeException('Przesłany plik jest za duży.');
              }

              // Sprawdź, czy przesłany plik to pdf
              $finfo = new finfo(FILEINFO_MIME_TYPE);

              $ext = null;
              if (false === $ext = array_search($finfo->file($file['tmp_name']), $_CONFIG['acceptedFiles'], true)) {
                    throw new RuntimeException('Należy przesłać plik z jednym z rozszerzeń: '. implode(array_keys($_CONFIG['acceptedFiles']), ", ") . '.');
              }

              // Sprawdź, czy dany student może wysłać plik
              $albums = explode("\n", file_get_contents($_CONFIG['albumsFile']));
              if($_POST['album'] == '' || !in_array($_POST['album'], $albums)) {
                throw new RuntimeException('Wskazanego numeru indeksu nie ma w bazie studentów.');
              }

              // Sprawdź, czy istnieją wszystkie poprzednie wersje projektów
              // i czy student nie próbuje wysłać drugi raz takiego samego.
              // Pliki mają nazwy INDEKS_WERSJA_DATA.EXT
              $list = glob($_CONFIG['uploadDirectory'].'/'.(int)$_POST['album'].'_*');
              // Poniższy if zakłada, że nikt nie manipulował przy plikach po stronie serwera.
              // Szacuje on numer wersji na podstawie liczby plików, które zostały dotąd przesłane.
              if($_POST['version'] != count($list) + 1){
                if(count($list) >= $_CONFIG['uploadsAllowed'])
                  throw new RuntimeException('Przesłałaś/eś już maksymalną dopuszczalną liczbę wersji swojego rozwiązania. Więcej nie można.');
                throw new RuntimeException('Przesłałaś/eś już '.count($list).' wersję/i, a zatem bieżąca wersja powinna mieć numer '.(count($list) + 1).'.');
              }

              // Jeśli wszystko jest okej, przenieś plik
              $nfilename = sprintf('%s_%s_%s.'.$ext, $_POST['album'], count($list) + 1, date('YmdHis'));
              if (!move_uploaded_file($file['tmp_name'], $_CONFIG['uploadDirectory'].'/'.$nfilename)) {
                throw new RuntimeException('Nie udało się wgrać pliku.');
              }

              echo '<p class="alert green">Dziękuję za przesłanie pliku. Jego nazwa w systemie to <code>'.$nfilename.'</code></p>';

            } catch (RuntimeException $e) {
                echo '<p class="alert red">'.$e->getMessage().'</p>';
            }
          }
        ?>
        <p class="textinput"><label for="file"><span>Plik</span> <input type="file" name="file" accept=".<?php echo implode(array_keys($_CONFIG['acceptedFiles']), ",."); ?>" /></label></p>
        <p class="textinput"><label for="album"><span>Numer indeksu</span> <input type="text" name="album" maxlength="6" /></label></p>
        <p class="textinput"><label for="version"><span>Numer wersji</span> <select name="version">
          <?php for($i = 1; $i < $_CONFIG['uploadsAllowed']; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
          <option value="<?php echo $_CONFIG['uploadsAllowed']; ?>">∞</option></select></p>
        <p class="buttons"><input type="submit" class="button gold" name="uploader" value="Wyślij na serwer" /></p>
    </form>
</body>
</html>
