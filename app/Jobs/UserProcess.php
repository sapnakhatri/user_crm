<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Eloquent\ApiRepo as ApiRepo;
use Log;
use App\Models\User;

class UserProcess implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * The number of times the job may be attempted and override the queue tries.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {       
        try {
            // make api call
           Log::info("inside handle".$this->user->id);
            $apiRepo = new ApiRepo;
            $response = $apiRepo->getUserDetails($this->user->id);
            Log::info("Response".$response);
        } catch (\Throwable $exception) {
            if ($this->attempts() > 3) {
                // hard fail after 3 attempts
                throw $exception;
            }
            // requeue this job to be executes
            // in 3 minutes (180 seconds) from now
            $this->release(180);
            return;
        }
    }

}
