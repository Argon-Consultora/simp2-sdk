# SIMP2 SDK

## Instalación

```shell
composer require simp2/sdk
```

## Use

Como los métodos de la sdk son estáticos se puede llamar sin instanciarla desde cualquier archivo.

Por ejemplo:

```php
use SIMP2\SDK\SIMP2SDK;

$debts = SIMP2SDK::getDebtsOfClient($client_identifier);
```

## Methods

Cada método se corresponde con un endpoint del SIMP2

### POST /payments/notify

```php
notifyPayment(string $unique_reference): void
```

### POST /payments/confirm

```php
confirmPayment(string $unique_reference): Response
```

### POST /reverse/notify

```php
notifyRollbackPayment(string $unique_reference): Response
```

### POST /reverse/confirm

```php
confirmRollbackPayment(string $unique_reference): Response
```

### POST /integrations/metadata

```php
createMetadata(string $key, string|array $value): void
```

### POST /events/info

```php
infoEvent(string $unique_reference, string $observations, ?string $category, TypeDescription $type_description, LogLevel $logLevel, int $overwriteLogLevel = null)
```

### POST /events/error

```php
errorEvent(string $unique_reference, string $observations, ?string $category, TypeDescription $type_description, LogLevel $logLevel, int $overwriteLogLevel = null)
```

### GET /integrations/metadata/{key}

```php
getMetadata(string $key): string|array|null
```

### GET /debt/{code}

```php
getDebtInfo($code): ?Debt
```

### GET /client/{ccf_client_id}/debts

```php
getDebtsOfClient(string $ccf_client_id): array
```

### GET /debt/unique/{uniqueReference}

```php
getSubdebt(string $unique_reference): Debt
```

### GET /debt/barcode/{barcode}

```php
getSubdebtByBarcode(string $barcode): Debt
```

### Devuelve el cliente al que le pertenece la deuda

```php
getClientData(array $debts): Client
```
