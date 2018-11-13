<?php

namespace srag\LibrariesNamespaceChanger;

use Composer\Script\Event;

/**
 * Class LibrariesNamespaceChanger
 *
 * @package srag\LibrariesNamespaceChanger
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class LibrariesNamespaceChanger {

	/**
	 * @var self
	 */
	private static $instance = NULL;
	/**
	 * @var array
	 */
	private static $libraries = [
		"ActiveRecordConfig",
		"DIC",
		"RemovePluginDataConfirm"
	];
	/**
	 * @var array
	 */
	private static $exts = [
		"json",
		"md",
		"php"
	];


	/**
	 * @param Event $event
	 *
	 * @return self
	 */
	private static function getInstance(Event $event)/*: self*/ {
		if (self::$instance === NULL) {
			self::$instance = new self($event);
		}

		return self::$instance;
	}


	/**
	 * @param Event $event
	 */
	public static function rewriteLibrariesNamespaces(Event $event) {
		self::getInstance($event)->doRewriteLibrariesNamespaces();
	}


	/**
	 * @var Event
	 */
	private $event;


	/**
	 * LibrariesNamespaceChanger constructor
	 *
	 * @param Event $event
	 */
	private function __construct(Event $event) {
		$this->event = $event;
	}


	/**
	 *
	 */
	private function doRewriteLibrariesNamespaces()/*: void*/ {
		$plugin_namespace = $this->getPluginNamespace();

		foreach (self::$libraries as $library) {
			$folder = __DIR__ . "/../../" . strtolower($library);

			$files = $this->getFiles($folder);

			foreach ($files as $file) {
				$code = file_get_contents($file);
				$code = str_replace("srag\\" . $library, $plugin_namespace . "\\" . $library, $code);
				$code = str_replace("srag\\\\" . $library, $plugin_namespace . "\\\\" . $library, $code);
				file_put_contents($file, $code);
			}
		}
	}


	/**
	 * @return string
	 */
	private function getPluginNamespace()/*: string*/ {
		$composer_json_file = __DIR__ . "/../../../../composer.json";

		$composer_json = json_encode(file_get_contents($composer_json_file));

		reset($composer_json->autoload->{"psr-4"});

		$plugin_namespace = key($composer_json->autoload->{"psr-4"});

		return $plugin_namespace;
	}


	/**
	 * @param string $folder
	 * @param array  $files
	 *
	 * @return array
	 */
	private function getFiles(/*string*/
		$folder, &$files = [])/*: array*/ {
		$paths = scandir($folder);

		foreach ($paths as $file) {
			if ($file !== "." && $file .= "..") {
				$path = $folder . "/" . $file;

				if (is_dir($path)) {
					$this->getFiles($path, $files);
				} else {
					$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					if (in_array($ext, self::$exts)) {
						array_push($files, $path);
					}
				}
			}
		}

		return $files;
	}
}
