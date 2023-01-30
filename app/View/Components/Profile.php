<?php

namespace App\View\Components;

use App\Models\Save;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\Component;

class Profile extends Component
{
    public int $num;
    public \App\Enums\Components\Profile $type;
    public string $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(?string $type = null, ?string $num = null, string $class = '')
    {
        if($num === null) {
            $this->num = Auth::getSession()->get('save', 0);
        } else {
            $this->num = intval($num);
        }

        if(empty($type)) {
            $this->type = \App\Enums\Components\Profile::PRIMARY();
        } else {
            $this->type = \App\Enums\Components\Profile::coerce($type);
        }

        if(strlen($class) !== 0) $class = ' ' . $class;

        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $save = Auth::user()->saves()->where('num', '=', $this->num)->get(['avatar', 'nickname', 'badges', 'money'])->first();

        if(empty($save)) {
            $save = Save::factory()->make();
        }

        $save->nickname = $save->nickname ?? 'Satoshi';

        return view('components.profile', ['num' => $this->num, 'avatar' => $save->avatar, 'name' => $save->nickname, 'badges' => $save->badges, 'money' => $save->money, 'class' => $this->class]);
    }
}
