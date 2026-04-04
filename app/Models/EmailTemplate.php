<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['key', 'name', 'subject', 'body', 'variables'];

    /**
     * Return an array of variable names available for this template.
     */
    public function variableList(): array
    {
        if (! $this->variables) {
            return [];
        }

        return array_map('trim', explode(',', $this->variables));
    }

    /**
     * Render the subject with the given variable values substituted.
     */
    public function renderSubject(array $vars = []): string
    {
        return $this->substitute($this->subject, $vars);
    }

    /**
     * Render the body with the given variable values substituted.
     */
    public function renderBody(array $vars = []): string
    {
        return $this->substitute($this->body, $vars);
    }

    private function substitute(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $text = str_replace('{{'.$key.'}}', $value, $text);
        }

        return $text;
    }
}
