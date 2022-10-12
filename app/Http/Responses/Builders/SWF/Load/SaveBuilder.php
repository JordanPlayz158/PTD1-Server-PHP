<?php

namespace App\Http\Responses\Builders\SWF\Load;

use App\Models\Pokemon;

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

    protected array $pokemon;

    public static function new($saveNum): self
    {
        $new = new self();
        $new->num = $saveNum;

        return $new;
    }

    /**
     * @param int $advanced
     */
    public function setAdvanced(int $advanced): void
    {
        $this->Advanced = $advanced;
    }

    /**
     * @param int $AdvancedA
     */
    public function setAdvancedA(int $AdvancedA): void
    {
        $this->Advanced_a = $AdvancedA;
    }

    /**
     * @param int $pNumPoke
     */
    public function setPNumPoke(int $pNumPoke): void
    {
        $this->p_numPoke = $pNumPoke;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->Nickname = $nickname;
    }

    /**
     * @param int $badges
     */
    public function setBadges(int $badges): void
    {
        $this->Badges = $badges;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @param int $classic
     */
    public function setClassic(int $classic): void
    {
        $this->Classic = $classic;
    }

    /**
     * @param string $classicA
     */
    public function setClassicA(string $classicA): void
    {
        $this->Classic_a = $classicA;
    }

    /**
     * @param int $challenge
     */
    public function setChallenge(int $challenge): void
    {
        $this->Challenge = $challenge;
    }

    /**
     * @param int $money
     */
    public function setMoney(int $money): void
    {
        $this->Money = $money;
    }

    /**
     * @param int $npcTrade
     */
    public function setNPCTrade(int $npcTrade): void
    {
        $this->NPCTrade = $npcTrade;
    }

    /**
     * @param int $shinyHunt
     */
    public function setShinyHunt(int $shinyHunt): void
    {
        $this->shinyHunt = $shinyHunt;
    }

    /**
     * @param int $pNumItem
     */
    public function setPNumItem(int $pNumItem): void
    {
        $this->p_numItem = $pNumItem;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->Version = $version;
    }

    /**
     * @param array $pokemon
     */
    public function addPokemon(PokemonBuilder $pokemonBuilder): void
    {
        $this->pokemon[] = $pokemonBuilder;
    }

    private function getKey($key): string
    {
        return match($key) {
            'Advanced_a' => "Advanced{$this->num}_a",
            'p_numPoke' => "p{$this->num}_numPoke",
            'Classic_a' => "Classic{$this->num}_a",
            'p_numItem' => "p{$this->num}_numItem",
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
    private function prepareVariableKeyNames(): void
    {
        foreach ($this->pokemon as $pokemon) {
            if(!($pokemon instanceof PokemonBuilder)) continue;

            $pokemon->create();
        }

        unset($this->pokemon);


        foreach (get_object_vars($this) as $key => $value) {
            $newKey = $this->getKey($key);
            $this->$newKey = $value;
            unset($key);
        }
    }

    public function create()
    {
        $this->prepareVariableKeyNames();
        return get_object_vars($this);
    }
}
