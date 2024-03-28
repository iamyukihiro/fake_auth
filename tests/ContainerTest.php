<?php

/** @noinspection NonAsciiCharacters */
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerTest extends KernelTestCase
{
    public function test_dev環境のサービス定義で起動できること(): void
    {
        $kernel = $this::createKernel(['environment' => 'dev']);
        $kernel->boot();

        $this->assertInstanceOf(ContainerInterface::class, $kernel->getContainer());
    }

    public function test_prod環境のサービス定義で起動できること(): void
    {
        $kernel = $this::createKernel(['environment' => 'prod']);
        $kernel->boot();

        $this->assertInstanceOf(ContainerInterface::class, $kernel->getContainer());
    }
}
