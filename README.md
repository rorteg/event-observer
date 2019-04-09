# Event Observer

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

Adicione as seguintes linhas no composer.json

    "repositories": [
        {
            "type": "git",
            "url":  "git@github.com:rorteg/event-observer.git"
        }
    ]
    
Execute:
```
composer require madeiramadeirabr/event-observer
```

## Exemplo de uso

O ideal usar configturações de modo a poder preparar os observers no início do cíclo de uma request e persistir o objeto (Publisher) até o envio da response para que qualquer classe durante o cíclo possa apenas executar o método notify() da classe \Server\Core\Event\Publisher.

Porém é possível utilizar de forma pontual utilizando a \Server\Core\Event\EventObserverFactory e injetando os observers diretamente no momento de disparar um evento, dessa forma o impacto na aplicação é apenas pontual. Pode por exemplo setar uma constante contendo um array com as classes observadoras e passar como parâmetro para que não fique como responsabilidade da classe disparadora definir os observadores.

```
EventObserverFactory::dispatchEvent(
            'key_name_to_identify_event',
            [ // Array contendo os observadores para escutarem esse evento.
                Observer1::class, 
                Observer2::class,
                Observer3::class
            ],
            $this // Aqui você pode passar tanto o escopo da classe que está executando como passar um array com dados ou nada.
        )
```

## Observadores

Você pode criar classes observadoras para injetar no método explicado acima apenas implementando a interface: \Server\Core\Event\ObserverInterface

### Exemplo:
```
<?php 

namespace YourNamespace;

use  Server\Core\Event\ObserverInterface

class IntegrateOrderToOtherServices implements ObserverInterface
{
    public function update(SplSubject $publisher)
    {
        // Você pode pegar os dados passados no dispatchEvent dessa forma:
        $order = $publisher->getEvent();
    }
}

```

## Precauções
Como é possível receber objetos dentro de um observador é necessário um certo cuidado ao executar um método de um determinado objeto que pode ser um disparador de um evento.
Exemplo: Se criar um disparador de evento dentro de uma model Order no método afterSave e dentro de um observador você receber a model Order e salvar novamente, ele irá executar o observador novamente e cair em um loop infinito. Caso seja necessário essa abordagem, sempre crie uma checagem antes do método que salva os dados novamente na Model.


## Testes
Clone o repositório e depois execute o comando abaixo:
```
composer test
```
