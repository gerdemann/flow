<?php
declare(ENCODING = 'utf-8');

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package FLOW3
 * @subpackage Cache
 * @version $Id: $
 */

/**
 * A caching backend which stores cache entries in files
 *
 * @package FLOW3
 * @subpackage Cache
 * @version $Id:T3_FLOW3_AOP_Framework.php 201 2007-03-30 11:18:30Z robert $
 * @copyright Copyright belongs to the respective authors
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 */
class T3_FLOW3_Cache_Backend_File extends T3_FLOW3_Cache_AbstractBackend {

	/**
	 * @var string Directory where the cache files are stored
	 */
	protected $filesDirectory;

	/**
	 * Sets the directory where the cache files are stored.
	 *
	 * @param string $filesDirectory: The directory
	 * @return void
	 * @throws T3_FLOW3_Cache_Exception if the directory does not exist, is not writable or could not be created.
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setFilesDirectory($filesDirectory) {
		if (!is_writable($filesDirectory)) {
			T3_FLOW3_Utility_Files::createDirectoryRecursively($filesDirectory);
		}

		if (!is_dir($filesDirectory)) throw new T3_FLOW3_Cache_Exception('The directory "' . $filesDirectory . '" does not exist.', 1203965199);
		if (!is_writable($filesDirectory)) throw new T3_FLOW3_Cache_Exception('The directory "' . $filesDirectory . '" is not writable.', 1203965200);
		$this->filesDirectory = $filesDirectory;
	}

	/**
	 * Saves data in a cache file.
	 *
	 * @param string $data
	 * @param string $identifier
	 * @param array $tags: Tags to associate with this cache entry
	 * @param integer $lifetime: Lifetime of this cache entry in seconds. If NULL is specified, the default lifetime is used. "0" means unlimited liftime.
	 * @return void
	 * @throws T3_FLOW3_Cache_Exception if the directory does not exist or is not writable
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function save($data, $identifier, $tags = array(), $lifetime = NULL) {
		$expiryTime = new DateTime('now +1 week', new DateTimeZone('UTC'));
		$dataHash = sha1($data);
		$path = $this->filesDirectory . '/' . $dataHash{0} . '/' . $dataHash {1} . '/';
		$filename = $this->renderCacheFilename($identifier, $expiryTime, $dataHash);

		if (!is_writable($path)) {
			T3_FLOW3_Utility_Files::createDirectoryRecursively($path);
			if (!is_writable($path)) throw new T3_FLOW3_Cache_Exception('The cache directory "' . $path . '" could not be created.', 1204026250);
		}

		$temporaryFilename = $filename . '.' . uniqid() . '.temp';
		$result = @file_put_contents($path . $temporaryFilename, $data);
		if ($result === FALSE) throw new T3_FLOW3_Cache_Exception('The temporary cache file "' . $temporaryFilename . '" could not be written.', 1204026251);
		for ($i=0; $i<5; $i++) {
			$result = rename($path . $temporaryFilename, $path . $filename);
			if ($result === TRUE) break;
		}
	}

	/**
	 * Renders a file name for the specified cache entry
	 *
	 * @param string $identifier: Identifier for the cache entry
	 * @param DateTime $expiry: Date and time specifying the expiration of the entry. Must be a UTC time.
	 * @param string $dataHash: 40 bytes hexadecimal SHA1 hash of the data to be stored
	 * @return string Filename of the cache data file
	 * @author Robert Lemke <robert@typo3.org>
	 */
	protected function renderCacheFilename($identifier, DateTime $expiryTime, $dataHash) {
		$filename = $expiryTime->format('Y-m-d\TH\;i\;s\Z') . '_' . $identifier . '_' . $dataHash . '.cachedata';
		return $filename;
	}
}
?>