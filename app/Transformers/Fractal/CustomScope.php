<?php

namespace App\Transformers\Fractal;

use League\Fractal\Resource\Collection;
use League\Fractal\Scope;

/**
 * Scope
 *
 * The scope class acts as a tracker, relating a specific resource in a specific
 * context. For example, the same resource could be attached to multiple scopes.
 * There are root scopes, parent scopes and child scopes.
 */
class CustomScope extends Scope
{
    /**
     * Convert the current data for this scope to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = $this->data();
        $meta = $this->meta();

        if (is_null($data)) {
            if (!empty($meta)) {
                return $meta;
            }
            return null;
        }

        if (!empty($meta)) {
            $data = array_key_exists('data', $data) ? $data : ['data' => $data];
        }

        return $data + $meta;
    }

    /**
     * Hàm lấy data
     *
     * Logic của thư viện
     *
     * @return array
     */
    protected function data()
    {
        list($rawData, $rawIncludedData) = $this->executeResourceTransformers();

        $serializer = $this->manager->getSerializer();

        $data = $this->serializeResource($serializer, $rawData);

        if ($serializer->sideloadIncludes()) {
            $rawIncludedData = array_map(array($this, 'filterFieldsets'), $rawIncludedData);

            $includedData = $serializer->includedData($this->resource, $rawIncludedData);

            $data = $serializer->injectData($data, $rawIncludedData);

            if ($this->isRootScope()) {
                $includedData = $serializer->filterIncludes(
                    $includedData,
                    $data
                );
            }

            $data = $data + $includedData;
        }

        if (!empty($this->availableIncludes)) {
            $data = $serializer->injectAvailableIncludeData($data, $this->availableIncludes);
        }

        return $data;
    }

    /**
     * Hàm lấy meta
     *
     * Logic của thư viện
     *
     * @return array
     */
    protected function meta()
    {
        $serializer = $this->manager->getSerializer();

        if ($this->resource instanceof Collection) {
            if ($this->resource->hasCursor()) {
                $pagination = $serializer->cursor($this->resource->getCursor());
            } elseif ($this->resource->hasPaginator()) {
                $pagination = $serializer->paginator($this->resource->getPaginator());
            }

            if (!empty($pagination)) {
                $this->resource->setMetaValue(key($pagination), current($pagination));
            }
        }

        return $serializer->meta($this->resource->getMeta());
    }
}
