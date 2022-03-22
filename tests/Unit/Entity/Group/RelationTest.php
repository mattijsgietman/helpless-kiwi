<?php

namespace Tests\Unit\Entity\Group;

use App\Entity\Group\Group;
use App\Entity\Group\Relation;
use App\Entity\Security\LocalAccount;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RelationTest.
 *
 * @covers \App\Entity\Group\Relation
 * @group entities
 */
class RelationTest extends KernelTestCase
{
    /**
     * @var Relation
     */
    protected $relation;
    protected $relation2;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->relation = new Relation();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->relation);
    }

    public function testGetId(): void
    {
        $expected = '42';
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getId());
    }

    public function testGetDescription(): void
    {
        $expected = '42';
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('description');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getDescription());
    }

    public function testSetDescription(): void
    {
        $expected = '42';
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('description');
        $property->setAccessible(true);
        $this->relation->setDescription($expected);
        $this->assertSame($expected, $property->getValue($this->relation));
    }

    public function testGetGroup(): void
    {
        $expected = new Group();
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('group');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getGroup());
    }

    public function testSetGroup(): void
    {
        $expected = new Group();
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('group');
        $property->setAccessible(true);
        $this->relation->setGroup($expected);
        $this->assertSame($expected, $property->getValue($this->relation));
    }

    public function testGetPerson(): void
    {
        $expected = new LocalAccount();
        $expected->setEmail('john@doe.eyes');
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('person');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getPerson());
    }

    public function testSetPerson(): void
    {
        $expected = new LocalAccount();
        $expected->setEmail('john@doe.eyes');
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('person');
        $property->setAccessible(true);
        $this->relation->setPerson($expected);
        $this->assertSame($expected, $this->relation->getPerson());
    }

    public function testGetParent(): void
    {
        $expected = new Relation();
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('parent');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getParent());
    }

    public function testSetParent(): void
    {
        $expected = new Relation();
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('parent');
        $property->setAccessible(true);
        $this->relation->setParent($expected);
        $this->assertSame($expected, $property->getValue($this->relation));
    }

    public function testGetChildren(): void
    {
        $expected = new ArrayCollection();
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('children');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getChildren());
    }

    public function testAddChild(): void
    {
        $expected = new Relation();
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('children');
        $property->setAccessible(true);
        $this->relation->addChild($expected);
        $this->assertSame($expected, $property->getValue($this->relation)[0]);
    }

    public function testRemoveChild(): void
    {
        $expected = new ArrayCollection();
        $relation = new Relation();
        $expected->add($relation);
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('children');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($relation, $property->getValue($this->relation)[0]);

        $this->relation->removeChild($relation);
        $this->assertNotSame($relation, $property->getValue($this->relation));
    }

    //TODO figure out how to create recurvice testing objects
    public function testGetRoot(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    //TODO this feels.... funky, and not in the good way.
    public function testGetRootChildless(): void
    {
        $expected = '42';
        $property = (new ReflectionClass(Relation::class))
            ->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->relation, $expected);
        $this->assertSame($expected, $this->relation->getRoot()->getId());
    }

    public function testGetChildrenRecursive(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetAllRelations(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
