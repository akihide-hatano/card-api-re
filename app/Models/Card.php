<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    use HasFactory;

    protected $table = 'cards';
    // protected $primaryKey = 'card_id';
    public $timestamps = true; // デフォルトtrue。falseにするとcreated_at/updated_atを無視

    // 一括代入を許可するカラムを明示
    protected $fillable = [
        'title',
        'description',
    ];

    protected $casts = [
    'published_at' => 'datetime',
    'is_active' => 'boolean',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
