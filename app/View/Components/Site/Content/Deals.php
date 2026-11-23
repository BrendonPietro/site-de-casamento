<?php

namespace App\View\Components\Site\Content;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\DiaPromocao;
use App\Models\Promocao;

class Deals extends Component
{
    public $deals;

    /**
     * Create a new component instance.
     */
    public function __construct(
		$deals = null
	){
        // If the component doesn't have this attribute set, then use the content from the database:
        if (empty($deals))
		{
            // Select all the deals related data from database:
            $dealsDb 	= DiaPromocao::
						with(['promocoes' => function($query) {
                    		$query->where('flag_status', 'A')->orderBy('int_ordem', 'ASC');
                		}])
                		->where('flag_status', 'A')
                		->get();

					
			$weekdays = $this->getWeekday(false);

            // Organize the results into arrays:
			foreach( $weekdays as $weekday => $fullweekday ){

				foreach($dealsDb as $deal)
				{
					if( strpos( $deal->str_dias_semana, $weekday ) === false )
						continue;

					$this->deals[] = [
						'weekday'			=> $this->getWeekday($weekday),
						'condition'			=> $deal->str_condicao,
						'description'		=> $deal->str_descricao,
						'href'				=> DiaPromocao::getMenuHref($weekday),
						'details'			=> $this->getDetails($deal)
					];
				}
			}
        }

        // If it does, then treat the array to use non-dynamic steps to render the content:
        else {
            $this->deals = $deals;
        }
    }

	/**
     * Based on a database reference, return the actual weekday name for the deal:
     */
	private function getWeekday($reference)
	{
		$weekdays = [
			'segunda' 	=> 'Segunda-Feira',
			'terca' 	=> 'Terça-Feira',
            'quarta' 	=> 'Quarta-Feira',
            'quinta' 	=> 'Quinta-Feira',
            'sexta' 	=> 'Sexta-Feira',
            'sabado' 	=> 'Sábado',
			'domingo' 	=> 'Domingo'
		];

		if( $reference === false ){
			return $weekdays;
		}

        return $weekdays[$reference];
	}
	
	/**
     * Get all the current approved deals for the specified weekday:
     */
	private function getDetails($weekday)
	{
		$details = [];

		foreach ($weekday->promocoes as $detail)
		{
			$details[] = [
				'cover'		=> Promocao::getImagePath() . $detail->str_imagem,
				'style'    	=> $detail->str_cor,
                'label'    	=> $detail->str_titulo
			];
		}

		return $details;
	}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.site.content.deals');
    }
}
