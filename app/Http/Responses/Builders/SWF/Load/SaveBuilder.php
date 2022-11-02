<?php

namespace App\Http\Responses\Builders\SWF\Load;

class SaveBuilder
{
    protected int $num;

    // All values not explicitly stated to have the save $num at a different place have the num at the end of the variable name
    // Example: Advanced would have the $num placed Advanced{$num}
    protected int $Advanced;
    // Advanced{$num}_a
    protected int $Advanced_a;
    // p{$num}_numPoke
    protected int $p_numPoke;
    protected string $Nickname;
    protected int $Badges;
    protected string $avatar;
    protected int $Classic;
    // Classic{$num}_a
    protected string $Classic_a;
    protected int $Challenge;
    protected int $Money;
    protected int $NPCTrade;
    protected int $shinyHunt;
    // p{$num}_numItem
    protected int $p_numItem;
    protected int $Version;
    protected int $HMI;
    protected int $p_hs;

    protected array $pokemon = [];
    protected array $items = [];

    public static function new($saveNum): self
    {
        $new = new self();
        $new->num = $saveNum;

        return $new;
    }

    /**
     * @param int $advanced
     */
    public function setAdvanced(int|null $advanced): self
    {
        $this->Advanced = $advanced ?? 0;
        return $this;
    }

    /**
     * @param int $advancedA
     */
    public function setAdvancedA(int|null $advancedA): self
    {
        $this->Advanced_a = $advancedA ?? 0;
        return $this;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string|null $nickname): self
    {
        $this->Nickname = $nickname ?? 'Satoshi';
        return $this;
    }

    /**
     * @param int $badges
     */
    public function setBadges(int|null $badges): self
    {
        $this->Badges = $badges ?? 0;
        return $this;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string|null $avatar): self
    {
        $this->avatar = $avatar ?? '';
        return $this;
    }

    /**
     * @param int $classic
     */
    public function setClassic(int|null $classic): self
    {
        $this->Classic = $classic ?? 0;
        return $this;
    }

    /**
     * @param string $classicA
     */
    public function setClassicA(string|null $classicA): self
    {
        $this->Classic_a = $classicA ?? '';
        return $this;
    }

    /**
     * @param int $challenge
     */
    public function setChallenge(int|null $challenge): self
    {
        $this->Challenge = $challenge ?? 0;
        return $this;
    }

    /**
     * @param int $money
     */
    public function setMoney(int|null $money): self
    {
        $this->Money = $money ?? 0;
        return $this;
    }

    /**
     * @param int $npcTrade
     */
    public function setNPCTrade(int|null $npcTrade): self
    {
        $this->NPCTrade = $npcTrade ?? 0;
        return $this;
    }

    /**
     * @param int $shinyHunt
     */
    public function setShinyHunt(int|null $shinyHunt): self
    {
        $this->shinyHunt = $shinyHunt ?? 0;
        return $this;
    }

    /**
     * @param int $pNumItem
     */
    public function setPNumItem(int|null $pNumItem): self
    {
        $this->p_numItem = $pNumItem ?? 0;
        return $this;
    }

    /**
     * @param int $version
     */
    public function setVersion(int|null $version): self
    {
        $this->Version = $version ?? 1;
        return $this;
    }

    /**
     * @param PokemonBuilder $pokemonBuilder
     */
    public function addPokemon(PokemonBuilder $pokemonBuilder): self
    {
        $this->pokemon[] = $pokemonBuilder;
        return $this;
    }

    /**
     * @param int $item
     */
    public function addItem(int $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    private function getKey($key): string
    {
        return match($key) {
            'Advanced_a' => "Advanced{$this->num}_a",
            'p_numPoke' => "p{$this->num}_numPoke",
            'Classic_a' => "Classic{$this->num}_a",
            'p_numItem' => "p{$this->num}_numItem",
            'p_hs' => "p{$this->num}_hs",
            default => "{$key}{$this->num}"
        };
    }


    /**
     * I use the variable names for the keys, so I will rename the variables to the dynamic key names
     * before returning the object on create (and this can and only will be called by create when
     * the builder is finalized
     *
     * @return void
     */
    private function prepareVariableKeyNames(): array
    {
        $arrays = [];
        $numOfShinies = 0;

        foreach ($this->pokemon as $pokemon) {
            if(!($pokemon instanceof PokemonBuilder)) continue;
            if($pokemon->getShiny() === 1) $numOfShinies++;

            $arrays = array_merge($arrays, $pokemon->create());
        }

        $this->p_numPoke = sizeof($this->pokemon);

        unset($this->pokemon);

        $this->HMI = sizeof($this->items);

        for($i = 0; $i < $this->HMI; $i++) {
            $itemNum = $i + 1;
            $item = $this->items[$i];

            $itemKey = "p{$this->num}_item_{$itemNum}_num";
            $this->$itemKey = $item;
        }

        unset($this->items);

        foreach (get_object_vars($this) as $key => $value) {
            if($key === 'num') continue;

            $newKey = $this->getKey($key);
            $this->$newKey = $value;
            unset($this->$key);
        }

        $pHsKey = "p{$this->num}_hs";
        $this->$pHsKey = $numOfShinies;

        unset($this->num);

        return array_merge(get_object_vars($this), $arrays);
    }

    public function create()
    {
        return $this->prepareVariableKeyNames();
    }
}
