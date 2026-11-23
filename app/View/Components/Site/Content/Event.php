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
use Exception;

class Event extends Component
{
    public $event;

	/**
     * Create a new component instance.
     */
    public function __construct(
		$event = null
	){
		// If the component doesn't have this attribute set, then use the content from the database:
        if (empty($event))
		{
			if(empty(request('data'))){
            	// Retrieve, from the callendar, the current day:
				$eventDate = now()->format('Y-m-d 00:00:00');
			}else{
				try{
					$eventDate = Carbon::createFromFormat('d-m-Y', request('data') )->format('Y-m-d 00:00:00');
				}
				catch(Exception){
					return false;
				}
			}

			// Extract the current weekday name to a slug reference:
			$weekDaySlug = DateHelper::getWeekDayName($eventDate, "slug-reduced");

			// ...Select all the cast related data from database that has an event scheduled for such date:
			$eventsDb = Evento::
						with('artista')
						->where('dt_evento', '>=', "$eventDate")
						->orderBy('dt_evento', 'ASC')
						->limit(2)
						->get();

			if(isset($eventsDb[1]))
			{
				// Compare if the event data of the second event is in the same date as the first one:
				if(date("Y-m-d", strtotime($eventsDb[0]->dt_evento)) !== date("Y-m-d", strtotime($eventsDb[1]->dt_evento)))
                {
                    // If the second event is in a different date, then remove it from the results:
                    unset($eventsDb[1]);
                }
			}

			if(isset($eventsDb[0]))
			{
				// Gera parametro de controle de cache das thumbs
				$cacheUrlParamArtist  = FileHelper::getCacheUrlParam($eventsDb[0]->artista->dt_thumb_atualizado ?? '');
				$cacheUrlParamArtist2 = FileHelper::getCacheUrlParam($eventsDb[1]->artista->dt_thumb_atualizado ?? '');

				// Configure the image aspect ratio based on how many different events are scheduled on the same day:
				$aspectRatio = ((isset($eventsDb[1])) ? "2:3" : "1:1");

				// Uses regular expression to extract the first paragraph of the event's bio:
				preg_match('/<p(.*?)<\/p>/', $eventsDb[0]->artista->blob_bio, $bio);

				if(isset($eventsDb[1])) preg_match('/<p>(.*?)<\/p>/', $eventsDb[1]->artista->blob_bio, $bio2);

                $eventDeals = $this->getEventDeals($weekDaySlug);

				// Organize the results into arrays:
				$this->event = [
					'img'				=> FileHelper::getAspectRatioThumb(Artista::getImagePath() . $eventsDb[0]->artista->str_imagem, $aspectRatio) . $cacheUrlParamArtist,
					'date'				=> date("Y-m-d", strtotime($eventsDb[0]->dt_evento)),
					'categories'		=> explode(',', $eventsDb[0]->artista->str_estilos),
					'fontColor'			=> $eventsDb[0]->artista->str_cor,
					'eventName'         => $eventsDb[0]->artista->str_nome,
				    'eventType'			=> $eventsDb[0]->artista->flag_tipo ?? 'B',
					'eventLink'         => $eventsDb[0]->artista->str_slug,
					'eventDesc'         => $bio[0] ?? null,
					'eventHour'         => Carbon::parse($eventsDb[0]->dt_evento)->format('H\Hi'),
					'promos' 			=> $eventDeals['deals'],
                    'condition'		    => $eventDeals['condition'],
					'socials'			=> $this->getSocials($eventsDb[0]),

					// Conditional parameters:
					'cohostImg'			=> (isset($eventsDb[1])) ? FileHelper::getAspectRatioThumb(Artista::getImagePath() . $eventsDb[1]->artista->str_imagem, $aspectRatio) . $cacheUrlParamArtist2 : false,
					'cohostCategories'	=> (isset($eventsDb[1])) ? explode(',', $eventsDb[1]->artista->str_estilos) : false,
					'cohostFontColor'	=> (isset($eventsDb[1])) ? $eventsDb[1]->artista->str_cor : false,
					'cohostName'		=> (isset($eventsDb[1])) ? $eventsDb[1]->artista->str_nome : false,
					'cohostType'		=> (isset($eventsDb[1])) ? $eventsDb[1]->artista->flag_tipo : false,
					'cohostLink'		=> (isset($eventsDb[1])) ? $eventsDb[1]->artista->str_slug : false,
					'cohostDesc'		=> $bio2[0] ?? false,
					'cohostHour'		=> (isset($eventsDb[1])) ? Carbon::parse($eventsDb[1]->dt_evento)->format('H\Hi') : false,
					'cohostSocials'		=> (isset($eventsDb[1])) ? $this->getSocials($eventsDb[1]) : false,
				];
			}

			// If there are no events scheduled for today, then set the variable as an empty array{
			if(empty($this->event)){
				$this->event = [];
			}
        }

        // If it does, then treat the array to use non-dynamic steps to render the content:
        else {
            $this->event = $event;
        }
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

		$dealsDay = (!empty($promoArray)) ? $promoArray : false;

        return [
            'condition'	=> $condition ?? false,
            'deals' => $dealsDay,
        ];
	}

	/**
     * Get the view / contents that represent the component.
     */
    private function getSocials($cast)
    {
		// Organize the socials array:
        $socialsName = explode(',', $cast->artista->str_redes_nomes);
        $socialsLinks = explode(',', $cast->artista->str_redes_links);

        // Create an array with the social media icons and links:
        $socialsArr = [];

        foreach($socialsName as $key => $name)
        {
            if($name != null) $socialsArr[$name] = $socialsLinks[$key];
        }

        return $socialsArr;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.site.content.event');
    }
}
