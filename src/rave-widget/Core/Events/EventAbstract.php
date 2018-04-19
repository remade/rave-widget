<?php

namespace Remade\RaveWidget\Core\Events;

abstract class EventAbstract implements EventHandlerContract
{
    public function implementedEvents()
    {
        return [
            'widget.initialize' => 'onInit',
            'widget.success' => 'onSuccessful',
            'widget.failure' => 'onFailure',
            'widget.timeout' => 'onTimeOut',
            'widget.cancel' => 'onCancel',
        ];
    }

    public abstract function onInit($event);
    public abstract function onSuccessful($event);
    public abstract function onFailure($event);
    public abstract function onTimeOut($event);
    public abstract function onCancel($event);
}