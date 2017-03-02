<?php

/**
 * SimpleFramework
 * The fast, light-weighted, easy-to-extend php framework.
 *
 * Some classes are based on project PocketMine-MP.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PeratX
 */

namespace PeratX\SimpleFramework\Module;

class ModuleInfo{
	const LOAD_METHOD_PACKAGE = 0;
	const LOAD_METHOD_SOURCE = 1;

	const LOAD_ORDER_MIN = 0;
	const LOAD_ORDER_MAX = 9;

	private $name;
	private $main;
	private $api;
	private $version;
	private $description = null;
	private $authors = [];
	private $website = null;
	private $loadMethod;
	private $loadOrder = self::LOAD_ORDER_MIN;
	private $dependency = [];

	public function __construct(string $info, int $loadMethod){
		$this->loadMethod = $loadMethod;
		$info = json_decode($info, true);
		$this->name = preg_replace("[^A-Za-z0-9 _.-]", "", $info["name"]);
		if($this->name === ""){
			throw new \Exception("Invalid plugin name");
		}
		$this->name = str_replace(" ", "_", $this->name);
		$this->version = $info["version"];
		$this->main = $info["main"];
		$this->api = $info["api"];

		if(stripos($this->main, "sf\\") === 0){
			throw new \Exception("Invalid plugin main class.");
		}

		$this->description = $info["description"] ?? null;
		$this->authors = [];
		$this->authors[] = $info["author"] ?? [];
		if(isset($info["authors"])){
			foreach($info["authors"] as $author){
				$this->authors[] = $author;
			}
		}
		if(isset($info["order"])){
			$this->loadOrder = min(self::LOAD_ORDER_MAX, max(self::LOAD_ORDER_MIN, (int)$info["order"]));
		}
		$this->website = $info["website"] ?? null;

		$this->dependency = $info["dependency"] ?? [];
	}

	public function getDependency() : array {
		return $this->dependency;
	}

	public function getLoadMethod() : int{
		return $this->loadMethod;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getVersion() : string{
		return $this->version;
	}

	public function getDescription() : string{
		return $this->description;
	}

	public function getAuthors() : array{
		return $this->authors;
	}

	public function getAPILevel() : int{
		return $this->api;
	}

	public function getMain(){
		return $this->main;
	}

	public function getLoadOrder() : int{
		return $this->loadOrder;
	}

	public function getWebsite() : string {
		return $this->website;
	}
}