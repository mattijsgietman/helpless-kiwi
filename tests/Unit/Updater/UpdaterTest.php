<?php

namespace Tests\Unit\Updater;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class UpdaterTest.
 */
class UpdaterTest extends KernelTestCase
{
    /**
     * @var array
     */
    protected $session_vars;

    /**
     * @var array
     */
    protected $env_vars;

    /**
     * @var string
     */
    protected $updater_file;

    /**
     * @var string
     */
    protected $autoload_file;

    /**
     * @var string
     */
    protected $temp_testing_dir;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        // Get root.
        $kiwi_root = dirname(__FILE__, 4);

        // Get updater file path
        $this->updater_file = $kiwi_root.'/public/update.php';

        // Dev autoloader, loading the downloaded one misses out on new composer additions.
        $this->autoload_file = $kiwi_root.'/vendor/autoload.php';

        $this->temp_testing_dir = $kiwi_root.Dir::TEMP_DIR;

        // Load .env vars
        $this->loadEnvVars($kiwi_root);

        // Load all test vars.
        $this->loadSessionVar();
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        // Get root.
        $kiwi_root = dirname(__FILE__, 4);

        // Make temp test dir.
        $temp_testing_dir = $kiwi_root.Dir::TEMP_DIR;
        if (!file_exists($temp_testing_dir)) {
            mkdir($temp_testing_dir);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass(): void
    {
        // Get root.
        $kiwi_root = dirname(__FILE__, 4);
        $temp_testing_dir = $kiwi_root.Dir::TEMP_DIR;

        // Remove the testing dir.
        self::remove_dir($temp_testing_dir);

        parent::tearDownAfterClass();
    }

    /**
     * This test also setups a kiwi instance to test stuff.
     */
    public function testDownload(): void
    {
        // Set SESSION and load the updater.
        $_SESSION = $this->session_vars;
        include_once $this->updater_file;

        download_kiwi();

        // Check if index.php exists. If so, then it is a succesfull download.
        $this->assertFileExists(kiwidir(Dir::PUBLIC_DIR).'/kiwi/index.php');
    }

    /**
     * @depends testDownload
     */
    public function testBackup(): void
    {
        // Set SESSION and load the updater.
        $_SESSION = $this->session_vars;
        include_once $this->updater_file;

        // Generate some test files, that should be excluded from the backup.
        $dir_exceptions = get_dir_exceptions(Dir::KIWI_DIR);
        foreach ($dir_exceptions as $dir) {
            $dirname = kiwidir(Dir::KIWI_DIR).'/'.$dir;

            if (!file_exists($dirname)) {
                mkdir($dirname);
            }

            $file = $dirname.'/testfile';
            if (!file_exists($file)) {
                file_put_contents($file, 'content');
            }
        }

        create_backup();

        // Check if index.php exists. If so, then it is a succesfull download.
        $this->assertFileExists(kiwidir(Dir::BACKUP_KIWI));
    }

    /**
     * @depends testDownload
     */
    public function testDatabaseBackup(): void
    {
        $_SESSION = $this->session_vars;
        include_once $this->updater_file;

        database_backup();

        // Check if database dump exist. If so, then it is a succesfull backup.
        $this->assertFileExists(kiwidir(Dir::BACKUP_SQL));
    }

    public function loadSessionVar()
    {
        $db_session = $this->getDatabase($this->env_vars);
        $other_session = $this->set_misc_data();
        $this->session_vars = array_merge($db_session, $other_session);
    }

    public function set_misc_data()
    {
        return [
            'unit_test' => true,
            'unit_test_dir' => $this->temp_testing_dir,
            'unit_test_env' => $this->env_vars,
            'unit_autoload' => $this->autoload_file,
            'email_type' => 'stmp',
            'mailer_url' => 'mailer://url',
            'mailer_email' => 'mail@mail.com',
            'updater_pass' => 'pass',
            'org_name' => 'unit_kiwi',
            'sec_type' => '',
            'admin_name' => 'admin',
            'admin_email' => 'admin@mail.com',
            'admin_pass' => 'admin',
            'app_id' => 'app_id_1',
            'app_secret' => 'secrit_1',
            'bunny_url' => 'bunny_url',
            'step' => 'intro',
            'install_progress' => 'start',
            'log' => '',
            'install_error' => false, ];
    }

    public static function remove_dir($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                self::remove_dir("$dir/$file");
            } else {
                unlink("$dir/$file");
            }
        }

        if (2 == count(scandir($dir))) {
            rmdir($dir);
        } else {
            self::remove_dir($dir);
        }
    }

    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    public function getDatabase($env_vars)
    {
        $sql_url = $env_vars['DATABASE_URL'];

        $firstpart = stristr($sql_url, '//', true).'host='.stristr($sql_url, '@', false);
        $secondpart = stristr($firstpart, '/', false);
        $thirdpart = stristr($sql_url, '@', false);
        $host = 'http://'.substr(stristr($thirdpart, '/', true), 1);
        $name = substr($secondpart, 1);

        $firstpart = stristr($sql_url, '//', false);
        $secondpart = stristr($firstpart, ':', false);
        $db_username = substr(stristr($firstpart, ':', true), 2);
        $db_password = substr(stristr($secondpart, '@', true), 1);

        $db_type = 'sqldb';

        return ['db_type' => $db_type,
            'db_name' => $name,
            'db_host' => $host,
            'db_user' => $db_username,
            'db_pass' => $db_password, ];
    }

    public function loadEnvVars($root)
    {
        $dotenv = new Dotenv();
        $dotenv->load($root.'/.env.local');
        $this->env_vars = $_ENV;
    }
}

abstract class Dir
{
    const ROOT_DIR = '';
    const KIWI_DIR = '/kiwi';
    const PUBLIC_DIR = '/public_html';
    const BACKUP_DIR = '/back_up';
    const BACKUP_KIWI = '/back_up/kiwi_backup.zip';
    const BACKUP_SQL = '/back_up/sql_dump.sql';
    const TEMP_DIR = '/temp_update_test_dir';
}
