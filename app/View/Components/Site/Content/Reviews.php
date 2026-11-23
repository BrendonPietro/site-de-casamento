<?php

namespace App\View\Components\Site\Content;

use App\Models\Avaliacao;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;

class Reviews extends Component
{
	public $reviews;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
		// Select all the reviews related data from database:
		$reviewsDb	= Avaliacao::offset(0)
					->limit(4)
					->get();
		
		// Organize the results into arrays:
		foreach($reviewsDb as $review)
		{
			$this->reviews[] = [
				'img'			=> Avaliacao::getImagePath() . $review->str_imagem,
				'rating'	 	=> $review->int_nota,
				'source'		=> $review->str_origem,
				'reviewer'		=> $review->str_nome,
				'review'		=> $review->str_comentario,
				'link'			=> $review->str_link,
				'date' 			=> Carbon::createFromFormat('Y-m-d', $review->dt_avaliacao),
			];
		}
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.site.content.reviews');
    }
}
