<?php

namespace App\Transformers\Fractal;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\ScopeFactory;
use League\Fractal\Scope;

class CustomScopeFactory extends ScopeFactory
{
    /**
     * @param Manager $manager
     * @param ResourceInterface $resource
     * @param string|null $scopeIdentifier
     * @return CustomScope
     */
    public function createScopeFor(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null)
    {
        return new CustomScope($manager, $resource, $scopeIdentifier);
    }
}
