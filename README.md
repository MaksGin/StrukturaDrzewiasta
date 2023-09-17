# StrukturaDrzewiasta

Moja Aplikacja przedstawia strukturę drzewiastą katalogów. Wykorzystałem podejście rekurencyjne do wyświetlania i usuwania katalogów.

![image](https://github.com/MaksGin/StrukturaDrzewiasta/assets/26302413/4b84c7da-ccd3-49fb-b3c2-c1429fd72035)


### Funkcje

- Struktura umożliwia działanie na nieograniczonej liczbie poziomów
- Funkcje dla użytkownika:
  - Dodawanie dla głównego katalogu (bez sciezki), dla podkatalogów wymagane jest podanie scieżki
  - Usuwanie - poprzez podwójne klikniecie na katalog
  - Edycja istniejącego katalogu (trzeba podać scieżke do katalogu który chcemy edytować)

    scieżka do aktualizacji podkatalogu Wakacje który znajduje sie w katalogu Zdjęcia wyglada tak:  /Zdjęcia/Wakacje
- Rozwinięcie całej struktury
- Walidacja
  - Nazwy katalogów nie mogą sie powtarzać w obrębie jednego głównego katalogu (rodzica)
  - Nazwy katalogów nie mogą zawierać znaków specjalnych
- Sortowanie - sortowanie głównych katalogów wraz z podkatalogami


### Instrukcja instalacji: 
1. Sklonuj repozytorium w swoim środowisku lokalnym
   
   Zacznij od sklonowania tego repozytorium na komputer lokalny za pomocą następującego polecenia:
   
```
$ git clone https://github.com/MaksGin/StrukturaDrzewiasta.git
$ cd folder-name
```

2. Zainstaluj zależności (użyj composera, aby zainstalować zależności php oraz zależności dla javascript)
   
```
composer install
npm install
```

3. Utwórz plik .env i skopiuj do niego całą zawartość pliku env.example

Zaktualizuj plik w odpowiednie ustawienia, takie jak dane do bazy danych, klucze API itp. 

4 .Wygeneruj klucz aplikacji:

```
php artisan key:generate
```

5. Uruchom migracje i dane początkowe: wykonaj migracje baz danych i zainicjuj przykładowe dane.

```
php artisan migrate
php artisan db:seed
```

6. Wystartuj serwer.
   
```
php artisan serve
npm run dev
```

7. Otwórz przeglądarke i pod adresem:
   http://localhost:8000 będziesz mógł korzystać z aplikacji.
