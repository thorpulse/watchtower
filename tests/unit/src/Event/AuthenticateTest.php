<?php
/**
 * WatchTower Authentication system
 *
 * @license   MIT License
 * @author    David Lundgren
 * @link      http://dlundgren.github.io/watchtower
 * @copyright 2015. David Lundgren
 */
namespace WatchTower\Test\Event;

use WatchTower\Event\Authenticate;
use WatchTower\Identity\GenericIdentity;

/**
 * Unit tests for the Authenticate event
 *
 * @package WatchTower\Test\Event
 */
class AuthenticateTest
	extends \PHPUnit_Framework_TestCase
{
	public function testConstructorThrowsExceptionWithoutIdentity()
	{
		$this->setExpectedException('InvalidArgumentException', 'Incomplete identity, missing identity');
		new Authenticate(new GenericIdentity(null));
	}

	public function testConstructorThrowsExceptionWithoutCredential()
	{
		$this->setExpectedException('InvalidArgumentException', 'Incomplete identity, missing credential');
		new Authenticate(new GenericIdentity('hi'));
	}
}
