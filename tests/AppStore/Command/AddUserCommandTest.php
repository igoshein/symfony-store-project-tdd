<?php

namespace Tests\Command;

use AppBundle\Command\AddUserCommand;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AddUserCommandTest extends KernelTestCase
{

    private $userData = [
        'username' => 'chuck_norris',
        'password' => 'foobar',
        'email' => 'chuck@norris.com',
        'full-name' => 'Chuck Norris',
    ];

    public function setUp()
    {
        exec('stty 2>&1', $output, $exitcode);
        $isSttySupported = 0 === $exitcode;

        $isWindows = DIRECTORY_SEPARATOR;

        if ($isWindows || !$isSttySupported) {
            $this->markTestSkipped('`stty` is required to test this command.');
        }
    }

    /**
     * @dataProvider isAdminDataProvider
     *
     * This test provides all the arguments required by the command, so the
     * command runs non-interactively and it won't ask for any argument.
     */
    public function testCreateUserNonInteractive($isAdmin)
    {
        $input = $this->userData;
        if ($isAdmin) {
            $input['--admin'] = 1;
        }
        $this->executeCommand($input);

        $this->assertUserCreated($isAdmin);
    }

    /**
     * @dataProvider isAdminDataProvider
     *
     * This test doesn't provide all the arguments required by the command, so
     * the command runs interactively and it will ask for the value of the missing
     * arguments.
     * See https://symfony.com/doc/current/components/console/helpers/questionhelper.html#testing-a-command-that-expects-input
     */
    public function testCreateUserInteractive($isAdmin)
    {
        $this->executeCommand(
        // these are the arguments (only 1 is passed, the rest are missing)
            $isAdmin ? ['--admin' => 1] : [],
            // these are the responses given to the questions asked by the command
            // to get the value of the missing required arguments
            array_values($this->userData)
        );

        $this->assertUserCreated($isAdmin);
    }

}
