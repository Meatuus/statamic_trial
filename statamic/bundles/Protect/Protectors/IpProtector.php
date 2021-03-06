<?php

namespace Statamic\Addons\Protect\Protectors;

class IpProtector extends AbstractProtector
{
    /**
     * Whether or not this provides protection.
     *
     * @return bool
     */
    public function providesProtection()
    {
        return array_key_exists('ip_address', $this->scheme)
               && !empty(array_get($this->scheme, 'ip_address.allowed', []));
    }

    /**
     * Provide protection
     *
     * @return void
     */
    public function protect()
    {
        if (! in_array(request()->ip(), $this->getAllowedIps())) {
            $this->deny();
        }
    }

    protected function getAllowedIps()
    {
        return array_get($this->scheme, 'ip_address.allowed');
    }
}