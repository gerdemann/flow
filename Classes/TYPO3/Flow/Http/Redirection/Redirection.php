<?php
namespace TYPO3\Flow\Http\Redirection;

/*
 * This file is part of the TYPO3.Flow package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Response;

/**
 * A Redirection model that represents a HTTP redirect
 *
 * @see RedirectionService
 *
 * @Flow\Entity
 */
class Redirection
{
    /**
     * Relative URI path for which this redirect should be triggered
     *
     * @var string
     * @ORM\Column(length=500)
     * @Flow\Identity
     */
    protected $sourceUriPath;

    /**
     * Target URI path to which a redirect should be pointed
     *
     * @var string
     * @ORM\Column(length=500)
     */
    protected $targetUriPath;

    /**
     * Status code to be send with the redirect header
     *
     * @var integer
     * @Flow\Validate(type="NumberRange", options={ "minimum"=100, "maximum"=599 })
     */
    protected $statusCode;

    /**
     * @param string $sourceUriPath relative URI path for which a redirect should be triggered
     * @param string $targetUriPath target URI path to which a redirect should be pointed
     * @param integer $statusCode status code to be send with the redirect header
     */
    public function __construct($sourceUriPath, $targetUriPath, $statusCode = 301)
    {
        $this->sourceUriPath = $sourceUriPath;
        $this->targetUriPath = $targetUriPath;
        $this->statusCode = $statusCode;
    }

    /**
     * @param string $targetUriPath
     * @param integer $statusCode
     */
    public function update($targetUriPath, $statusCode)
    {
        $this->targetUriPath = $targetUriPath;
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getSourceUriPath()
    {
        return $this->sourceUriPath;
    }

    /**
     * @param string $targetUriPath
     * @return void
     */
    public function setTargetUriPath($targetUriPath)
    {
        $this->targetUriPath = $targetUriPath;
    }

    /**
     * @return string
     */
    public function getTargetUriPath()
    {
        return $this->targetUriPath;
    }

    /**
     * @param integer $statusCode
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusLine()
    {
        return sprintf('HTTP/1.1 %d %s', $this->statusCode, Response::getStatusMessageByCode($this->statusCode));
    }
}
