<?php

namespace App\Tests\Service;

use App\Service\EmailUtil;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class EmailUtilTest extends KernelTestCase
{
    public function testSendEmail()
    {
        self::bootKernel();
        $container = static::getContainer();
        $emailUtil = $container->get(EmailUtil::class);
        $emailUtil->sendEmail('dixon@abc.com', []);
        $this->assertEmailCount(1);

    }
}
