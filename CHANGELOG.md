# Changelog

### 5.0.2
> 12/12/2023

* Se migró a la versión PHP ^8.1.
* Se quita la librería `bensampo/laravel-enum` porque la nueva versión de PHP soporta enumeraciones de manera nativa.
* Se quitan anotaciones propietarias del IDE PHPStorm de Jetbrains.

### 5.0.0
> 06/12/2023

* Se migró a la versión PHP ^8.1

### 2.0.0
> 15/06/2021 

* Ahora el SIMP2 Requiere el c.c.t. como header.
* Se agregó el método `setCompanyTransactionToken(string $cct): void`
* Se optó por remover los métodos estáticos y hacerlos dinámicos para evitar para el c.c.t. a cada uno de los métodos.

### 1.2.6
> 07/06/2021

* Cambios requeridos para el funcionamiento de rapipago.
### 1.2.0
> 21/04/2021

* Versión inicial.
