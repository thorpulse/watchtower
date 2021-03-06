<?php
/**
 * WatchTower Authentication system
 *
 * @license   MIT License
 * @author    David Lundgren
 * @link      http://dlundgren.github.io/watchtower
 * @copyright 2015. David Lundgren
 */
namespace WatchTower\Test;

use WatchTower\WatchTower;
use WatchTower\Sentry;
use WatchTower\Event\Event;

/**
 * Unit tests for the WatchTower
 *
 * @package WatchTower
 */
class WatchTowerTest
	extends \PHPUnit_Framework_TestCase
{
	public function testIdentifyWithNoSentriesReturnsInvalid()
	{
		$wt = new WatchTower();
		$identity = $wt->identify('superman');

		self::assertTrue($identity->hasErrors());
		self::assertContains("No sentries available", $identity->getErrors());
		self::assertFalse($identity->isIdentified());
	}

	public function testIdentifyStopsPropagation()
	{
		$wt = new WatchTower();
		$wt->watch(new Sentry\GenericCallback('pre', function(Event $e) {
			$e->stopPropagation();
		}));
		$wt->watch(new Sentry\GenericCallback('no', function(Event $e) {
			throw new \Exception("shouldn't run this");
		}));

		$identity = $wt->identify('superman');
		self::assertFalse($identity->hasErrors());
	}

	public function testAuthenticateWithNoSentriesReturnsInvalid()
	{
		$wt = new WatchTower();
		$identity = $wt->authenticate('superman', 'lois lane');

		self::assertTrue($identity->hasErrors());
		self::assertContains("No sentries available", $identity->getErrors());
		self::assertFalse($identity->isIdentified());
	}

	public function testAuthenticateSuccess()
	{
		$wt = new WatchTower();
		$wt->watch(new Sentry\GenericCallback('pre', function(Event $e) {
			$e->stopPropagation();
		}));
		$identity = $wt->authenticate('superman', 'lois lane');

		self::assertFalse($identity->hasErrors());
		self::assertTrue($identity->isAuthenticated());
	}
}
