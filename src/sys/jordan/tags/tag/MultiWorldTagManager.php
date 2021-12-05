<?php

declare(strict_types=1);

namespace sys\jordan\tags\tag;


use pocketmine\Player;
use pocketmine\utils\Config;

class MultiWorldTagManager {

	private TagFactory $factory;
	private bool $enabled;
	/** @var string[] */
	private array $tags;

	public function __construct(TagFactory $factory, Config $config) {
		$this->factory = $factory;
		$this->enabled = $config->getNested("multi-world.enabled", false);
		$this->tags = array_map(
			function ($tag): string { return is_array($tag) ? implode("\n", $tag) : $tag; },
			$config->getNested("multi-world.worlds", [])
		);
	}

	public function isEnabled(): bool {
		return $this->enabled;
	}

	public function getFactory(): TagFactory {
		return $this->factory;
	}

	/**
	 * @return string[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	public function getTag(string $key): ?string {
		return $this->tags[$key] ?? null;
	}

	public function hasTag(string $key): bool {
		return isset($this->tags[$key]);
	}

	public function getTagForLevel(Player $player): string {
		return ($this->isEnabled() && $player->isValid() && $this->hasTag($player->getLevel()->getFolderName())) ?
			$this->getTag($player->getLevel()->getFolderName()) :
			$this->getFactory()->getTagString();
	}

}