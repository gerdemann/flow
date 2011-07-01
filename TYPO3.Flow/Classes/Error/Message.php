<?php
namespace F3\FLOW3\Error;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 */

/**
 * An object representation of a generic message. Usually, you will use Error, Warning or Notice instead of this one.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class Message {

	/**
	 * The message text
	 * @var string
	 */
	protected $message = '';

	/**
	 * The message arguments. Will be replaced in the message body
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * @param string $message message text
	 * @param array $arguments Arguments that need to be replaced in the message
	 * @param string $title optional message title
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Christian Müller <christian.mueller@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function __construct($message, array $arguments = array()) {
		$this->message = $message;
		$this->arguments = $arguments;
	}

	/**
	 * @return string the message text
	 * @author Andreas Förthner <andreas.foerthner@netlogix.de>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return array the message arguments
	 * @author Christian Müller <christian.mueller@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * Returns the message as string by replacing any arguments using sprintf()
	 *
	 * @return string
	 * @author Christian Müller <christian.mueller@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render() {
		if ($this->arguments !== array()) {
			return vsprintf($this->message, $this->arguments);
		} else {
			return $this->message;
		}
	}

	/**
	 * Converts this error into a string
	 *
	 * @return string
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Christian Müller <christian.mueller@typo3.org>
	 * @api
	 */
	public function __toString() {
		return $this->render();
	}
}

?>