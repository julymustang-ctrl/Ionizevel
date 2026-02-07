<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementField extends Model
{
    use HasFactory;

    protected $fillable = [
        'element_definition_id',
        'name',
        'label',
        'type',
        'options',
        'default_value',
        'translatable',
        'required',
        'ordering',
    ];

    protected $casts = [
        'options' => 'array',
        'translatable' => 'boolean',
        'required' => 'boolean',
    ];

    /**
     * Alan tipleri
     */
    const TYPES = [
        'text' => 'Text Input',
        'textarea' => 'Text Area',
        'wysiwyg' => 'WYSIWYG Editor',
        'image' => 'Image',
        'file' => 'File',
        'select' => 'Select Dropdown',
        'checkbox' => 'Checkbox',
        'number' => 'Number',
        'date' => 'Date',
        'color' => 'Color Picker',
        'url' => 'URL',
        'email' => 'Email',
    ];

    /**
     * İlişki: Element tanımı
     */
    public function definition()
    {
        return $this->belongsTo(ElementDefinition::class, 'element_definition_id');
    }
}
