<?php

namespace Stnw\AvatarBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvatarManagerTest extends WebTestCase
{
    private $avatarManager;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->avatarManager = static::$kernel->getContainer()->get('avatar.manager');
    }

    public function testListFiles()
    {
        //testing of file's list in a folder.
        $listFiles = $this->avatarManager->listFiles(__DIR__, "php");
        $this->assertTrue(in_array('AvatarManagerTest.php', $listFiles));
    }

    public function testGenerateAvatar()
    {
        $avatar = sys_get_temp_dir() . "/temp_avatar_" . mt_rand(0, 99999999);
        $imgWidth = 50;
        $result = $this->avatarManager->generateAvatar($avatar, "f", $imgWidth);

        //the avatar was created
        $this->assertTrue($result);
        $this->assertTrue(file_exists($avatar));

        //the avatar has the correct size
        $imgSize = getimagesize($avatar);
        $this->assertEquals($imgSize[0], $imgWidth);

        unlink($avatar);
    }


}
