<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_halaman_utama_dialihkan_ke_dashboard(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
    }
}
