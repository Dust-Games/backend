<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Add filtering data by request to query
        Builder::macro('filterRequest', function ($request, $callbacks) {
            $input = $request->input();
            foreach ($input as $key => $value) {
                if (array_key_exists($key, $callbacks)) {
                    $callbacks[$key]($this, $value);
                }
            }
            return $this;
        });

        // Check that table is joined to query
        Builder::macro('isJoined', function(string $table) {
            return collect($this->getQuery()->joins)->pluck('table')->contains($table);
        });
    }
}
