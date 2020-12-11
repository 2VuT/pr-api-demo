<?php

namespace App;

use Illuminate\Support\Str;

class Prospect
{
    /**
     * Run an interaction method.
     *
     * @param  string  $interaction
     * @param  array  $parameters
     * @return mixed
     */
    public static function call($interaction, array $parameters = [])
    {
        if (! Str::contains($interaction, '@')) {
            $interaction = $interaction.'@handle';
        }

        list($class, $method) = explode('@', $interaction);

        return call_user_func_array([app($class), $method], $parameters);
    }

    /**
     * Get the default filter audiences options.
     *
     * @param  array  $options
     * @return array
     */
    public static function filterAudiencesOptions(array $options = [])
    {
        return array_merge([
            'previously_targeted' => true,
            'targeted_weeks' => 2,
        ], $options);
    }
}
