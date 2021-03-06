<?php
/**
 * WatchTower Authentication system
 *
 * @license   MIT License
 * @author    David Lundgren
 * @link      http://dlundgren.github.io/watchtower
 * @copyright 2015. David Lundgren
 */
namespace WatchTower\Test\Sentry\Identification\Stealth;

use WatchTower\Sentry\Identification\Stealth\PhpSession;
use WatchTower\Event\Identify;
use WatchTower\Identity\GenericIdentity;

/**
 * Unit tests for the PhpSession Identification adapter
 *
 * @package WatchTower\Identification\Adapter\Transparent
 */
class PhpSessionTest
	extends \PHPUnit_Framework_TestCase
{
	private function buildIdentifyEvent()
	{
		return new Identify(new GenericIdentity(null));
	}


	public function testNameReturns()
	{
		$i = new PhpSession('ps-test', 'a');
		self::assertEquals('ps-test', $i->name());
	}

	public function testDiscernIgnoresNonIdentifyEvents()
	{
		$imap = new PhpSession('ip-test', []);
		$e    = $this->getMock('WatchTower\Event\AbstractEvent', ['discern']);
		$e->expects($this->never())->method('discern')->willThrowException(new \Exception('Should not call'));
		$imap->discern($e);
	}

	public function testDiscernWithoutSession()
	{
		$i = new PhpSession('ps-test', '__auth__');
		$e = $this->buildIdentifyEvent();
		$i->discern($e);
		self::assertFalse($e->identity()->isIdentified());
	}

	public function testDiscernUsesSession()
	{
		if (session_status() === PHP_SESSION_NONE) {
			@session_start();
		}
		$_SESSION['__auth__']['identity'] = 'super';
		$i = new PhpSession('ps-test', '__auth__');
		$e = $this->buildIdentifyEvent();
		$i->discern($e);
		self::assertTrue($e->identity()->isIdentified());
		self::assertEquals('super', $e->identity()->identified());
	}
}
