<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchBundle\Command;

use ONGR\ElasticsearchBundle\Client\Connection;
use ONGR\ElasticsearchBundle\ORM\Manager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * AbstractElasticsearchCommand class.
 */
abstract class AbstractManagerAwareCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption(
            'manager',
            null,
            InputOption::VALUE_REQUIRED,
            'Manager name',
            'default'
        );
    }

    /**
     * Returns elasticsearch manager by name with latest mappings.
     *
     * @param string $name
     *
     * @return Manager
     *
     * @throws \RuntimeException
     */
    protected function getManager($name)
    {
        $id = $this->getManagerId($name);

        if ($this->getContainer()->has($id)) {
            return $this->getContainer()->get($id);
        }

        throw new \RuntimeException(
            sprintf('Manager named `%s` not found. Check your configuration.', $name)
        );
    }

    /**
     * Returns connection service id.
     *
     * @param string $name
     *
     * @return string
     */
    private function getManagerId($name)
    {
        $manager = $name == 'default' || empty($name) ? 'es.manager' : sprintf('es.manager.%s', $name);

        return $manager;
    }
}
