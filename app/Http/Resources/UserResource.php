<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $showSensitiveFields = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->showSensitiveFields) {
            $this->resource->makeHidden(['phone', 'email']);
        }

        $data = parent::toArray($request);
        $data['bind_phone'] = $this->resource->phone ? true : false;
        $data['bind_wechat'] = ($this->resource->wechat_unionid || $this->resource->wechat_openid) ? true : false;

        return $data;

    }

    public function showSensitiveFields()
    {
        $this->showSensitiveFields = true;

        return $this;

    }


}
