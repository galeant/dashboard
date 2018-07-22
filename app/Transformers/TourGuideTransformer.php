<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\TourGuide;

class TourGuideTransformer extends TransformerAbstract
{
    /**
     * @return  array
     */
    public function transform(TourGuide $tourGuide)
    {
        return [
            'id'         => (int) $tourGuide->id,
            'salutation' => (string)$tourGuide->salutation,
            'fullname'   => (string)$tourGuide->fullname.' ('.$tourGuide->age.')',
            'experience_year' => (string) $user->experience_year.' year(s)',
            'language' => (string)$user->language,
            'status' =>  (string)($tourGuide->status == 1 ? 'active':'inactive')
        ];
    }
}