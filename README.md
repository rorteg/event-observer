# Event Observer

[![Build Status](https://travis-ci.org/rorteg/event-observer.svg?branch=master)](https://travis-ci.org/rorteg/event-observer) [![Coverage Status](https://coveralls.io/repos/github/rorteg/event-observer/badge.svg?branch=master)](https://coveralls.io/github/rorteg/event-observer?branch=master)

## Descrição
Lib para implementar o pattern Observer: https://pt.wikipedia.org/wiki/Observer

Esta lib implementa as interfaces padrões do PHP para implementação do padrão Observer:

 - https://www.php.net/manual/pt_BR/class.splsubject.php
 - https://www.php.net/manual/pt_BR/class.splobserver.php

### Benefícios

 - Fácil manutenção
 - Aplicação de princípios SOLID
   - Segregação de Interfaces
   - Responsabilidade única
 - Facilidade em alterar prioridades de execução dos observadores
 - Facilidade de habilitar ou desabilitar observadores (inclusive de dentro de um observador)
 - Código limpo, simples e legível.

### Casos comuns de uso
Lógicas de negócio que precisam fazer diversas ações após salvar os dados no banco. Geralmente são utilizadas Models que persistem os dados e implementam toda lógica de negócio, porém suas Models podem ficar gigantes como God Class e ter diversas responsabilidades dificultando em muito a manutenção. 

Um exemplo clássico seria enviar um e-mail após salvar um pedido, você pode criar métodos (afterSave, afterUpdate) e simplesmente implementar o disparo de um evento, a responsabilidade de enviar email, notificações, etc, ficariam por conta dos observadores.

## Instalação
    
Execute:
```
composer require madeiramadeirabr/event-observer
```

## Exemplo de uso



```
// Composer autoload
require_once('vendor/autoload.php');

use MadeiraMadeiraBr\Event\EventObserverFactory;
use MadeiraMadeiraBr\Integration\Event\Tests\Stub\Observer;
use MadeiraMadeiraBr\Integration\Event\Tests\Stub\Observer2;

// Factory Singleton Instance
$eventFactory = EventObserverFactory::getInstance();

// Adiciona os observers com uma key para referenciar o evento.
$eventFactory->addObserversToEvent('event_test', [Observer2::class, Observer::class]);

// Dispara o evento onde serão instanciados todos os objetos setados anteriormente.
$publisher = $eventFactory->dispatchEvent('event_test');
```

## Observadores

Você pode criar classes observadores para injetar no método explicado acima apenas implementando a interface: \MadeiraMadeiraBr\Event\ObserverInterface

### Exemplo:
```
<?php 

namespace YourNamespace;

use  MadeiraMadeiraBr\Event\ObserverInterface

class IntegrateOrderToOtherServices implements ObserverInterface
{
    /**
    * {@inheritdoc}
    */
    public function update(SplSubject $publisher)
    {
        // Você pode pegar os dados passados no dispatchEvent dessa forma:
        $order = $publisher->getEvent();
    }

    /**
    * {@inheritdoc}
    */
    public function getPriority()
    {
        return 0;
    }
}

```

A execução sempre seguirá a prioridade retornada de dentro de cada observador, independente se ele é setado antes ou depois de outros.


## Precauções
Como é possível receber objetos dentro de um observador é necessário um certo cuidado ao executar um método de um determinado objeto que pode ser um disparador de um evento.
Exemplo: Se criar um disparador de evento dentro de uma model Order no método afterSave e dentro de um observador você receber a model Order e salvar novamente, ele irá executar o observador novamente e cair em um loop infinito. Caso seja necessário essa abordagem, sempre crie uma checagem antes do método que salva os dados novamente na Model.


## Testes
Clone o repositório, instale as dependências via Composer e depois execute o comando abaixo:
```
composer test
```
