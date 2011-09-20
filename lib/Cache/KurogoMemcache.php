<?php
class KurogoMemcache extends KurogoCache {
	private $mem;
	private $compressed;

	public function init($args) {
		$this->mem = new Memcache;
		if(!isset($args['HOST'])) {
			throw new KurogoConfigurationException("Memcache host is not defined");
		}else {
			$host = $args['HOST'];
		}
		if(!isset($args['PORT'])) {
			throw new KurogoConfigurationException("Memcache port is not defined");
		}else {
			$port = $args['PORT'];
		}
		if(!isset($args['PERSISTENT'])) {
			$persistent = true;
		}else {
			$persistent = (boolean) $args['PERSISTENT'];
		}
		if(!isset($args['WEIGHT'])) {
			$weight = 10;
		}else {
			$weight = (int) $args['WEIGHT'];
		}
		if(!isset($args['TIMEOUT'])) {
			$timeout = 1;
		}else {
			$timeout = (int) $args['TIMEOUT'];
		}
		if(isset($args['COMPRESSED'])) {
			$this->setCompressed($args['COMPRESSED']);
		}else {
			$this->setCompressed(true);
		}
		if(isset($args['DEBUG'])) {
			$this->setDebug($args['DEBUG']);
		}else {
			$this->setDebug(false);
		}
		$this->mem->addServer($host, $port, $persistent, $weight, $timeout);
	}

	public function setDebug($debug) {
		if($debug) {
			memcache_debug(true);
		}else {
			memcache_debug(false);
		}
	}

	public function setCompressed($compressed) {
		if($compressed) {
			$this->compressed = MEMCACHE_COMPRESSED;
		}else {
			$this->compressed = false;
		}
	}

	public function get($key) {
		return $this->mem->get($key);
	}

	public function set($key, $value, $ttl = 0) {
		return $this->mem->set($key, $value, $this->compressed, $ttl);
	}

	public function delete($key) {
		return $this->men->delete($key);
	}

	public function add($key, $value, $ttl = 0) {
		return $this->mem->add($key, $value, $this->compressed, $ttl);
	}
}