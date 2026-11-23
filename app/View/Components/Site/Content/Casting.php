<?php

namespace App\View\Components\Site\Content;

use App\Helpers\FileHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Component;
use App\Models\Artista;

class Casting extends Component
{
    public $casting;

	/**
     * Create a new component instance.
     */
    public function __construct(
		$casting = null
	){
        // If the component doesn't have this attribute set, then use the content from the database:
		if(empty($casting))
		{
			// Select all the cast related data from database:
			$castingDb 	= Artista::
						where('flag_casting', 'A')
						->orderBy('str_nome', 'ASC')
						->get();

			// Organize the results into arrays:
			foreach($castingDb as $casting)
			{
				// Gera parametro de controle de cache das thumbs
				$cacheUrlParam = FileHelper::getCacheUrlParam($casting->dt_thumb_atualizado ?? '');

				$this->casting[] = [
					'img'				=> Artista::getImagePath() . $casting->str_imagem,
					'cacheUrlParam'		=> $cacheUrlParam,
					'categories'		=> explode(',', $casting->str_estilos,),
					'fontColor'			=> $casting->str_cor,
					'eventName'         => $casting->str_nome,
					'eventLink'         => $casting->str_slug,
                    'eventDesc'         => $casting->str_descricao,
                    'socials'         	=> $this->getSocials($casting)
				];
			}
		}

		// If it does, then treat the array to use non-dynamic steps to render the content:
		else{
			$this->casting = $casting;

			// Replace image paths with Vite asset paths:
			foreach($this->casting as $key => $casting)
			{
				$casting['img'] = Vite::asset($casting['img']);
				$this->casting[$key] = $casting;
			}
		}
    }

    /**
     * Get the view / contents that represent the component.
     */
    private function getSocials($cast)
    {

		// Organize the socials array:
        $socialsName = explode(',', $cast->str_redes_nomes);
        $socialsLinks = explode(',', $cast->str_redes_links);

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
        return view('components.site.content.casting');
    }
}
