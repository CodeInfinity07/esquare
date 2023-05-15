<?php

namespace CTXFeed\V5\Common;


use CTXFeed\V5\Download\FileDownload;
use CTXFeed\V5\Utility\CTX_WC_Log_Handler;

/**
 * Class DownloadFiles
 *
 * @package    CTXFeed\V5\Common
 * @subpackage CTXFeed\V5\Common
 */
class DownloadFiles {

	public function __construct() {
		add_action( 'admin_post_wf_download_feed_log', [ $this, 'download_log' ], 10 );
		add_action( 'admin_post_wf_download_feed', [ $this, 'download_feed' ], 10 );
	}

	/**
	 * Download Feed Log.
	 *
	 * @return void
	 *
	 * @throw RuntimeException
	 */
	public function download_log() {
		if (
			isset( $_REQUEST['feed'], $_REQUEST['_wpnonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wpf-log-download' )
		) {
			$feed_name     = sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) );
			$feed_name     = str_replace( 'wf_feed_', '', $feed_name );
			$log_file_path = CTX_WC_Log_Handler::get_log_file_path( $feed_name );

			$file_name = sprintf(
				'%s-%s-%s.log',
				sanitize_title( $feed_name ),
				gmdate( 'Y-m-d', time() ),
				time()
			);

			if ( ! file_exists( $log_file_path ) ) {
				exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'log_file_not_found', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
			}

			$fileDownload = new FileDownload( fopen( $log_file_path, 'rb' ) );
			$fileDownload->sendDownload( $file_name );
		} else {
			exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'log_file_not_found', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
		}
	}

	/**
	 * Download feed.
	 *
	 * @return void
	 */
	public function download_feed() {
		if (
			isset( $_REQUEST['feed'], $_REQUEST['_wpnonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wpf-download-feed' )
		) {
			$feed_name = sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) );
			/* your file, somewhere opened with fopen() or tmpfile(), etc.. */
			$config = Factory::get_feed_info( $feed_name );

			if ( ! file_exists( $config->get_feed_path() ) ) {
				exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'feed_download_failed', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
			}

			$fileData     = fopen( $config->get_feed_path(), 'rb' );
			$fileDownload = new FileDownload( $fileData );
			$fileDownload->sendDownload( $config->get_feed_file_name() );
		} else {
			exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'feed_download_failed', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
		}
	}

}
