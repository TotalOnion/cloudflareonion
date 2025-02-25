<?php
namespace GlobalCfo\Controllers\Admin;

use GlobalCfo\Controllers\Admin;
use GlobalCfo\Controllers\AbstractController;
use GlobalCfo\Controllers\Admin\Logger;
use GlobalCfo\Controllers\Admin\CfoManager;


class CfoAPI extends AbstractController
{
    private Logger $logger;
	private CfoManager $manager;

	public function __construct($pluginName, $version, $cfoManager)
    {
        $this->logger = new Logger(GLOBAL_CFO_VERSION, GLOBAL_CFO_NAME);
		$this->manager = $cfoManager;
        parent::__construct($pluginName, $version);
    }

	public function registerEndpoints()
	{
		$this->registerPurgeMarketEndpoint();
	}

	function registerPurgeMarketEndpoint() {
		$args = [
			'market_id' => [
				'description'       => 'Market ID',
				'type'              => 'string',
				'required'          => true,
				'validate_callback' => fn( $param ) => is_string( $param ),
				'sanitize_callback' => 'sanitize_text_field',
			],
		];
		register_rest_route(
			'cfo',
			'/purge_market',
			array(
				'methods'  => 'GET',
				'callback' => [$this, 'purge_market'],
				'permission_callback' => fn() => current_user_can( 'manage_options' ),
				'args'     => $args,
			)
		);
	}
	
	public function purge_market( $request ) {
		$marketId = $request['market_id'];
		return $this->manager->purgeMarket( $marketId );
	}
}