<?php

declare(strict_types=1);

it('pauses its animated canvas for reduced motion and hidden tabs', function (): void {
    $layout = file_get_contents(dirname(__DIR__, 2).'/layouts/app.blade.php');
    expect($layout)->toContain('prefers-reduced-motion', 'visibilitychange', 'cancelAnimationFrame');
});
