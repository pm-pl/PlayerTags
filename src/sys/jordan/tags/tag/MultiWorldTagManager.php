<?php


namespace sys\jordan\tags\tag;


use pocketmine\Player;
use pocketmine\utils\Config;
use function array_key_exists;

class MultiWorldTagManager {

	private TagFactory $factory;
	private bool $enabled;
	/** @var string[] */
	private array $tags;

	public function __construct(TagFactory $factory, Config $config) {
		$this->factory = $factory;
		$this->enabled = $config->getNested("multi-world.enabled", false);
		$this->tags = $config->getNested("multi-world.worlds", []);
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
		return array_key_exists($key, $this->tags);
	}

	public function getTagForLevel(Player $player): string {
		return ($this->isEnabled() && $player->isValid() && $this->hasTag($player->getLevel()->getFolderName())) ? $this->getTag($player->getLevel()->getFolderName()) : $this->getFactory()->getTagString();
	}

}