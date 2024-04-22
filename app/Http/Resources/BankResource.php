<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'account_number' => $this->account_number,
            'IBAN_number'    => $this->IBAN_number,
            'created_at'     => $this->created_at->format('Y-m-d')
        ];
    }
}
