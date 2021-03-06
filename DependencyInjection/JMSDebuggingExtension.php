<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\DebuggingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class JMSDebuggingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $config = $processor->process($this->getConfigTree(), $configs);

        $container->setParameter('jms.debugging.debug', $config['debug']);
        $container->setParameter('jms.debugging.auto_help', $config['auto_help']);
    }

    private function getConfigTree()
    {
        $tb = new TreeBuilder();

        return $tb->root('jms_debugging')
                ->children()
                    ->booleanNode('auto_help')->defaultFalse()->end()

                    // this is only relevant if you want to modify the javascript files
                    ->booleanNode('debug')->defaultFalse()->end()
                ->end()
            ->end()
            ->buildTree();
    }
}