<?php /*~~~*/
add_action( 'lct_additional_primes', 'lct_additional_primes' );
function lct_additional_primes( $queue ) {
	//Use this to reset the the cron's [Bug with W3 Total Cache]
	//wp_clear_scheduled_hook( 'w3_pgcache_prime' );
	//wp_clear_scheduled_hook( 'w3_pgcache_cleanup' );

	if ( ! empty( $queue ) ) {
		foreach ( $queue as $url ) {
			//Use to force clear URLs
			//$w3_cacheflush = w3_instance( 'W3_CacheFlushLocal' );
			//$w3_cacheflush->flush_url( $url );

			$user_agents = [
				//'googlebot_mobile'                => '',
				//'googlebot'                       => '',
				'google_chrome_mobile_all_others' => 'chrome mobile',
				'firefox_mobile_all_others'       => 'firefox mobile',
				'android_mobile_all_others'       => 'android mobile',
				'iphone'                          => 'iphone',
				'ipad'                            => 'ipad',
				'ipod'                            => 'ipod',
				'mobile_all_others'               => 'mobile',
				'google_chrome_50s'               => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0 Safari/537.36',
				'google_chrome_40s'               => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
				'google_chrome_30s'               => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0 Safari/537.36',
				'google_chrome_pre_30'            => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0F Safari/537.36',
				'firefox_40s'                     => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0',
				'firefox_30s'                     => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0',
				'firefox_pre_30'                  => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0',
				'internet_explorer_11'            => 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; LCJB; rv:11.0) like Gecko',
				'internet_explorer_10'            => 'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0',
				'internet_explorer_9'             => 'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US))',
				'internet_explorer_8'             => 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 1.1.4322)',
				'internet_explorer_7'             => 'Mozilla/4.0(compatible; MSIE 7.0b; Windows NT 6.0)',
				'internet_explorer_all_others'    => 'Mozilla/4.0(compatible; MSIE;)'
			];

			$user_agents = apply_filters( 'lct_additional_prime_agents', $user_agents );

			if ( ! empty( $user_agents ) ) {
				foreach ( $user_agents as $user_agent ) {
					/** @noinspection PhpUndefinedFunctionInspection */
					w3_http_get( $url, [ 'user-agent' => $user_agent ] );
				}
			}
		}
	}
}
