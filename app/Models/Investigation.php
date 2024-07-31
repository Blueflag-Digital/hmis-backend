<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function consultations()
    {
        return $this->belongsToMany(Consultation::class, 'patient_investigation')
            ->withPivot('status', 'results', 'ordered_at', 'updated_at')
            ->withTimestamps();

    }

    public static function codeGenerator(){
        return  'INV'.mt_rand(200,300);
    }
    public static function generateUniqueInvestigationCode(){
        $code = self::codeGenerator();
        if(Investigation::where('code',$code)->exists()){
           return self::generateUniqueInvestigationCode();
        }
        return $code;
    }

    public function investigationsData(){
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'type' =>$this->type
        ];
    }
}
