<?php

/**
 * Copernicus translation class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * translation class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Translation {

	// all meta boxes
	private $translation = array();

	/**
	 * Class constructor
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function __construct() {
		
		// initialize the meta boxes
		$this->_init();
	}

	/**
	 * Initiate the meta boxes
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function _init() {

		// get all files from config folder
		if ($handle = opendir(get_stylesheet_directory() . '/translation/')) {

			// for each file with .config.php extension
			while (false !== ($filename = readdir($handle))) {
				
				if (preg_match('/.csv$/', $filename)) {
				
					$this->get_adapter_csv($filename);
				}
			}
			closedir($handle);
		}
	}
	
	private function get_adapter_csv($translation) {
		$csv_filename = get_stylesheet_directory() . '/translation/' . $translation;

		if (file_exists($csv_filename)) {

			// default options
			$adapter = array(
				'lenght' => 0,
				'delimiter' => ',',
				'enclosure' => '"',
				'escape' => '\\'
			);

			$translation_key = '';
			$this->languages = array();

			$csv_file = @fopen($csv_filename, 'rb');
			if ($csv_file) {

				// go through every row in csv file
				while (($data = fgetcsv($csv_file, $adapter['lenght'], $adapter['delimiter'], $adapter['enclosure'])) !== false) {
					
					// if this is the first row, get the languages names
					if (!count($this->languages)) {
						foreach ($data AS $key => $language) {
							if ($key > 0) {
								$this->languages[$key] = $language;
							}
						}
					}
					else {
						
						// if the first cell in the row is not empty
						if ($data[0]) {
							// for every column
							foreach ($data AS $key => $language) {
								// first column
								if ($key == 0) {
									$translation_key = $language;
								}
								else {
									$this->translation[$translation_key][$this->languages[$key]] = $language;
								}
							}
						}
					}
				}
			}
		}
	}
	
	public function translate($text, $language = '') {
		$phrase = $this->get_phrase($text);

		if (!$phrase) {
			return $text;
		}

		if (!$language) {
			$language = LANGUAGE;
		}
		
		$translation = $this->get_translation($language, $phrase);

		if ($translation) {
			return $translation;
		}

		return $text;
	}
	
	private function get_phrase($text) {
		if (isset($this->translation[$text]))
			return $this->translation[$text];
		return null;
	}
	
	private function get_translation($language, $phrase) {

		if (isset($phrase[$language]))
			return $phrase[$language];

		if (preg_match('/[a-zA-Z]{2}_[a-zA-Z]{2}/', $language)) {
			$language = preg_replace('/_[a-zA-Z]{2}/', '', $language);
			return $this->get_translation($language, $phrase);
		}

		foreach ($phrase AS $key => $text) {
			if (preg_match('/' . $language . '_[a-zA-Z]{2}/', $key))
				return $text;
		}

		if (isset($phrase[$language]))
			return $phrase[$language];

		return null;
	}

}