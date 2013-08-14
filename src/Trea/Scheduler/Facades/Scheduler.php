<?php
namespace Trea\Scheduler\Facades;
use Illuminate\Support\Facades\Facade;

class Scheduler extends Facade {
	protected static function getFacadeAccessor() { return 'scheduler'; }
}