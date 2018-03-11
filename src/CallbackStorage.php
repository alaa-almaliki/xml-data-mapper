<?php

namespace Xml\Data;

/**
 * Class CallbackStorage
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class CallbackStorage
{
    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * @param string $key
     * @param callable $callback
     * @return $this
     */
    public function registerCallback($key, callable $callback)
    {
        $this->callbacks[$key] = $callback;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function unregisterCallback($key)
    {
        if (isset($this->callbacks[$key])) {
            unset($this->callbacks[$key]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        unset($this->callbacks);
        $this->callbacks = [];
        return $this;
    }

    /**
     * @return array
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * @param string $key
     * @return callable|null
     */
    public function getCallback($key)
    {
        return isset($this->callbacks[$key])? $this->callbacks[$key]: null;
    }
}
