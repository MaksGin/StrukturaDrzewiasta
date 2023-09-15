<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    use HasFactory;

    protected $table = 'katalogi';

    public function podkatalogi(){
        return $this->HasMany(Katalog::class,'rodzic_id');
    }

    public function nadrzedny(){
        return $this->BelongsTo(Katalog::class,'rodzic_id');
    }
}
