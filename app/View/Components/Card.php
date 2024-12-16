<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Título ou cabeçalho do card.
     *
     * @var string
     */
    public $header;

    /**
     * Cria uma nova instância do componente.
     *
     * @param string $header
     */
    public function __construct($header)
    {
        $this->header = $header;
    }

    /**
     * Renderiza a view do componente.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
