<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class JobOpportunityOffer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Cache schema column availability for resilience across environments.
     */
    protected static ?array $existingColumns = null;

    /**
     * Ensure database columns exist dynamically (self-healing for legacy production databases).
     */
    public static function ensureSchemaCompatibility(): void
    {
        if (static::$existingColumns !== null) {
            return;
        }

        try {
            if (!Schema::hasTable('job_opportunity_offer')) {
                static::$existingColumns = [];
                return;
            }

            // Check and add external_url if missing in database
            if (!Schema::hasColumn('job_opportunity_offer', 'external_url')) {
                try {
                    Schema::table('job_opportunity_offer', function (Blueprint $table) {
                        $table->string('external_url', 500)->nullable();
                    });
                } catch (\Throwable $e) {
                    Log::warning('Could not auto-add external_url to job_opportunity_offer: ' . $e->getMessage());
                }
            }

            // Check and add banner_image if missing in database
            if (!Schema::hasColumn('job_opportunity_offer', 'banner_image')) {
                try {
                    Schema::table('job_opportunity_offer', function (Blueprint $table) {
                        $table->string('banner_image', 500)->nullable();
                    });
                } catch (\Throwable $e) {
                    Log::warning('Could not auto-add banner_image to job_opportunity_offer: ' . $e->getMessage());
                }
            }

            // Check and add vacancies if missing in database
            if (!Schema::hasColumn('job_opportunity_offer', 'vacancies')) {
                try {
                    Schema::table('job_opportunity_offer', function (Blueprint $table) {
                        $table->unsignedInteger('vacancies')->default(1);
                    });
                } catch (\Throwable $e) {
                    Log::warning('Could not auto-add vacancies to job_opportunity_offer: ' . $e->getMessage());
                }
            }

            static::$existingColumns = Schema::getColumnListing('job_opportunity_offer');
        } catch (\Throwable $e) {
            static::$existingColumns = [];
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($offer) {
            static::ensureSchemaCompatibility();

            // Guard against SQL 1054 Unknown column errors if column still does not exist in DB
            if (is_array(static::$existingColumns) && count(static::$existingColumns) > 0) {
                if (!in_array('external_url', static::$existingColumns)) {
                    unset($offer->attributes['external_url']);
                }
                if (!in_array('banner_image', static::$existingColumns)) {
                    unset($offer->attributes['banner_image']);
                }
                if (!in_array('vacancies', static::$existingColumns)) {
                    unset($offer->attributes['vacancies']);
                }
            }
        });
    }

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
        'vacancies',
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
        'vacancies' => 'integer',
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
