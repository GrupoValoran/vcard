<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class TermsConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $termConditionHtml = view('settings.terms_conditions.terms_conditions')->render();
        Setting::create(['key' => 'terms_conditions', 'value' => $termConditionHtml]);
        $privacyPolicyHtml = view('settings.terms_conditions.privacy_policy')->render();
        Setting::create(['key' => 'privacy_policy', 'value' => $privacyPolicyHtml]);
    }
}
