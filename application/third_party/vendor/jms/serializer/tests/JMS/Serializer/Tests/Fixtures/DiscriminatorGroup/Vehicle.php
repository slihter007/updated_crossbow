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

namespace JMS\Serializer\Tests\Fixtures\DiscriminatorGroup;

use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\Discriminator(field = "type", groups={"foo"}, map = {
 *    "car": "JMS\Serializer\Tests\Fixtures\DiscriminatorGroup\Car"
 * })
 */
abstract class Vehicle
{
    /**
     * @Serializer\Type("integer")
     * @Serializer\Groups({"foo"})
     */
    public $km;

    public function __construct($km)
    {
        $this->km = (integer) $km;
    }
}
