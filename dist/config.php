<?php

return array(
  // Tytuł strony
  "title" => "Skrypt do przesyłania rozwiązań zadań",
  // Termin przesyłania rozwiązań (w formacie RRRR-MM-DD GG:MM:SS)
  "deadline" => "2017-12-29 22:02:00",
  // Liczba dopuszczalnych prób
  "uploadsAllowed" => 5,
  // Dopuszczalne spóźnienie po stronie serwera (w sekundach)
  "tolerance" => 5,
  // Maksymalny rozmiar pliku (w MB)
  "maxFileSize" => 5,
  // Ścieżka do pliku z listą dopuszczalnych numerów albumów
  "albumsFile" => "albums.txt",
  // Ścieżka do katalogu, w którym mają być umieszczane rozwiązania
  "uploadDirectory" => "./upload",
  // Akceptowalne typy plików (rozszerzenie => mime)
  "acceptedFiles" => array(
    'zip' => 'application/zip',
    'pdf' => 'application/pdf'
  )
)

?>
