# Bridge NativePhp (`Olek\Audit\Bridge\NativePhp`)

Integracja pakietu [`olek/audit`](../../../README.md) bez żadnego frameworka i bez kontenera DI — plain PHP + Doctrine ORM.

To jest **domyślny** bridge: nie zamienia niczego, po prostu ręcznie składa wszystkie uniwersalne implementacje z rdzenia (`Identifier\NativeAuditIdGenerator`, `Cache\FilesystemMetadataCache`, `Dispatcher\InlineAuditPayloadDispatcher`) i podpina `Listener\AuditListener` do Twojego `EntityManagera`. Inne bridge'e (np. [`Bridge\Symfony`](../Symfony/README.md)) różnią się od tego domyślnego zestawu tylko tam, gdzie ich framework robi coś lepiej.

## Instalacja

1. Zarejestruj własny typ Doctrine DBAL — **przed** utworzeniem `EntityManagera`:
   ```php
   use Doctrine\DBAL\Types\Type;
   use Olek\Audit\Doctrine\AuditIdType;

   Type::addType(AuditIdType::NAME, AuditIdType::class);
   ```
2. Dodaj katalog `Entity/` pakietu do swoich ścieżek mapowania Doctrine (obok własnych encji), np. przy `ORMSetup::createAttributeMetadataConfiguration()`:
   ```php
   $config = ORMSetup::createAttributeMetadataConfiguration(
       paths: [
           __DIR__ . '/src/Entity', // Twoje encje
           __DIR__ . '/vendor/olek/audit/src/Entity', // encja Audit
       ],
       isDevMode: true,
   );
   ```
3. Podepnij `AuditListener` do `EntityManagera`:
   ```php
   use Olek\Audit\Bridge\NativePhp\AuditKit;

   AuditKit::register($entityManager, __DIR__ . '/var/cache');
   ```
4. **Wygeneruj i uruchom migrację dla tabeli `audits`** (np. `vendor/bin/doctrine-migrations diff` + `migrate`, zależnie od tego, jak masz skonfigurowane migracje) — bez tego pierwszy zapis audytu skończy się błędem bazy danych (brakująca tabela).

## Odczyt historii

Bez kontenera DI po prostu tworzysz `AuditRepository` sam:

```php
use Olek\Audit\Repository\AuditRepository;

$history = (new AuditRepository($entityManager))->findHistoryFor($produkt);
```

## Sync vs async

`AuditKit::register()` wpina `Dispatcher\InlineAuditPayloadDispatcher` — audyt jest zawsze przetwarzany synchronicznie, w tym samym procesie, od razu po flushu. Bez frameworka nie ma tu odpowiednika kolejki/workera "za darmo" — jeśli potrzebujesz przetwarzania asynchronicznego, musisz sam dostarczyć implementację `Dispatcher\AuditPayloadDispatcherInterface` (np. wrzucającą payload do własnej kolejki) i użyć jej zamiast tego, co składa `AuditKit`.
