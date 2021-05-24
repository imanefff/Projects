<?php

namespace App\Http\Csp\Policies;


use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;

class MyCustomPolicy extends Basic
{
    public function configure()
    {
        parent::configure();

        $this->addDirective(Directive::STYLE, 'fonts.googleapis.com');
            ->addDirective(Directive::FONT, 'fonts.gstatic.com');

    }
}