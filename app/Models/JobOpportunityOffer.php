<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOpportunityOffer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_opportunity_offer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'publication_date',
        'deadline',
        'benefits',
        'salary',
        'salary_currency',
        'attachments',
        'address',
        'department',
        'province',
        'country',
        'company_id',
        'modality_id',
        'state_id',
        'category_id',
        'work_schedule_id',
        'contract_type_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publication_date' => 'datetime',
        'deadline' => 'datetime',
        'salary' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function modality()
    {
        return $this->belongsTo(JobOpportunityModality::class, 'modality_id');
    }

    public function state()
    {
        return $this->belongsTo(JobOpportunityOfferState::class, 'state_id');
    }

    public function category()
    {
        return $this->belongsTo(JobOpportunityOfferCategory::class, 'category_id');
    }

    public function workSchedule()
    {
        return $this->belongsTo(JobOpportunityWorkSchedule::class, 'work_schedule_id');
    }

    public function contractType()
    {
        return $this->belongsTo(JobOpportunityContractType::class, 'contract_type_id');
    }

    public function applications()
    {
        return $this->hasMany(JobOpportunityApplication::class, 'offer_id');
    }
}
