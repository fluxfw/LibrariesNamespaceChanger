<?php

namespace srag\LibrariesNamespaceChanger;

use Composer\Script\Event;

/**
 * Class LibrariesNamespaceChanger
 *
 * @package srag\LibrariesNamespaceChanger
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @access  package
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
		"BexioCurl",
		"CustomInputGUIs",
		"DIC",
		"JasperReport",
		"JiraCurl",
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
	 * @var string
	 *
	 * @access package
	 */
	const PLUGIN_NAME_REG_EXP = "/\/([A-Za-z0-9_]+)\/vendor\//";
	/**
	 * @var string
	 *
	 * @access package
	 */
	const SRAG = "srag";


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
	 *
	 * @access package
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
		$plugin_name = $this->getPluginName();

		if (!empty($plugin_name)) {
			foreach (self::$libraries as $library) {
				$folder = __DIR__ . "/../../" . strtolower($library);

				if (is_dir($folder)) {
					$files = $this->getFiles($folder);

					foreach ($files as $file) {
						$code = file_get_contents($file);

						$code = str_replace(self::SRAG . "\\" . $library, self::SRAG . "\\" . $library . "\\" . $plugin_name, $code);

						$code = str_replace(self::SRAG . "\\\\" . $library, self::SRAG . "\\" . $library . "\\\\" . $plugin_name, $code);

						file_put_contents($file, $code);
					}
				}
			}
		}
	}


	/**
	 * @return string
	 */
	private function getPluginName()/*: string*/ {
		$matches = [];
		preg_match(self::PLUGIN_NAME_REG_EXP, __DIR__, $matches);

		if (is_array($matches) && count($matches) >= 2) {
			$plugin_name = $matches[1];

			return $plugin_name;
		} else {
			return "";
		}
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
			if ($file !== "." && $file !== "..") {
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
