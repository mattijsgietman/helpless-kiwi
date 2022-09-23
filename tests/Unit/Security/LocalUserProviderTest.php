<?php

namespace Tests\Unit\Security;

use App\Entity\Security\LocalAccount;
use App\Security\LocalUserProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Drenso\OidcBundle\Model\OidcUserData;
use Drenso\OidcBundle\Security\Token\OidcToken;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class LocalUserProviderTest.
 *
 * @covers \App\Security\LocalUserProvider
 */
class LocalUserProviderTest extends KernelTestCase
{
    /**
     * @var LocalUserProvider
     */
    protected $localUserProvider;

    /**
     * @var EntityManagerInterface&MockObject
     */
    protected $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->em = $this->createMock(EntityManager::class);
        $this->localUserProvider = new LocalUserProvider($this->em);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->localUserProvider);
        unset($this->em);
    }

    public function testRefreshUser(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testSupportsClass(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testEnsureUserExistsNewAccount(): void
    {
        // Arrange data
        $token = new OidcUserData(['sub' => '123']);

        // Arrange stubs
        /** @var ServiceEntityRepository&MockObject */
        $repo = $this->createMock(ServiceEntityRepository::class);
        $repo->method('findOneBy')->willReturn(null);
        $this->em->method('getRepository')->willReturn($repo);

        // Expect the object to be flushed to db
        $this->em->expects(self::once())->method('persist');
        $this->em->expects(self::once())->method('flush');

        // Act
        $this->localUserProvider->ensureUserExists($token->getSub(), $token);
    }

    public function testEnsureUserExists(): void
    {
        // Arrange data
        $account = new LocalAccount();
        $token = new OidcUserData(['sub' => '123', 'email' => 'foo@bar.com']);

        // Arrange stubs
        /** @var ServiceEntityRepository&MockObject */
        $repo = $this->createMock(ServiceEntityRepository::class);
        $repo->method('findOneBy')->willReturn($account);
        $this->em->method('getRepository')->willReturn($repo);

        // Expect the object to be flushed to db
        $this->em->expects(self::once())->method('flush');

        // Act
        $this->localUserProvider->ensureUserExists($token->getSub(), $token);

        // Assert that $account has been updated to match token
        self::assertSame($account->getEmail(), $token->getEmail());
    }

    public function testLoadOidcUser(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testLoadUserByUsername(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testLoadUserByIdentifier(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
