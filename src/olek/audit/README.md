# olek/audit

Audyt zmian encji Doctrine — dla encji oznaczonych `#[Auditable]` zapisuje historię `update`/`delete` (diff pól i relacji) jako osobne encje `Audit`.

## Architektura

Pakiet jest podzielony na dwie warstwy:

- **Rdzeń** (`Olek\Audit\...`) — bez zależności od jakiegokolwiek frameworka poza Doctrine ORM/Persistence i PSR-16 (`psr/simple-cache`). Da się go użyć w dowolnym środowisku PHP.
- **Bridge** (`Olek\Audit\Bridge\<Framework>\...`) — integracja z konkretnym środowiskiem: DI/wiring, konfiguracja, konkretne implementacje interfejsów z rdzenia. Dostępne:
  - [`Bridge\NativePhp`](src/Bridge/NativePhp/README.md) — **domyślny**: plain PHP, bez kontenera DI, samymi uniwersalnymi implementacjami z rdzenia.
  - [`Bridge\Symfony`](src/Bridge/Symfony/README.md) — integracja z Symfony (bundle, kontener DI, Messenger).

## Przepływ

1. `Listener\AuditListener::onFlush` — dla zaplanowanych update'ów/usunięć w `UnitOfWork` woła `Factory\AuditPayloadFactory` (pomija encje bez `#[Auditable]` i te bez realnych zmian).
2. `AuditPayloadFactory` normalizuje diff: proste pola bez zmian, relacje *ToOne/*ToMany sprowadzone do samych identyfikatorów (nigdy surowych obiektów/proxy) — wynik to `DTO\AuditPayload` z listą `DTO\EntityPropertyDiff`.
3. `AuditListener::postFlush` (czyli już poza aktywnym flushem) przekazuje payload przez `Dispatcher\AuditPayloadDispatcherInterface`.
4. `Handler\AuditPayloadHandler` (wywoływany przez dowolny mechanizm dispatcha) tworzy encję `Entity\Audit` przez `Factory\AuditFactory` i zapisuje ją.

## Oznaczanie encji do audytu

```php
use Olek\Audit\Attribute\Auditable;
use Olek\Audit\Attribute\Ignore;

#[Auditable]
class MyEntity
{
    #[Ignore]
    private string $polePomijaneWAudycie;
}
```

## Punkty rozszerzeń (do zaimplementowania/podmiany przy nowym bridge'u)

| Interfejs | Rola | `Bridge\NativePhp` (domyślny) | `Bridge\Symfony` |
|---|---|---|---|
| `Identifier\AuditIdGeneratorInterface` | generowanie ID encji `Audit` (`Entity\Audit::$id`, typu `Identifier\AuditIdInterface`) | `Identifier\NativeAuditIdGenerator` (UUID v4 z `random_bytes()`, zero zależności) | `Bridge\Symfony\Identifier\SymfonyUidAuditIdGenerator` (UUID v1 z `symfony/uid`, time-ordered) |
| `Dispatcher\AuditPayloadDispatcherInterface` | sposób przekazania payloadu do handlera | `Dispatcher\InlineAuditPayloadDispatcher` (wywołanie w tym samym procesie, bez kolejki) | `Bridge\Symfony\Dispatcher\MessengerAuditPayloadDispatcher` (Messenger, sync lub async wg routingu) |
| `Psr\SimpleCache\CacheInterface` | cache metadanych `#[Auditable]`/`#[Ignore]` per klasa | `Cache\FilesystemMetadataCache` | (ta sama implementacja z rdzenia) |

`Bridge\NativePhp` używa wyłącznie uniwersalnych implementacji z rdzenia — jest referencyjnym, domyślnym bridge'em. Każdy kolejny bridge (jak `Bridge\Symfony`) podmienia tylko to, co jego framework robi lepiej — dla Symfony jest to identyfikator (UUID v1 z `symfony/uid` zamiast v4) i dispatcher (kolejkowanie przez Messenger zamiast wywołania in-process).

`Entity\Audit::$id` jest typu `Identifier\AuditIdInterface`, nie `string` — mapowane przez własny typ Doctrine DBAL `Doctrine\AuditIdType` (kolumna `uuid`/GUID w bazie). Przy odczycie z bazy zawsze trafia do niego jako `Identifier\NativeAuditId` (tylko `__toString()` ma znaczenie, więc nie ma potrzeby pamiętać, który generator faktycznie stworzył dany wiersz).

## Zależności opcjonalne

Pakiety wymagane tylko przez konkretne bridge'e są w sekcji `suggest`, nie w `require` — rdzeń nie narzuca żadnego frameworka.

## Zanim zaczniesz audytować encje

Encja `Entity\Audit` wymaga własnej tabeli w bazie (`audits`). Instalacja bridge'a **nie tworzy jej automatycznie** — trzeba raz wygenerować i uruchomić migrację (patrz sekcja "Instalacja" w README konkretnego bridge'a, np. [`Bridge\Symfony`](src/Bridge/Symfony/README.md#instalacja)). Bez tego pierwszy zapis audytu skończy się błędem bazy danych (brakująca tabela).
