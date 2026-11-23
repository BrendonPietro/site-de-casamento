<?php

namespace App\View\Components\Site\Content;

use App\Helpers\FileHelper;
use App\Helpers\DateHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Artista;
use App\Models\DiaPromocao;
use App\Models\Evento;
use Carbon\Carbon;

class Agenda extends Component
{
    public $events;

	/**
     * Create a new component instance.
     */
    public function __construct(
		$events = null
	){
		// If the component doesn't have this attribute set, then use the content from the database:
        if (empty($events))
		{
            // Retrieve, from the callendar, the exact date and time of the next seven days:
			$currentWeek = $this->getCurrentWeek();

			// For each day of the week...
			foreach($currentWeek as $weekDay)
			{
				// Extract the current weekday name to a slug reference:
				$weekDaySlug = DateHelper::getWeekDayName($weekDay, "slug-reduced");

				// ...Select all the cast related data from database that has an event scheduled for such date:
				$eventsDb 	= Evento::
							with('artista')
							->where('dt_evento', 'LIKE', "%$weekDay%")
							->orderBy('dt_evento', 'ASC')
							->limit(2)
							->get();

				if(isset($eventsDb[0]))
				{
					// Configure the image aspect ratio based on how many different events are scheduled on the same day:
					$aspectRatio = ((isset($eventsDb[1])) ? "2:3" : "16:9");

					// Uses regular expression to extract the first paragraph of the event's bio:
					// preg_match('/<p>(.*?)<\/p>/', $eventsDb[0]->artista->blob_bio, $bio);

					// Gera parametro de controle de cache das thumbs
					$cacheUrlParamArtist  = FileHelper::getCacheUrlParam($eventsDb[0]->artista->dt_thumb_atualizado ?? '');
					$cacheUrlParamArtist2 = FileHelper::getCacheUrlParam($eventsDb[1]->artista->dt_thumb_atualizado ?? '');

                    $eventDeals = $this->getEventDeals($weekDaySlug);

					// Organize the results into arrays:
					$this->events[] = [
						'img'				=> FileHelper::getAspectRatioThumb(Artista::getImagePath() . $eventsDb[0]->artista->str_imagem, $aspectRatio) . $cacheUrlParamArtist,
						'date'				=> date("Y-m-d", strtotime($eventsDb[0]->dt_evento)),
						'dateSlug'			=> date("d-m-Y", strtotime($eventsDb[0]->dt_evento)),
						'categories'		=> explode(',', $eventsDb[0]->artista->str_estilos),
						'fontColor'			=> $eventsDb[0]->artista->str_cor,
						'eventName'         => $eventsDb[0]->artista->str_nome,
						'eventLink'         => $eventsDb[0]->artista->str_slug,
						'eventDesc'      	=> $bio[0] ?? NULL,
						'eventHour'         => Carbon::parse($eventsDb[0]->dt_evento)->format('H\Hi'),
						'promo' 			=> $eventDeals['description'],
                        'condition'		    => $eventDeals['condition'],

						// Conditional parameters:
						'cohostImg'			=> (isset($eventsDb[1])) ? FileHelper::getAspectRatioThumb(Artista::getImagePath() . $eventsDb[1]->artista->str_imagem, $aspectRatio) . $cacheUrlParamArtist2 : false,
						'cohostCategories'	=> (isset($eventsDb[1])) ? explode(',', $eventsDb[1]->artista->str_estilos) : false,
						'cohostFontColor'	=> (isset($eventsDb[1])) ? $eventsDb[1]->artista->str_cor : false,
						'cohostName'		=> (isset($eventsDb[1])) ? $eventsDb[1]->artista->str_nome : false,
						'cohostHour'		=> (isset($eventsDb[1])) ? Carbon::parse($eventsDb[1]->dt_evento)->format('H\Hi') : false,
					];
				}

				else continue;
			}

			// If there are no events scheduled for the next seven days, then set the variable as an empty array{
			if(empty($this->events)){
				$this->events = [];
			}
        }

        // If it does, then treat the array to use non-dynamic steps to render the content:
        else {
            $this->events = $events;
        }
    }

    /**
     * Get the details of the next seven days of the week;
     */
    private function getCurrentWeek()
    {
        $week = collect(range(0, 6))->map(function($day) {
			return now()->addDays($day)->format('Y-m-d');
		});

		return $week;
    }

	/**
     * Get all the event deals available for the selected day:
     */
	private function getEventDeals($weekDaySlug)
	{
		// Select all the deals related data from database:
		$dealsDb 	= DiaPromocao::
					with(['promocoes' => function($query) {
						$query->where('flag_status', 'A')->orderBy('int_ordem', 'ASC');
					}])
					->where('str_dias_semana', "LIKE", "%$weekDaySlug%")
					->get();

		$promoArray = [];
		foreach($dealsDb as $dealDays)
		{
            $condition = $dealDays->str_condicao;

			foreach($dealDays->promocoes as $deal)
			{
				if(isset($deal->str_titulo_curto)) $promoArray[] = $deal->str_titulo_curto;
				else continue;
			}
		}

		$dealsDay = (!empty($promoArray)) ? implode(' + ', $promoArray) : false;

        return [
            'condition'	=> $condition,
            'description' => $dealsDay,
        ];
	}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.site.content.agenda');
    }
}
