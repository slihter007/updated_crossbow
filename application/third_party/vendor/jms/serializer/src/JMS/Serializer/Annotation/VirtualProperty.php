<?php

/*
 * Copyright 2016 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 *
 * @author Alexander Klimenkov <alx.devel@gmail.com>
 */
final class VirtualProperty
{
    public $exp;
    public $name;
    public $options = array();

    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data['name'] = $data['value'];
            unset($data['value']);
        }

        foreach ($data as $key => $value) {
            if (!property_exists(__CLASS__, $key)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, __CLASS__));
            }
            $this->{$key} = $value;
        }
    }
}

