# Skrypt do przesyłania rozwiązań zadań

To repozytorium zawiera pliki źródłowe prostego skryptu umożliwiającego przesyłanie przez studentów rozwiązań zadań domowych, projektów, kolokwiów praktycznych itp. Skrypt nie odwołuje się do żadnych plików umieszczonych na zewnętrznych serwerach, dlatego może być z powodzeniem stosowany w zamkniętych sieciach o ograniczonym dostępie do internetu.

## Instalacja

Umieść katalog `dist` w odpowiednim miejscu struktury plików na serwerze WWW (do prawidłowego działania wymagane jest wsparcie interpretera PHP w wersji co najmniej 5.3.0). Nazwę tego katalogu moższ zmienić dowolnie według upodobania.

## Konfiguracja

Plik `config.php` definiuje parametry działania skryptu. Obejmują one wartości takie jak: termin przesyłania rozwiązań, liczba ich dopuszczalnych wersji, ścieżka do pliku z listą numerów albumów, ścieżka do katalogu, w którym mają być umieszczane przesłane pliki itp.

*Uwaga.* Upewnij się, że oprogramowanie serwera WWW ma prawo zapisywać pliki we wskazanym katalogu docelowym (domyślnie `./upload`).
