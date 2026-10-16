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
        'banner_image',
        'external_url',
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

    /**
     * Determine if the offer has expired or is finished.
     */
    public function isExpired(): bool
    {
        if ($this->state_id == 3) {
            return true;
        }
        if ($this->deadline) {
            $d = $this->deadline->format('H:i:s') === '00:00:00'
                ? $this->deadline->copy()->endOfDay()
                : $this->deadline;
            return $d->isPast();
        }
        return false;
    }

    /**
     * Determine if the offer publication is scheduled for the future.
     */
    public function isScheduled(): bool
    {
        return $this->publication_date && $this->publication_date->isFuture();
    }

    /**
     * Determine if candidates can apply internally.
     */
    public function canApply(): bool
    {
        return !$this->isExpired() && !$this->isScheduled() && $this->state_id == 2 && empty($this->external_url);
    }

    /**
     * Determine if the offer is an external job post.
     */
    public function isExternal(): bool
    {
        return !empty($this->external_url);
    }

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
