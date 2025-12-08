<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PublicServiceSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_image',
        'intro_title',
        'intro_description',
        'intro_content',
        'regulation_title',
        'regulation_items',
        'policy_title',
        'policy_subtitle',
        'policy_description',
        'policy_signature',
        'policy_position',
        'policy_image',
        'standard_title',
        'standard_description',
        'standard_document_title',
        'standard_document_description',
        'standard_document_file',
        'standard_document_badge',
        'flow_section_title',
        'flow_section_description',
        'cta_title',
        'cta_description',
        'cta_primary_text',
        'cta_primary_link',
        'cta_secondary_text',
        'cta_secondary_link',
    ];

    public function getRegulationListAttribute(): array
    {
        if (!$this->regulation_items) {
            return [];
        }

        $decoded = json_decode($this->regulation_items, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->regulation_items))));
    }

    public function setRegulationItemsAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['regulation_items'] = json_encode($value);
            return;
        }

        $this->attributes['regulation_items'] = $value;
    }
}
