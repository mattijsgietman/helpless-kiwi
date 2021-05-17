<?php

namespace Tests\Functional\Controller\Admin;

use App\Controller\Admin\ActivityController;
use App\Entity\Activity\Activity;
use App\Log\EventService;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Helper\Database\Activity\ActivityFixture;
use Tests\Helper\Database\Activity\PriceOptionFixture;
use Tests\Helper\Database\Activity\RegistrationFixture;
use Tests\Helper\Database\Security\LocalAccountFixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\Helper\AuthWebTestCase;

/**
 * Class ActivityControllerTest.
 *
 * @covers \App\Controller\Admin\ActivityController
 */
class ActivityControllerTest extends AuthWebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var ActivityController
     */
    protected $activityController;

    /**
     * @var EventService
     */
    protected $events;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        //self::bootKernel();
        $this->login();

        $this->loadFixtures([
            LocalAccountFixture::class,
            PriceOptionFixture::class,
            ActivityFixture::class,
            RegistrationFixture::class,
        ]);

        $this->events = self::$container->get(EventService::class);
        $this->activityController = new ActivityController($this->events);

        $this->em = self::$container->get(EntityManagerInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->activityController);
        unset($this->events);
        unset($this->em);
    }

    public function testIndexAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testNewAction(): void
    {
        $local_file = __DIR__.'/../../../assets/Faint.png';
        $activity_name = 'testname';

        // Act
        $crawler = $this->client->request('GET', '/admin/activity/new');

        // Act
        $form = $crawler->selectButton('Toevoegen')->form();
        $form['activity_new[name]'] = $activity_name;
        $form['activity_new[description]'] = 'added through testing';
        $form['activity_new[location][address]'] = 'In php unittest';
        $form['activity_new[deadline][date]'] = '2013-03-15';
        $form['activity_new[deadline][time]'] = '23:59';
        $form['activity_new[start][date]'] = '2013-03-15';
        $form['activity_new[start][time]'] = '23:59';
        $form['activity_new[end][date]'] = '2013-03-15';
        $form['activity_new[end][time]'] = '23:59';
        $form['activity_new[imageFile][file]'] = new UploadedFile(
            $local_file,
            'Faint.png',
            'image/png',
            null,
            null,
            true
        );
        $form['activity_new[color]'] = 1;

        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.container', 'Activiteit '.$activity_name);
    }

    public function testShowAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testEditAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testImageAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testDeleteAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testPriceNewAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testPriceEditAction(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testPresentEditAction(): void
    {
        //Arange
        $activities = $this->em->getRepository(Activity::class)->findAll();
        $id = $activities[0]->getId();
        $comment = 'This is a test person for testing purposes';
        $valueexists = false;

        //Act
        $crawler = $this->client->request('GET', "/admin/activity/{$id}/present");
        $form = $crawler->selectButton('Opslaan')->form();
        $form['activity_edit_present[registrations][0][present]']->setValue('2');
        $form['activity_edit_present[registrations][0][comment]']->setValue($comment);
        $this->client->submit($form);

        //Assert
        $activity = $this->em->getRepository(Activity::class)->find($id);
        if ($activity) {
            $registrations = $activity->getRegistrations();
            foreach ($registrations as $register) {
                if ($register->getComment() == $comment) {
                    $valueexists = true;
                }
            }
        }
        $this->assertSelectorTextContains('.flash', 'Aanwezigheid aangepast');
        $this->assertTrue($valueexists);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testSetAmountPresent(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testResetAmountPresent(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
