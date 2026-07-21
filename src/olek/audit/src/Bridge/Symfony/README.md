# Bridge Symfony (`Olek\Audit\Bridge\Symfony`)

Integracja pakietu [`olek/audit`](../../../README.md) z Symfony: bundle, wiring DI, konfiguracja Messengera i Doctrine.

## Instalacja

1. Doinstaluj pakiety wymagane przez ten bridge — patrz sekcja `suggest` w głównym `composer.json` pakietu (`symfony/dependency-injection`, `symfony/http-kernel`, `symfony/messenger`, `symfony/uid`, `symfony/yaml`). W typowej aplikacji Symfony (z `framework-bundle`, `messenger`, `uid`) już je masz.
2. Zarejestruj bundle w `config/bundles.php`:
   ```php
   Olek\Audit\Bridge\Symfony\AuditBundle::class => ['all' => true],
   ```
3. **Wygeneruj i uruchom migrację dla tabeli `audits`** (encja `Entity\Audit` jest mapowana automatycznie przez bundle, ale tabela nie istnieje, dopóki jej nie zmigrujesz):
   ```bash
   php bin/console doctrine:migrations:diff
   php bin/console doctrine:migrations:migrate
   ```
   > **Bez tego kroku** oznaczenie pierwszej encji `#[Auditable]` i jej zapis zakończy się błędem bazy danych (`relation "audits" does not exist`) — tabeli po prostu jeszcze nie ma.

## Co rejestruje bundle

- **Doctrine**: mapowanie `Olek\Audit\Entity` (`prependExtension` → `doctrine.orm.mappings`) oraz rejestracja własnego typu DBAL `audit_id` → `Olek\Audit\Doctrine\AuditIdType` (`doctrine.dbal.types`), którym zmapowana jest kolumna `Entity\Audit::$id`.
- **Messenger**: transporty `audit.sync` (`sync://`) i `audit.async` (Redis, `%env(REDIS_URL)%`, stream `messages`) oraz domyślny routing `Olek\Audit\DTO\AuditPayload` → `audit.sync`.
- **Usługi** (`Resources/config/services.yaml`):
  - `Identifier\AuditIdGeneratorInterface` → `SymfonyUidAuditIdGenerator` (UUID v1 z `symfony/uid`, time-ordered — zamiast uniwersalnego `Identifier\NativeAuditIdGenerator` z rdzenia, który jest domyślny tylko poza Symfony)
  - `Dispatcher\AuditPayloadDispatcherInterface` → `MessengerAuditPayloadDispatcher` (owija `MessageBusInterface`)
  - `Psr\SimpleCache\CacheInterface` → `FilesystemMetadataCache` (`%kernel.cache_dir%/olek/audit/metadata`)
  - `AuditListener` z tagami `doctrine.event_listener` (`onFlush`, `postFlush`)
  - `AuditPayloadHandler` z tagiem `messenger.message_handler` (metoda `process`)

## Sync vs async

Domyślnie audyt jest przetwarzany synchronicznie, w tym samym request (`audit.sync` = `sync://`). Żeby przełączyć na przetwarzanie asynchroniczne (wymaga działającego workera Messengera konsumującego transport `audit.async`), nadpisz routing w głównym configu aplikacji, np. `config/packages/messenger.yaml`:

```yaml
framework:
    messenger:
        routing:
            'Olek\Audit\DTO\AuditPayload': audit.async
```

Bez tego nadpisania wszystko działa synchronicznie od razu, bez potrzeby uruchamiania workera.
