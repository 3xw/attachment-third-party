<?php
namespace Attachment\ThirdParty\Test\TestCase\Shell\Task;

use Attachment\ThirdParty\Shell\Task\QueueMoveFileTask;
use Cake\TestSuite\TestCase;

/**
 * Attachment\ThirdParty\Shell\Task\QueueMoveFileTask Test Case
 */
class QueueMoveFileTaskTest extends TestCase
{
    /**
     * ConsoleIo mock
     *
     * @var \Cake\Console\ConsoleIo|\PHPUnit_Framework_MockObject_MockObject
     */
    public $io;

    /**
     * Test subject
     *
     * @var \Attachment\ThirdParty\Shell\Task\QueueMoveFileTask
     */
    public $QueueMoveFile;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')->getMock();
        $this->QueueMoveFile = new QueueMoveFileTask($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->QueueMoveFile);

        parent::tearDown();
    }

    /**
     * Test getOptionParser method
     *
     * @return void
     */
    public function testGetOptionParser()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
