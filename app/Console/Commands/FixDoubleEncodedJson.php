<?php

namespace App\Console\Commands;

use App\Models\DiplomaTemplateElement;
use Illuminate\Console\Command;

class FixDoubleEncodedJson extends Command
{
    protected $signature = 'fix:double-encoded-json';
    protected $description = 'Fix double-encoded config_json values in diploma_template_elements';

    public function handle()
    {
        $elements = DiplomaTemplateElement::all();
        $fixed = 0;

        foreach ($elements as $el) {
            $raw = $el->getRawOriginal('config_json');
            if ($raw === null) continue;

            $decoded = json_decode($raw, true);

            // If decoded is a string, it's double-encoded
            if (is_string($decoded)) {
                $this->line("Fixing element {$el->id}: raw is a JSON string, re-decoding...");
                $second = json_decode($decoded, true);
                if (is_array($second)) {
                    $el->config_json = $second;
                    $el->save();
                    $fixed++;
                    $this->line("  -> Fixed, has keys: " . implode(', ', array_keys($second)));
                } else {
                    $this->error("  -> Could not decode inner JSON for element {$el->id}");
                }
            } elseif (!is_array($decoded)) {
                $this->warn("Element {$el->id}: config_json is not array or string: " . gettype($decoded));
            }
        }

        $this->info("Fixed {$fixed} elements.");
    }
}
