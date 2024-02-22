<?php

namespace App\Jobs;

use App\Services\Integracao\IntegradorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IntegradorJob extends IntegradorService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct($request, $tenant)
    {
        parent::setRequest($request);
        parent::setTenant($tenant);
    }

    public function handle() {
        parent::setJobId($this->job->getJobId());
        $this->sinc();
    }

    // public function uniqueId()
    // {
    //     $this->writeLog("Unique ID $this->tenant");
    //     return $this->tenant;
    // }
}