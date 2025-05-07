<?php
namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\Admin;
use GlobalCfo\Controllers\AbstractController;
use GlobalCfo\Controllers\Admin\Logger;
use GlobalCfo\Controllers\Admin\CfoManager;
use \WP_CLI as CLI;


class CfoCommand extends AbstractController
{
    private Logger $logger;
	private CfoManager $manager;

	public function __construct($pluginName, $version, $cfoManager)
    {
        $this->logger = new Logger(GLOBAL_CFO_VERSION, GLOBAL_CFO_NAME);
		$this->manager = $cfoManager;
        parent::__construct($pluginName, $version);
    }

    public function purgemarkets()
    {
        CLI::log( sprintf( 'Purging marktets' ) );
        $purge = $this->manager->purgeMarkets();
        if ($purge) {
            CLI::success( 'Request successfully sent with response : ' . $purge);
        } else {
            CLI::error( 'Wpml is not enabled on this site.' );
        }
    }

    public function purgeprefix($args)
    {
        $prefix = $args[0];
        CLI::log( 'Clearing prefix : ' . $prefix);
        $purge = $this->manager->purgePrefix($prefix);
        if ($purge) {
            CLI::success( 'Request successfully sent with response : ' . $purge);
        } else {
            CLI::error( 'An error occured' );
        }
    }
}