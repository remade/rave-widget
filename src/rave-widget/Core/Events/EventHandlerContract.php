<?php

namespace Remade\RaveWidget\Core\Events;

use Cake\Event\EventListenerInterface;

interface EventHandlerContract extends EventListenerInterface {

    public function onInit($event);
    public function onSuccessful($event);
    public function onFailure($event);
    public function onTimeOut($event);
    public function onCancel($event);

}