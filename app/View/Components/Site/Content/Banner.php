<?php

namespace App\View\Components\Site\Content;

use App\Helpers\FileHelper;
use App\Models\Banner as ModelsBanner;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Component;

class Banner extends Component
{
    public $slides;
	public $hasVite = false;
	public $hasTracker = false;

	/**
     * Create a new component instance.
     */
    public function __construct(
		$slides = null,
	){
		return false;
        // If the component doesn't have this attribute set, then use the content from the database:
		if(empty($slides))
		{
			// Select all the banners related data from database:
			$bannerDb 	= ModelsBanner::
						where('flag_status', 'A')
						->where('dt_publicacao', '<=', date('Y-m-d H:i:s'))
						->where(function($query){
							$query->where('dt_expiracao', '>', date('Y-m-d H:i:s'));
							$query->orWhere('flag_expiracao', 'N');
							$query->orWhereNull('dt_expiracao');
						})
						->orderBy('int_ordem', 'ASC')
						->get();

			// Organize the results into arrays:
			foreach($bannerDb as $slide)
			{
				// Gera parametro de controle de cache das thumbs
				$cacheUrlParam = FileHelper::getCacheUrlParam($slide->dt_thumb_atualizado ?? '');

				$this->slides[] = [
					'img'				=> ModelsBanner::getImagePath() . $slide->str_imagem_desktop,
					'img-1/1'			=> FileHelper::getAspectRatioThumb(ModelsBanner::getImagePath() . $slide->str_imagem_desktop, '1:1') . $cacheUrlParam,
					'img-2/3'			=> FileHelper::getAspectRatioThumb(ModelsBanner::getImagePath() . $slide->str_imagem_desktop, '2:3') . $cacheUrlParam,
					'img-16/9'			=> FileHelper::getAspectRatioThumb(ModelsBanner::getImagePath() . $slide->str_imagem_desktop, '16:9') . $cacheUrlParam,
					'subtitle'			=> $slide->str_titulo,
					'title'				=> $slide->str_titulo2,
					'bloom'				=> 'default',
					'description'		=> $slide->str_descricao,
					'link'				=> $slide->str_link,
                    'target'			=> $slide->str_target_link,
                    'direction'			=> $slide->str_posicao_texto
				];
			}

			if (count($this->slides) > 1) $this->hasTracker = true;
		}

		// If it does, then treat the array to use non-dynamic steps to render the content:
		else{
			$this->slides = $slides;
			$this->hasVite = true;
			
			// Replace image paths with Vite asset paths:
			foreach($this->slides as $key => $slide)
			{
				$slide['img-1/1'] 	= Vite::asset(FileHelper::getAspectRatioThumb($slide['img'], '1:1'));
				$slide['img-2/3'] 	= Vite::asset(FileHelper::getAspectRatioThumb($slide['img'], '2:3'));
				$slide['img-16/9'] 	= Vite::asset(FileHelper::getAspectRatioThumb($slide['img'], '16:9'));
				$this->slides[$key] = $slide;
			}

			if (count($this->slides) > 1) $this->hasTracker = true;
		}
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.site.content.banner');
    }
}
